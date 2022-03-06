<?php

namespace app\controllers;

use app\models\Receipt;

class MainController extends AppController {

    public function indexAction() {
        // получение списка не оплаченных приходов из БД
        $receipts = \R::find('receipt', "WHERE (date_pay is NULL) OR (date_pay = CURDATE()) ORDER BY partner");
        // Создаем пустой массив для хранения необходимых для вывода данных
        $receipt = [];
        // Получаем дополнительную информацию для каждого прихода
        foreach ($receipts as $value) {
            // Получаем номер информацию о КА
            $partner = \R::findOne('partner', 'name = ?', [$value->partner]);
            // Получаем отсрочку оплаты
            $delay = $partner->delay;
            // формирум массив для вывода
            
            $receipt[$value->id] = [
                'partner' => $value->partner,
                'inn' => $partner->inn,
                'number' => $value->number,
                'date' => $value->date,
                'sum' => $value->sum,
                'num_pay' => $value->num_pay,
                'date_pay' => $value->date_pay, // дата оплаты
                'pay_date' => $this->getDatePayment(dateYear($value->number, $value->date)), // дата планируемой оплаты
                'delay' => isset($delay) ? $delay : null,
                'id_receipt' => $value->id,
                'payment' => $value->payment,
            ];
        }
        //debug($receipt);die;
      // формируем метатеги для страницы
      $this->setMeta('Главная страница', 'Содержит информацию о неоплаченных приходах', 'Ключевые слова');
      
      // Передаем полученные данные в вид
      $this->set(compact('receipt'));

    }

    public function payAction() {
        // получаем переданный идентификатор прихода
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        unset($_SESSION['pay_date']); // Очищаем сессию
        $_SESSION['pay_date'] = [
            'id' => $id,
            'date' => null,
        ];
        /*debug($id); die;
        $receipt = null;
        if ($id) {
            // если у нас есть ID получаем все данные об этом приходе
            $receipt = \R::findOne('receipt', 'id = ?', [$id]);
            if (!$receipt) return false; // если такого прихода нет дальнейшие действия бессмысленны
        }
        $rec = new Receipt();
        $rec->editReceipt($receipt);*/

        if ($this->isAjax()) {
            // Если запрос пришел АЯКСом
            $this->loadView('pay_add_date');

        }
        redirect();
    }

    public function payEnterAction() {
        $data = !empty($_POST) ? $_POST : null;
        $id_receipt = $data['id'];
        $pay_date = $data['date'];
        // необходимо внести оплату в приход
        $edit_receipt = \R::findOne('receipt', 'id = ?', [$id_receipt]);
        //debug($edit_receipt);
        $receipt = new Receipt();
        $receipt->editReceipt($edit_receipt);
        $_SESSION['receipt']['date_pay'] = $pay_date;
        $receipt->load($_SESSION['receipt']);
        //debug($receipt->attributes);die;
        $receipt->edit('receipt', $id_receipt);

        // необходимо проверить совпадает ли оплата в ЗО с окончательной оплатой

        //
        redirect();
    }

    /**
     * Функция получения данных об оплате конкретного прихода
     * $num_receipt mix номер прихода в виде 0000000000/2022 или массив номеров
     */
    public function getDatePayment($num_receipt) {
        $date_payment = null;
        // получаем данные об оплатах данного прихода
        $receipts = \R::getAll("SELECT * FROM payment WHERE receipt LIKE ?", ['%'.$num_receipt.'%']);
        foreach ($receipts as $receipt) { 
            if (!is_null($date_payment)) {
                if ($receipt['date_pay'] > $date_payment) {
                    $date_payment = $receipt['date_pay'];
                }
            } else {
                $date_payment = $receipt['date_pay'];
            }
        }
        return $date_payment;
    }

}
