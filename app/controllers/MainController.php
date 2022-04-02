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
        // формируем метатеги для страницы
        $this->setMeta('Главная страница', 'Содержит информацию о неоплаченных приходах', 'Ключевые слова');
        // Передаем полученные данные в вид
        $this->set(compact('receipt'));
    }

    /**
     * Функция обработки нажатия кнопки Ввод оплаты
     * @return void
     */
    public function payAction() {
        // получаем переданный идентификатор прихода
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        if ($this->isAjax()) {
            // Если запрос пришел АЯКСом
            $this->loadView('pay_add_date', compact('id'));
        }
        redirect();
    }

    /**
     * Функция занесения в БД данных об оплате прихода
     * @return void
     */
    public function payEnterAction() {
        // получаем данные пришедшие методом POST
        $data = !empty($_POST) ? $_POST : null;
        $id_receipt = $data['id'];
        // получаем приход в который необходимо внести дату оплаты
        $edit_receipt = \R::findOne('receipt', 'id = ?', [$id_receipt]);
        $edit_receipt['date_pay'] = $data['date']; // заносим оплату
        // записываем исправленные данные в БД
        $receipt = new Receipt();
        /** @var array $edit_receipt */
        $receipt->load($edit_receipt);
        $receipt->edit('receipt', $id_receipt);
        redirect();
    }

    /**
     * Функция получения данных об оплате конкретного прихода
     * @param $num_receipt mixed номер прихода в виде 0000000000/2022 или массив номеров
     * @return mixed
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
