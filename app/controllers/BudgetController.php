<?php

namespace app\controllers;

class BudgetController extends AppController {

    public function indexAction() {
        $year = isset($_GET['y']) ? $_GET['y'] : date('Y');
        $month = isset($_GET['m']) ? $_GET['m'] : date('m');
        $budget_date = $year . '-' . $month . '-01';
        // получение БО из БД
        $budgets = \R::find('budget', "WHERE scenario = '{$budget_date}' ORDER BY scenario, number");
        // формируем метатеги для страницы
        $this->setMeta('Список бюджетных операций', 'Описание...', 'Ключевые слова...');
        // получаем расходы по выбранным БО
        foreach ($budgets as $item) {
            // получаем номер БО 
            $num_bo = $item['number'].'/'.$year;
            $payments = \R::find('payment', "num_bo LIKE '%{$num_bo}%'");
            $item['payment'] = $this->get_sum($payments, $num_bo, $item['vat']);
        }
        // Передаем полученные данные в вид
        $this->set(compact('budgets', 'year', 'month'));
    }

    private function get_sum($payments, $num_bo, $vat_bo) {
        $sum = 0.00;
        foreach ($payments as $payment) {
            $nums = explode(';', trim($payment->num_bo));
            $sums = explode(';', trim($payment->sum_bo));
            $key = array_search($num_bo, $nums);
            if ($vat_bo == '1.20') {
                // если БО с НДС
                if ($payment->vat == '1.20') {
                    // если платеж с НДС
                    $sum += $sums[$key];
                }
                if ($payment->vat == '1.00') {
                    // если платеж без НДС
                    $sum += round($sums[$key] * 1.2, 2);
                }
            }
            if ($vat_bo == '1.00') {
                // если БО без НДС
                if ($payment->vat == '1.00') {
                    // если платеж без НДС
                    $sum += $sums[$key];
                }
                if ($payment->vat == '1.20') {
                    // если платеж с НДС
                    $sum += round($sums[$key] / 1.2, 2);
                }
            }            
        }
        return $sum;
    }

}
