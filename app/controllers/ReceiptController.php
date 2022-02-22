<?php

namespace app\controllers;

use app\models\Er;
use app\models\Payment;
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
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        if ($id) {
            R::hunt('receipt', 'id = ?', [$id]);
        }
        redirect();
    }

    public function payAction() {
        // получаем переданный идентификатор прихода
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        $partner_id = !empty($_GET['partner']) ? (int)$_GET['partner'] : null;
        // получение сопутствующих данных
        // получаем все действующие ЕР для этого КА
        $ers = \R::getAll('SELECT er.*, budget_items.name_budget_item FROM er, budget_items WHERE (budget_items.id = er.id_budget_item) AND (data_end >= CURDATE()) AND id_partner = ?', [$partner_id]);
        // получаем массив используемых статей расхода
        $budget_items = [];
        $er = [];
        foreach ($ers as $k => $v) {
            $budget_items[] = $v['name_budget_item'];
            $er[$k]['budget'] = $v['name_budget_item'];
            $er[$k]['number'] = $v['number'];
        }
        // необходимо получить используемые БО

        // нужно проверить есть ли у этого прихода ЗО
        $receipt = \R::findOne('receipt', 'id = ?', [$id]);
        $name = $receipt->partner;
        $year = date('Y', strtotime($receipt->date));
        $receipt_num = $receipt->number . '/' . $year;
        $payments = \R::findOne('payment', 'receipt = ?', [$receipt_num]);

        // получаем все неоплаченные приходы этого КА
        $receipts = \R::find('receipt', 'partner = ? AND date_pay IS NULL', [$name]);
        $recs = [];
        foreach ($receipts as $receipt) {
            $recs[] = dateYear($receipt->number, $receipt->date);
        }


        if (!empty($payments)) {
            // если есть редактируем ее. Получаем идентификатор оплаты
            $payment = new Payment();
            $payment->editPayment($name, $receipt_num, $recs, $er, $payments);
            if ($this->isAjax()) {
                // Если запрос пришел АЯКСом
                $this->loadView('payment_add_modal');
            }
            redirect();
        } else {
            // если нет добавляем ее
            $payment = new Payment();
            $payment->addPayment($name, $receipt_num, $recs, $er);
            if ($this->isAjax()) {
                // Если запрос пришел АЯКСом
                $this->loadView('payment_add_modal');
            }
            redirect();
        }





        /*/ получаем данные пришедшие методом POST
        // получаем переданное наименование КА
        $partner = !empty($_GET['partner']) ? $_GET['partner'] : null;
        $receipt = new Receipt();
        $receipt->addReceipt($partner);
        */
    }

}
