<?php

namespace app\controllers;

use app\models\Er;
use app\models\Partner;
use app\models\Payment;
use app\models\Receipt;
use Exception;

class PartnerController extends AppController {

    public function indexAction() {
        // создаем необходимые объекты связи с БД
        $partner_obj = new Partner(); // для КА
        $er_obj = new Er();           // для ЕР
        $receipt_obj = new Receipt(); // для приходов
        // получаем информацию о КА
        $partners = $partner_obj->getPartner('name');
        // Получение дополнительную информацию о КА
        $partners_ext = [];
        foreach ($partners as $partner) {
            // Получаем количество действующих ЕР
            $ers = $er_obj->getCurrentEr($partner['id']);
            $partner['er'] = $ers ? count($ers) : 0;
            $sum = 0;
            // Получаем сумму дебиторской задолженности
            $receipts = $receipt_obj->getReceiptNoPay($partner['name']);
            if ($receipts) {
                foreach ($receipts as $receipt) {
                    $sum += $receipt['sum'];
                }
            }
            $partner['sum'] = $sum;
            $partners_ext[] = $partner;
        }
        $partners = $partners_ext;
        // формируем метатеги для страницы
        $this->setMeta('Cписок активных контрагентов', 'Содержит список активных КА с дополнительной информацией о каждом', 'контрагент,дебиторка,задолженность,отсрочка,ер,единоличные,решения');
        // Передаем полученные данные в вид
        $this->set(compact('partners'));
    }

    /**
     * Если контрагента несуществует
     * @throws Exception
     */
    public function viewAction() {
        // создаем необходимые объекты связи с БД
        $partner_obj = new Partner(); // для КА
        $er_obj = new Er();           // для ЕР
        $receipt_obj = new Receipt(); // для приходов
        // получение ИНН запрашиваемого контрагента
        $inn = $this->route['inn'];
        // получение данных по КА из БД
        $partner = $partner_obj->getPartnerByINN($inn);
        if (!$partner) {
            // Если такого контрагента нет выбрасываем исключение
            throw new Exception('Контрагент с ИНН ' . $inn . ' не найден', 500);
        }
        // ЕДИНОЛИЧНЫЕ РЕШЕНИЯ
        // получение данных по ЕР для КА из БД
        $ers = $er_obj->getCurrentEr($partner['id']);
        // добавляем в массив данные по расходам этого ЕР
        if ($ers) $ers = $this->costs($ers, $partner['vat'], $partner['name']);
        // ПРИХОДЫ
        $receipt = $receipt_obj->getReceipt('partner', $partner['name']);
        // формируем метатеги для страницы
        $this->setMeta($partner['name'], 'Описание...', 'Ключевые слова...');
        // Передаем полученные данные в вид
        $this->set(compact('partner', 'ers', 'receipt'));
    }

    /**
     * @param $ers array список ЕР
     * @param $vat double ставка НДС
     * @return array список ЕР с остатками средств
     */
    public function costs($ers, $vat, $name) {
        $pay_obj = new Payment();
        foreach ($ers as $k => $er) {
            // получаем все оплаты использующие эту ЕР
            $payments = $pay_obj->getPaymentEr($name, $er['number']);
            // проходимся по каждому приходу чтобы получить суммы расхода по данной ЕР
            /* создаем массив в виде
                [0] [
                    number: 00000000,
                    summa: 123.12
                */
            $sum = 0.00;
            if ($payments) {
                foreach ($payments as $payment) {
                    // каждая оплата может использовать несколько ЕР
                    $nums = explode(';', $payment['num_er']);
                    $sums = explode(';', $payment['sum_er']);
                    $key = array_search($er['number'], $nums);
                    $sum += $sums[$key];
                }
                $sum = round($sum / $vat, 2);
            }
            $ers[$k]['cost'] = $sum;
        }
        return $ers;
    }

    public function addAction() {
        unset($_SESSION['form_data']); // Очищаем сессию данных о КА
        // если существуют переданные данные методом POST
        if (!empty($_POST)) {
            // создаем объект класса-модели User
            $user = new Partner();
            // запоминаем переданные данные
            $data = $_POST;
            // загружаем переданные данные
            $user->load($data);
            // проверяем заполненные данные
            if (!$user->checkUnique()) {
                // если проверка не пройдена запишем ошибки в сессию
                $user->getErrors();
                // запоминаем уже введенные данные
                $_SESSION['form_data'] = $data;
            } else {
                // если проверка пройдена записываем данные в таблицу
                if ($id = $user->save('partner')) {
                    // если все прошло хорошо в ID номер зарегистрированного пользователя
                    //$_SESSION['success'] = 'Контрагент сохранен в БД';
                    unset($_SESSION['form_data']);
                    // перезагрузим страницу
                    redirect("/partner/" . $data['inn']);
                } else {
                    $_SESSION['errors'] = 'Возникли ошибки при сохранении данных в БД';
                }
            }

        }
        // устанавливаем метаданные
        $this->setMeta('Добавление нового контрагента');

    }

    public function editAction() {
        // получаем переданный идентификатор КА
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        if ($id) {
            // если у нас есть ID получаем все данные об этом KA
            $partner = \R::findOne('partner', 'id = ?', [$id]);
            if (!$partner) return false; // если RF не найден дальнейшие действия бессмысленны
            // запоминаем полученные данные
            $_SESSION['form_data'] = $partner;
        }
        if ($this->isAjax()) {
            // Если запрос пришел АЯКСом
            $this->loadView('ka_edit_modal');

        }
        redirect();
    }

    public function editKaAction() {
        // получаем данные пришедшие методом POST
        $edit_ka = !empty($_POST) ? $_POST : null;
        //debug($edit_ka);
        $partner = new Partner();
        $partner->load($edit_ka);
        $partner->edit('partner', $edit_ka['id_ka']);
        unset($_SESSION['form_data']);
        redirect();
    }

}
