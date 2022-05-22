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

    /**
     * Возвращает массив неоплаченных приходов для КА
     * @param $name string наименование КА
     * @return array|false
     */
    public function getReceiptNoPay($name) {
        $receipts = \R::getAssocRow('SELECT * FROM receipt WHERE partner = ? AND date_pay IS NULL ORDER BY date', [$name]);
        if (!empty($receipts)) return $receipts;
        return false;
    }

    /**
     * Возвращает массив всех приходов для КА
     * @param $field string поле по которому происходит отбор
     * @param $value string значение по которому происходит отбор
     * @return array|false
     */
    public function getReceipt($field, $value) {
        $receipts = \R::getAssocRow("SELECT * FROM receipt WHERE $field = ? ORDER BY date", [$value]);
        if (!empty($receipts)) return $receipts;
        return false;
    }

}
