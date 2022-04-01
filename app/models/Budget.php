<?php

namespace app\models;

class Budget extends AppModel {

    // поля таблицы для заполнения
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
     * @param $names string наименование КА
     * @return array возвращает массив с данными о КА
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

}
