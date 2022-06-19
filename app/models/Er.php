<?php

namespace app\models;

use R;

class Er extends AppModel {

    // поля таблицы для заполнения
    public $attributes = [
        'id_partner' => '',
        'id_budget_item' => '',
        'number' => '',
        'data_start' => '',
        'data_end' => '',
        'otsrochka' => '',
        'summa' => '',
    ];

    public function editEr($er, $partner, $budget_items) {
        unset($_SESSION['er']); // Очищаем сессию
        if (!isset($_SESSION['er'][$er->id])) {
            // если у нас в сессии уже находится ЕР с таким ID
            $_SESSION['er'][$er->id] = [
                'id_er' => $er->id,
                'id_partner' => $er->id_partner,
                'name_partner' => $partner->name,
                'id_budget_item' => $er->id_budget_item,
                'budget_items' => $budget_items,
                'number' => $er->number,
                'data_start' => $er->data_start,
                'data_end' => $er->data_end,
                'otsrochka' => $er->otsrochka,
                'summa' => $er->summa,
            ];
        }
    }

    public function addEr($id, $partner, $budget_items) {
        unset($_SESSION['er']); // Очищаем сессию
        if (!isset($_SESSION['er'][$id])) {
            // если у нас в сессии уже находится ЕР с таким ID
            $_SESSION['er'][$id] = [
                'id_er' => null,
                'id_partner' => $id,
                'name_partner' => $partner['name'],
                'id_budget_item' => null,
                'budget_items' => $budget_items,
                'number' => null,
                'data_start' => null,
                'data_end' => null,
                'otsrochka' => null,
                'summa' => null,
            ];
        }
    }

    /**
     * @param $er string номер Единоличного решения
     * @param $partner string Наименование контрагента
     * @return array массив заявок на опалату
     */
    public function getPayment($er, $partner = null) {
        $er = '%' . $er . '%';
        if ($partner) {
            return R::find('payment', 'num_er LIKE ? AND partner = ? ORDER BY date', [$er, $partner]);
        } else {
            return R::find('payment', 'num_er LIKE ? ORDER BY date', [$er]);
        }
    }

    /**
     * Возвращает все действующие на сегодня ЕР
     * @param $partner_id integer идентификатор КА
     * @return array|false
     */
    public function getCurrentEr($partner_id) {
        $ers = R::getAll('SELECT er.*, budget_items.name_budget_item FROM er, budget_items WHERE (budget_items.id = er.id_budget_item) AND (data_end >= CURDATE()) AND id_partner = ?', [$partner_id]);
        if (!empty($ers)) return $ers;
        return false;
    }
    /**
     * Возвращает все действующие на указанную дату ЕР
     * @param $partner_id integer идентификатор КА
     * @param $date string строковое представление даты
     * @return array|false
     */
    public function getCurrentErFromDate($partner_id, $date) {
        $ers = R::getAll("SELECT er.*, budget_items.name_budget_item FROM er, budget_items WHERE (budget_items.id = er.id_budget_item) AND (data_start <= '{$date}') AND (data_end >= '{$date}') AND id_partner = ?", [$partner_id]);
        if (!empty($ers)) return $ers;
        return false;
    }

    /**
     * Возвращает остоток денежных средств на ЕР
     * @param $num_er string номер ЕР
     * @return float
     */
    public function getBalance(string $num_er): float {
        $pay_obj = new Payment();       // объект ЗО
        $er = $this->getEr($num_er);    // получаем информацию по ЕР
        $summa_coast = 0.00;            // расходы по ЕР
        // получаем все оплаты использующие эту ЕР
        $payments = $pay_obj->getPayment($num_er);
        // Если таковые есть проходимся по всему массиву
        if ($payments) {
            foreach ($payments as $payment) {
                $vat = $payment['vat']; // НДС текущей ЗО
                $nums = explode(';', $payment['num_er']); // массив всех ЕР в ЗО
                $sums = explode(';', $payment['sum_er']); // массив всех сумм ЕР в ЗО
                $key = array_search($er['number'], $nums);  // индекс текущей ЕР в массиве ЕР
                $sum = $sums[$key];                         // сумма текущей ЕР
                // добавляем сумму ЗО в расходы по ЕР без НДС
                $summa_coast += round($sum / $vat, 2);
            }
        }
        return $er['summa'] - $summa_coast;
    }

    /**
     * Возвращает массив содержащий номера оплат и суммы расхода по ЕР
     * @param $num_er string номер ЕР
     * @return array
     */
    public function getPaymentCoast(string $num_er): array {
        $pay_obj = new Payment();       // объект ЗО
        $er = $this->getEr($num_er);    // получаем информацию по ЕР
        $pay = [];                      // массив содержащий возвращаемые данные
        // получаем все оплаты использующие эту ЕР
        $payments = $pay_obj->getPayment($num_er);
        // Если оплаты есть проходимся по всему массиву
        if ($payments) {
            foreach ($payments as $payment) {
                $vat = $payment['vat']; // НДС текущей ЗО
                $nums = explode(';', $payment['num_er']); // массив всех ЕР в ЗО
                $sums = explode(';', $payment['sum_er']); // массив всех сумм ЕР в ЗО
                $key = array_search($er['number'], $nums);  // индекс текущей ЕР в массиве ЕР
                $sum = $sums[$key];                         // сумма текущей ЕР
                // запоминаем внутренний номер ЗО в формате TOF0000000000/2022
                $pay_er['number'] = $payment['number'] . '/' . substr($payment['date'], 0, 4);
                // запоминаем сумму ЕР без НДС
                $pay_er['summa'] = round($sum / $vat, 2);
                $pay[] = $pay_er; // добавляем полученные данные в массив оплат
            }
        }
        return $pay;
    }

    /**
     * Возвращает информацию по ЕР
     * @param $num_er string номер ER
     * @return array
     */
    public function getEr(string $num_er): array {
        $er_arr = [];
        $er = R::getAssocRow('SELECT er.*, budget_items.name_budget_item FROM er, budget_items WHERE (budget_items.id = er.id_budget_item) AND number = ?', [$num_er]);
        if ($er) $er_arr = $er[0];
        return $er_arr;
    }

}
