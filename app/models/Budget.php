<?php

namespace app\models;

class Budget extends AppModel {

    // поля таблицы для заполнения
    public $attributes = [
        'scenario' => '',
        'month_exp' => '',
        'month_pay' => '',
        'number' => '',
        'summa' => '',
        'vat' => '',
        'budget_item' => '',
        'status' => '',
    ];
    public $payment = [
        'date' => '',
        'number' => '',
        'sum' => '',
        'vat' => '',
        'partner' => '',
        'receipt' => '',
        'num_er' => '',
        'sum_er' => '',
        'num_bo' => '',
        'sum_bo' => '',
        'date_pay' => '',
    ];
    public $partner = [
        'name' => '',
        'alias' => '',
        'inn' => '',
        'kpp' => '',
        'bank' => '',
        'bic' => '',
        'account' => '',
        'address' => '',
        'phone' => '',
        'email' => '',
        'delay' => '',
        'vat' => '',
    ];

    /**
     * @param $number string номер БО в формате (CUB000000000/2021)
     * @return array возвращает массив с оплатами и данными по приходам
     */
    public function getBudgetPayment($number) {
        $pays = []; $payments = [];
        $number = '%' . $number . '%';
        $pay_arrays = \R::find('payment', 'num_bo LIKE ? ORDER BY date_pay', [$number]);
        foreach ($pay_arrays as $pay_array) {
            // проходим по всем атрибутам
            foreach ($this->payment as $name => $value) {
                // если в переданных данных data есть имя ключа атрибута
                if (isset($pay_array[$name])) {
                    // запоминаем в атрибуте соответсвующее значение
                    $pays[$name] = $pay_array[$name];
                    if ($name == 'partner') {
                        $pays[$name] = $this->getBudgetPartner($pay_array[$name]);
                    }
                }
            }
            $payments[] = $pays;
        }
        return $payments;
    }

    /**
     * Возвращает массив с данными о КА
     * @param $names string наименование КА
     * @return array данные о КА
     */
    public function getBudgetPartner($names) {
        $part = [];
        $partner = \R::findOne('partner', 'name = ?', [$names]);
        //debug($partner);
        // проходим по всем атрибутам
        foreach ($this->partner as $name => $value) {
            // если в переданных данных data есть имя ключа атрибута
            if (isset($partner[$name])) {
                // запоминаем в атрибуте соответсвующее значение
                $part[$name] = $partner[$name];
            }
        }
        return $part;
    }

    /**
     * Функция возвращает диапазон +/- 1 месяц от переданной даты
     * @param $date string переданная дата
     * @return void
     */
    public function getRangeOneMonth($date) {

    }

    /**
     * Возвращает массив содержащий номера оплат и суммы расхода по ЕР
     * @param $num_bo string номер ЕР
     * @return array
     */
    public function getPaymentCoast($num_bo) {
        $pay_obj = new Payment();
        $bo = $this->getBo($num_bo);
        $bo['number'] = $bo['number'] . '/' . substr($bo['scenario'], 0, 4);
        $pay = [];
        // получаем все оплаты использующие эту БО
        $payments = $pay_obj->getPaymentBo($num_bo);
        if ($payments) {
            // проходимся по всем оплатам использующим нашу ЕР
            $st = 0.00;

            foreach ($payments as $payment) {
                $vat = $payment['vat'];
                $nums = explode(';', trim($payment['num_bo']));
                $sums = explode(';', trim($payment['sum_bo']));
                $key = array_search($bo['number'], $nums);
                $sum = $sums[$key];
                $pay_bo['number'] = $payment['number'] . '/' . substr($payment['date'], 0, 4);
                if ($bo['vat'] == '1.20') {
                    if ($vat == '1.20') {
                        $pay_bo['summa'] = $sum;
                    }
                    if ($vat == '1.00') {
                        $pay_bo['summa'] = round($sum * 1.2, 2);
                    }
                }
                if ($bo['vat'] == '1.00') {
                    if ($vat == '1.20') {
                        $pay_bo['summa'] = round($sum / 1.2, 2);
                    }
                    if ($vat == '1.00') {
                        $pay_bo['summa'] = $sum;
                    }
                }
                $st += $pay_bo['summa'];
                $pay[] = $pay_bo;
            }
        }
        
        return $pay;
    }


    /**
     * возвращает все данные по ЕР по ее номеру
     * @param $num_bo string в формате CUB0000000000/2022
     * @return array|null
     */
    public function getBo($num_bo) {
        if (strpos($num_bo, '/')) {
            $bos = explode('/', $num_bo);
            $bo = \R::getAssocRow("SELECT * FROM budget WHERE YEAR(scenario) = ? AND number = ?", [$bos[1], $bos[0]]);
            return $bo[0];
        }
        return null;
    }

}
