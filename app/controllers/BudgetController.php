<?php

namespace app\controllers;

use app\models\Budget;

class BudgetController extends AppController {

    public function indexAction() {
        // получаем сценарий для просмотра бюджетных операций если он есть
        $filter_date = isset($_GET['filter']) ? $_GET['filter'] : date('Y-m-d');
        $year = mb_substr($filter_date, 0, 4);  // выделяем месяц сценария
        $month = mb_substr($filter_date, 5, 2); // выделяем год сценария
        $scenario = $year . '-' . $month . '-01';
        // получение данных из БД соответственно сценарию
        $budgets = \R::find('budget', "WHERE scenario = '{$scenario}' AND status = 'Согласован' ORDER BY scenario, number");
        // получаем расходы по выбранным БО
        foreach ($budgets as $item) {
            // получаем составной номер БО НОМЕР/ГОД
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

    /**
     * Функция подсчитывающая расход по БО
     * @param $payments array Все оплаты содержащие проверяемую БО
     * @param $num_bo string Составной номер БО (НОМЕР/ГОД)
     * @param $vat_bo float|string Ставка НДС проверяемой БО
     * @return float|string Сумма расходов по БО
     */
    private function get_sum($payments, $num_bo, $vat_bo) {
        $sum = 0.00; // расход по данной БО
        foreach ($payments as $payment) { // просматриваем все оплаты использующие нашу БО
            $nums = explode(';', trim($payment['num_bo']));
            $sums = explode(';', trim($payment['sum_bo']));
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
            $pay['date_pay'] = $payment['date_pay'];
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
        // формируем составной номер БО (НОМЕР/ГОД)
        $num_bo = $bo['number'].'/'.$year;
        // получаем все оплаты по этой БО
        $budget = new Budget();
        $payments = $budget->getBudgetPayment($num_bo);
        // добавляем в массив оплаченную сумму
        $bo['payment'] = $this->get_sum($payments, $num_bo, $bo['vat']);
        $bo['pay_arr'] = $this->get_array_sum($payments, $num_bo, $bo['vat']);
        // формируем метатеги для страницы
        $this->setMeta("Просмотр бюджетной операции {$bo['number']}", 'Описание...', 'Ключевые слова...');
        // Передаем полученные данные в вид
        $this->set(compact('bo', 'payments'));
    }

    public function editAction() {
        $id_bo = isset($_GET['id']) ? $_GET['id'] : null;
        // получаем данные по БО
        $budget = \R::findOne('budget', 'id = ?', [$id_bo]);
        // получаем все статьи расхода
        $budget_items = \R::getAll('SELECT * FROM budget_items');
        //debug($budget_items);
        if ($this->isAjax()) {
            // Если запрос пришел АЯКСом
            $this->loadView('budget_edit_modal', compact('budget', 'budget_items'));
        }
        redirect();
    }

    public function boEditAction() {
        // получаем данные пришедшие методом POST
        $edit_budget = !empty($_POST) ? $_POST : null;
        $budget = new Budget();
        $budget->load($edit_budget);
        $budget->edit('budget', $edit_budget['id']);
        redirect();
    }

    public function uploadAction() {
        $file = !empty($_POST['file']) ? $_POST['file'] : null;
        debug($file);
        // формируем метатеги для страницы
        $this->setMeta('Загрузка новых БО', '', '');
        // Передаем полученные данные в вид
        //$this->set(compact('receipt'));
    }

}
