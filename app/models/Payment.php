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

    public function editReceipt($receipt) {
        unset($_SESSION['receipt']); // Очищаем сессию
        if (!is_null($receipt)) {
            $_SESSION['receipt'] = [
                'id' => $receipt->id,
                'date' => $receipt->date,
                'number' => $receipt->number,
                'sum' => $receipt->sum,
                'vat' => $receipt->vat,
                'partner' => $receipt->partner,
                'num_doc' => $receipt->num_doc,
                'date_doc' => $receipt->date_doc,
                'note' => $receipt->note,
                'num_pay' => $receipt->num_pay,
                'date_pay' => $receipt->date_pay,
            ];
        }
    }

    public function addPayment($name, $receipt, $receipts) {
        unset($_SESSION['payment']); // Очищаем сессию
        $_SESSION['payment'] = [
            'date' => null,
            'number' => null,
            'sum' => null,
            'vat' => null,
            'partner' => $name,
            'receipt' => $receipts,
            'num_er' => null,
            'sum_er' => null,
            'num_bo' => null,
            'sum_bo' => null,
            'date_pay' => null,
            'receipt_current' => $receipt,
        ];
    }

}
