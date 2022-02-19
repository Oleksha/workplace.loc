<?php

namespace app\controllers;

use app\models\Er;
use app\models\Receipt;
use RedBeanPHP\R;

class ReceiptController extends AppController {

    public function editAction() {
        // получаем переданный идентификатор прихода
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        //debug($id); die;
        $receipt = null;
        if ($id) {
            // если у нас есть ID получаем все данные об этом приходе
            $receipt = \R::findOne('receipt', 'id = ?', [$id]);
            if (!$receipt) return false; // если такого прихода нет дальнейшие действия бессмысленны
        }
        $rec = new Receipt();
        $rec->editReceipt($receipt);
        if ($this->isAjax()) {
            // Если запрос пришел АЯКСом
            $this->loadView('receipt_edit_modal');
        }
        redirect();
    }

    public function editReceiptAction() {
        // получаем данные пришедшие методом POST
        $edit_receipt = !empty($_POST) ? $_POST : null;
        $receipt = new Receipt();
        $receipt->load($edit_receipt);
        $receipt->edit('receipt', $edit_receipt['id']);
        redirect();
    }

    public function addAction() {
        // получаем данные пришедшие методом POST
        // получаем переданное наименование КА
        $partner = !empty($_GET['partner']) ? $_GET['partner'] : null;
        $receipt = new Receipt();
        $receipt->addReceipt($partner);
        if ($this->isAjax()) {
            // Если запрос пришел АЯКСом
            $this->loadView('receipt_add_modal');
        }
        redirect();
    }

    public function addReceiptAction() {
        // получаем данные пришедшие методом POST
        $add_receipt = !empty($_POST) ? $_POST : null;
        $receipt = new Receipt();
        $receipt->load($add_receipt);
        $receipt->save('receipt');
        redirect();
    }

    public function delAction() {
        // получаем переданный идентификатор ЕР
        $this->id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        if ($this->id) {
            R::hunt('er', 'id = ?', [$this->id]);
        }
        redirect();
    }

}