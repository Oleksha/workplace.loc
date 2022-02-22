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
        'num_er' => '',
        'sum_er' => '',
        'num_bo' => '',
        'sum_bo' => '',
        'date_pay' => '',
    ];

    public function editPayment($name, $receipt, $receipts, $ers, $payments) {
        unset($_SESSION['payment']); // Очищаем сессию
        $_SESSION['payment'] = [
            'id' => $payments['id'],
            'number' => $payments['number'],
            'date' => $payments['date'],
            'sum' => $payments['sum'],
            'vat' => $payments['vat'],
            'partner' => $name,
            'receipt' => $receipts,
            'num_er' => $ers,
            'sum_er' => $payments['sum_er'],
            'num_bo' => $payments['num_bo'],
            'sum_bo' => $payments['sum_bo'],
            'date_pay' => null,
            'receipt_current' => $receipt,
            'num_er_current' => $payments['num_er'],
        ];
    }

    public function addPayment($name, $receipt, $receipts, $ers) {
        unset($_SESSION['payment']); // Очищаем сессию
        $_SESSION['payment'] = [
            'date' => null,
            'number' => null,
            'sum' => null,
            'vat' => null,
            'partner' => $name,
            'receipt' => $receipts,
            'num_er' => $ers,
            'sum_er' => null,
            'num_bo' => null,
            'sum_bo' => null,
            'date_pay' => null,
            'receipt_current' => $receipt,
        ];
    }

}
