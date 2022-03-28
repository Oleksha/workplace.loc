<?php

namespace app\models;

class Receipt extends AppModel {

    // поля таблицы для заполнения
    public $attributes = [
        'date' => '',
        'number' => '',
        'sum' => '',
        'type' => '',
        'vat' => '',
        'partner' => '',
        'num_doc' => '',
        'date_doc' => '',
        'note' => null,
        'num_pay' => null,
        'date_pay' => null,
    ];

    public function editReceipt($receipt) {
        unset($_SESSION['receipt']); // Очищаем сессию
        if (!is_null($receipt)) {
            $_SESSION['receipt'] = [
                'id' => $receipt->id,
                'date' => $receipt->date,
                'number' => $receipt->number,
                'sum' => $receipt->sum,
                'type' => $receipt->type,
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

    public function addReceipt($name) {
        unset($_SESSION['receipt']); // Очищаем сессию
        $_SESSION['receipt'] = [
            'date' => null,
            'number' => null,
            'sum' => null,
            'type' => null,
            'vat' => null,
            'partner' => $name,
            'num_doc' => null,
            'date_doc' => null,
            'note' => null,
            'num_pay' => null,
            'date_pay' => null,
        ];
    }

}
