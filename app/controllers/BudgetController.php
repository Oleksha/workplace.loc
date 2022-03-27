<?php

namespace app\controllers;

use app\models\Budget;

class BudgetController extends AppController {

    public function indexAction() {
        $cur_date = isset($_GET['filter']) ? $_GET['filter'] : date('Y-m-d');
        $year = mb_substr($cur_date, 0, 4);
        $month = mb_substr($cur_date, 5, 2);
        $budget_date = $year . '-' . $month . '-01';
        //debug($budget_date);die;
        // получение БО из БД
        $budgets = \R::find('budget', "WHERE scenario = '{$budget_date}' AND status = 'Согласован' ORDER BY scenario, number");
        // получаем расходы по выбранным БО
        foreach ($budgets as $item) {
            // получаем номер БО 
            $num_bo = $item['number'].'/'.$year;
            $payments = \R::find('payment', "num_bo LIKE '%{$num_bo}%'");
            $item['payment'] = $this->get_sum($payments, $num_bo, $item['vat']);
        }
        // начинаем работать с AJAX-запросом если включены фильтра
        // если данные пришли AJAX-запросом
        if ($this->isAjax()) {

            $this->loadView('filter', compact('budgets', 'year', 'month'));
        }
        // формируем метатеги для страницы
        $this->setMeta('Список бюджетных операций', 'Описание...', 'Ключевые слова...');
        // Передаем полученные данные в вид
        $this->set(compact('budgets', 'year', 'month'));
    }

    private function get_sum($payments, $num_bo, $vat_bo) {
        $sum = 0.00;
        foreach ($payments as $payment) {
            $nums = explode(';', trim($payment['num_bo']));//->num_bo));
            $sums = explode(';', trim($payment['sum_bo']));//->sum_bo));
            $key = array_search($num_bo, $nums);
            if ($vat_bo == '1.20') {
                // если БО с НДС
                if ($payment['vat'] == '1.20') {
                    // если платеж с НДС
                    $sum += $sums[$key];
                }
                if ($payment['vat'] == '1.00') {
                    // если платеж без НДС
                    $sum += round($sums[$key] * 1.2, 2);
                }
            }
            if ($vat_bo == '1.00') {
                // если БО без НДС
                if ($payment['vat'] == '1.00') {
                    // если платеж без НДС
                    $sum += $sums[$key];
                }
                if ($payment['vat'] == '1.20') {
                    // если платеж с НДС
                    $sum += round($sums[$key] / 1.2, 2);
                }
            }            
        }
        return $sum;
    }

    private function get_array_sum($payments, $num_bo, $vat_bo) {
        $pay_arr = [];
        foreach ($payments as $payment) {
            $pay['date'] = $payment['date'];
            $nums = explode(';', trim($payment['num_bo']));//->num_bo));
            $sums = explode(';', trim($payment['sum_bo']));//->sum_bo));
            $key = array_search($num_bo, $nums);
            if ($vat_bo == '1.20') {
                // если БО с НДС
                if ($payment['vat'] == '1.20') {
                    // если платеж с НДС
                    $pay['summa'] = $sums[$key];
                }
                if ($payment['vat'] == '1.00') {
                    // если платеж без НДС
                    $pay['summa'] = round($sums[$key] * 1.2, 2);
                }
            }
            if ($vat_bo == '1.00') {
                // если БО без НДС
                if ($payment['vat'] == '1.00') {
                    // если платеж без НДС
                    $pay['summa'] = $sums[$key];
                }
                if ($payment['vat'] == '1.20') {
                    // если платеж с НДС
                    $pay['summa'] = round($sums[$key] / 1.2, 2);
                }
            }  
            $pay['partner'] = $payment['partner'];
            $pay_arr[] = $pay;      
        }
        return $pay_arr;
    }

    public function viewAction() {
        $id_bo = isset($_GET['id']) ? $_GET['id'] : null;
        $bo = \R::findOne('budget', 'id = ?', [$id_bo]);
        $year = mb_substr($bo['scenario'], 0, 4);
        // формируем строку в виде CUB012345678/2022
        $num_bo = $bo['number'].'/'.$year;
        // получаем все оплты по этой БО
        $budget = new Budget();
        //debug($budget->getBudgetPayment($num_bo));die;
        $payments = $budget->getBudgetPayment($num_bo);//\R::find('payment', "num_bo LIKE '%{$num_bo}%' ORDER BY date");
        // добавляем в массив оплаченную сумму
        $bo['payment'] = $this->get_sum($payments, $num_bo, $bo['vat']);
        $bo['pay_arr'] = $this->get_array_sum($payments, $num_bo, $bo['vat']);
        // формируем метатеги для страницы
        $this->setMeta("Просмотр бюджетной операции {$bo['number']}", 'Описание...', 'Ключевые слова...');
        // Передаем полученные данные в вид
        $this->set(compact('bo', 'payments'));
    }

}
