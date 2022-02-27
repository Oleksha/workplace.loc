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
        $ers = \R::getAll("SELECT er.*, budget_items.name_budget_item FROM er, budget_items WHERE (budget_items.id = er.id_budget_item) AND (data_end >= CURDATE()) AND id_partner = ?", [$partner_id]);
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
        $receipt_num = '%' . $receipt->number . '/' . $year . '%';
        $payments = \R::findOne('payment', "receipt LIKE ?", [$receipt_num]);


        // получаем все неоплаченные приходы этого КА
        $receipts = \R::find('receipt', 'partner = ? AND date_pay IS NULL', [$name]);
        $recs = []; // массив содержащий приходы в формате TOF0000000/2022
        $sums = []; // массив содержащий суммы
        foreach ($receipts as $k => $v) {
            $recs[] = dateYear($v->number, $v->date);
            $sums[$k]['number'] = dateYear($v->number, $v->date);
            $sums[$k]['summa'] = $v->sum;
        }
        //debug($recs);debug($sums);die;
        $payment = new Payment();
        if ($payments) {
            // если есть редактируем ее. Получаем идентификатор оплаты
            $payment->editPayment($name, $recs, $er, $payments);
        } else {
            // если нет добавляем ее
            $payment->addPayment($name, trim($receipt_num,'%'), $recs, $er, $sums);
            //debug($_SESSION['payment']);die;
        }
        if ($this->isAjax()) {
            // Если запрос пришел АЯКСом
            $this->loadView('payment_add_modal');
        }
        redirect();


        /*/ получаем данные пришедшие методом POST
        // получаем переданное наименование КА
        $partner = !empty($_GET['partner']) ? $_GET['partner'] : null;
        $receipt = new Receipt();
        $receipt->addReceipt($partner);
        */
    }
    
    public function payReceiptAction() {
        // получаем данные пришедшие методом POST
        $pay_receipt = !empty($_POST) ? $_POST : null;
        $num = '';
        $receipt = $pay_receipt['receipt'];
        foreach ($pay_receipt['receipt'] as &$value) {
            $num .= $value . ';';
        }
        $num = rtrim($num, ';'); 
        $pay_receipt['receipt'] = $num;
        $num = '';
        foreach ($pay_receipt['num_er'] as &$value) {
            $num .= $value . ';';
        }
        $num = rtrim($num, ';'); 
        $pay_receipt['num_er'] = $num;
        if (empty($pay_receipt['date_pay'])) {
            $pay_receipt['date_pay'] = NULL;
             //echo 'заменил на NULL';
        }
        if (empty($pay_receipt['id'])) {
            //echo 'это новая ЗО';
            $pay = new Payment();
            $pay->load($pay_receipt);
            $pay->save('payment'); 
        } else {
            //echo 'это редактируемая ЗО';
            $pay = new Payment();
            $pay->load($pay_receipt);
            $pay->edit('payment', $pay_receipt['id']);            
        }
        // внесение изменений в приход
        $id = $this->editMy($receipt); // получаем массив ID приходов
        foreach ($id as $k => $v) {
            $edit_receipt['id'] = $v['id'];
            $edit_receipt['date'] = $v['date'];
            $edit_receipt['number'] = $v['number'];
            $edit_receipt['sum'] = $v['sum'];
            $edit_receipt['vat'] = $v['vat'];
            $edit_receipt['partner'] = $v['partner'];
            $edit_receipt['num_doc'] = $v['num_doc'];
            $edit_receipt['date_doc'] = $v['date_doc'];
            $edit_receipt['note'] = $v['note'];
            $edit_receipt['num_pay'] = dateYear($pay_receipt['number'], $pay_receipt['date']);
            $edit_receipt['date_pay'] = $v['date_pay'];
            $receipt = new Receipt();
            $receipt->load($edit_receipt);
            $receipt->edit('receipt', $edit_receipt['id']);
        }
        redirect();        
    }

    public function editMy($receipt) {
        // проходимся по всем элементам массива
        $id_receipt = [];
        foreach ($receipt as &$value) {
            $num_receipt = $value; // номер прихода 00000000/2022
            $num_receipt = explode('/', $num_receipt);
            $number = $num_receipt[0]; // 00000000
            $year = $num_receipt[1];   // 2022
            // получаем  данные о приходе
            $receipt_full = \R::findOne('receipt', "number = ? AND YEAR(date) = {$year}", [$number]);
            $id_receipt[] = $receipt_full->id;
        }
        return $id_receipt;
    }

}
