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

}
