<?php

namespace app\models;

class Payment extends AppModel {

    // поля таблицы для заполнения
    public $attributes = [
        'date' => '',
        'number' => '',
        'sum' => '',
        'vat' => '',
        'partner' => '',
        'receipt' => '',
        'num_er' => null,
        'sum_er' => null,
        'num_bo' => '',
        'sum_bo' => '',
        'date_pay' => null,
    ];

    public function editPayment($name, $receipts, $ers, $payments, $sums) {
        unset($_SESSION['payment']); // Очищаем сессию
        $_SESSION['payment'] = [
            'id' => $payments['id'],
            'number' => $payments['number'],
            'date' => $payments['date'],
            'sum' => $payments['sum'],
            'vat' => $payments['vat'],
            'partner' => $name,
            'receipt' => $sums,
            'num_er' => $ers,
            'sum_er' => $payments['sum_er'],
            'num_bo' => $payments['num_bo'],
            'sum_bo' => $payments['sum_bo'],
            'date_pay' => $payments['date_pay'],
            'receipt_current' => explode(';', $payments['receipt']),
            'num_er_current' => explode(';', $payments['num_er']),
        ];
    }

    public function addPayment($name, $receipt, $receipts, $ers, $sums) {
        unset($_SESSION['payment']); // Очищаем сессию
        $_SESSION['payment'] = [
            'date' => null,
            'number' => null,
            'sum' => $sums,
            'vat' => null,
            'partner' => $name,
            'receipt' => $sums,
            'num_er' => $ers,
            'sum_er' => null,
            'num_bo' => null,
            'sum_bo' => null,
            'date_pay' => null,
            'receipt_current' => explode(';', $receipt),
            'num_er_current' => null,
        ];
    }

    /**
     * Возвращает массив всех оплат по конкретной ЕР
     * @param $name string наименование КА
     * @param $er string наименование КА
     * @return array|false
     */
    public function getPaymentEr($name, $er) {
        $er_num = '%' . $er . '%';
        $payments = \R::getAssocRow("SELECT * FROM payment WHERE (partner = '{$name}') AND (num_er LIKE ?)", [$er_num]);
        if (!empty($payments)) return $payments;
        return false;
    }

    public function getPaymentFromReceipt($receipt) {
        $payments = \R::getAssocRow("SELECT * FROM payment WHERE receipt LIKE ?", [$receipt]);
        if (!empty($payments)) return $payments;
        return false;
    }

}
