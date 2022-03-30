<?php

namespace app\controllers;

use app\models\Payment;
use app\models\Receipt;
use RedBeanPHP\R;

class ReceiptController extends AppController {

    /**
     * @return void
     */
    public function editAction() {
        // получаем переданный идентификатор прихода
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        /** @var array $receipt данные о приходе*/
        $receipt = null;
        if ($id) {
            // если у нас есть ID получаем все данные об этом приходе
            $receipt = \R::findOne('receipt', 'id = ?', [$id]);
            if (!$receipt) die; // если такого прихода нет дальнейшие действия бессмысленны
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
        $vat = !empty($_GET['vat']) ? $_GET['vat'] : null;
        //debug($vat);die;
        $receipt = new Receipt();
        $receipt->addReceipt($partner);
        $_SESSION['receipt']['vat'] = $vat;
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
        // получаем переданные GET данные
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;                    // идентификатор прихода  
        $vat = !empty($_GET['vat']) ? $_GET['vat'] : null;                      // ставка НДС по которой работает КА
        $partner_id = !empty($_GET['partner']) ? (int)$_GET['partner'] : null;  // идентификатор контрагента
        $receipt = \R::findOne('receipt', 'id = ?', [$id]);                     // получаем полные данные о текущем приходе
        $pay_key = !is_null($receipt->date_pay);                 // индикатор оплаты прихода
        /***** Начало получения данных для формирования заявки на оплату (ЗО) ******/
        /* Получаем все действующие ЕР для этого КА на момент прихода */
        $ers = \R::getAll("SELECT er.*, budget_items.name_budget_item FROM er, budget_items WHERE (budget_items.id = er.id_budget_item) AND (data_start <= '{$receipt->date}') AND (data_end >= '{$receipt->date}') AND id_partner = ?", [$partner_id]);
        $er = [];
        foreach ($ers as $k => $v) {
            $er[$k]['budget'] = $v['name_budget_item'];
            $er[$k]['number'] = $v['number'];
        }
        $ers = $er;
        /* Проверяем есть ли у этого прихода завка на оплату (ЗО) */
        $name = $receipt->partner;                                              // Имя КА (ВСС ООО)
        $year = date('Y', strtotime($receipt->date));                           // Получаем год прихода (2022)
        $receipt_num = '%' . $receipt->number . '/' . $year . '%';              // Получаем используемый номер прихода (TOF00000000/2022)
        $payments = \R::findOne('payment', "receipt LIKE ?", [$receipt_num]);   // Получаем заявку на оплату для этого прихода (если есть)
        
        $receipts = \R::find('receipt', 'partner = ? AND date_pay IS NULL ORDER BY date', [$name]); // Получаем все неоплаченные приходы этого КА
        /***************** Получаем массив приходов в зависимости от режима
        Array (пример)
        (
            [0] => Array
                (
                    [number] => TOF00000278/2022 - номер неоплаченного прихода
                    [summa] => 37044.00          - сумма этого прихода
                

            [1] => Array
                (
                    [number] => TOF00000279/2022
                    [summa] => 20752.88
                )

            [2] => Array
                (
                    [number] => TOF00000280/2022
                    [summa] => 3998.74
                )   
        ) *****************************************************************/
        $receipt_select = []; // массив содержащий выбранные приходы в ЗО
        $receipt_nopay = [];  // массив содержащий неоплаченные приходы данного КА
        $ers_sel = []; $new_er = []; $new_sums = [];
        foreach ($receipts as $k => $v) {
            $receipt_nopay[$k]['number'] = dateYear($v->number, $v->date);
            $receipt_nopay[$k]['summa'] = $v->sum;
        }
        if (!$payments) {
            // Если ЗО нет (режим добавления)
            $receipt_select['0']['number'] = trim($receipt_num, '%');
            $receipt_select['0']['summa'] = $receipt->sum;            
        } else {              
            $er_sel = explode(';', $payments->num_er); // выбранные ер
            $er_sum = explode(';', $payments->sum_er); // суммы выбранных ер
            foreach ($er_sel as  $k => $v) {
                $new_er[$k]['number'] = $er_sel[$k];
                $new_er[$k]['summa'] = $er_sum[$k];
            }
            $ers_sel = $new_er;
            $recs = explode(';', $payments->receipt); // доступные приходы
            $sums = explode(';', $payments->sum); // все выбранные приходы
            foreach ($recs as  $k => $v) {
                $new_recs[$k]['number'] = $recs[$k];
                $new_recs[$k]['summa'] = $sums[$k];
            }
            $receipt_select = $new_recs;
            if ($pay_key) {
                // Если ЗО создана и уже оплачена (режим просмотра)
                $receipt_nopay = $new_recs;
            } else {
                // Если ЗО создана но пока не оплачена (режим редактирования)
                foreach ($receipts as $k => $v) {
                    $new_sums[$k]['number'] = dateYear($v->number, $v->date);
                    $new_sums[$k]['summa'] = $v->sum;
                }
                $receipt_nopay = $new_sums;  
            }
        }
        /***** Конец получения данных для формирования заявки на оплату ******/
        //debug($ers);die;
        if ($this->isAjax()) {
            // Если запрос пришел АЯКСом
            $this->loadView('payment_add_modal', compact('name', 'receipt_select', 'receipt_nopay', 'ers', 'ers_sel', 'payments', 'vat'));
        }
        redirect();
        //debug($ers);debug($receipt_select);debug($receipt_nopay);die;
    }

    public function payReceiptAction() {
        // получаем данные пришедшие методом POST
        $pay_receipt = !empty($_POST) ? $_POST : null;
        $num = '';                                      // обнуляем переменную
        $receipt = $pay_receipt['receipt'];             // получаем массив приходов
        foreach ($pay_receipt['receipt'] as &$value) {
            $num .= $value . ';';                       // добавляем значение прихода (TOF00000278/2022;TOF00000278/2022;)
        }
        $num = rtrim($num, ';');            // убираем конечный знак ; получаем (TOF00000278/2022;TOF00000278/2022)
        $pay_receipt['receipt'] = $num;     // записываем в сессию полученный результат
        $num = '';                                      // обнуляем переменную
        foreach ($pay_receipt['num_er'] as &$value) {
            $num .= $value . ';';                       // добавляем номера ЕР (0009/122021ТЛТ;0008/092021ТЛТ;)
        }
        $num = rtrim($num, ';');            // убираем конечный знак ; получаем (0009/122021ТЛТ;0008/092021ТЛТ)
        $pay_receipt['num_er'] = $num;      // записываем в сессию полученный результат
        $num = '';                                      // обнуляем переменную
        foreach ($pay_receipt['sum'] as &$value) {
            $num .= $value . ';';                       // добавляем номера ЕР (2000.00;2000.00;)
        }
        $num = rtrim($num, ';');            // убираем конечный знак ; получаем (2000.00;2000.00)
        $pay_receipt['sum'] = $num;      // записываем в сессию полученный результат
        if (empty($pay_receipt['date_pay'])) {
            $pay_receipt['date_pay'] = NULL;
             //echo 'заменил на NULL';
        }
        // внесение изменений в приход        
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
            $id_receipt[] = $receipt_full;
        }
        return $id_receipt;
    }

}
