<?php

namespace app\models;

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
            return \R::find('payment', 'num_er LIKE ? AND partner = ? ORDER BY date', [$er, $partner]);
        } else {
            return \R::find('payment', 'num_er LIKE ? ORDER BY date', [$er]);
        }
    }

    /**
     * Возвращает все действующие на сегодня ЕР
     * @param $partner_id integer идентификатор КА
     * @return array|false
     */
    public function getCurrentEr($partner_id) {
        $ers = \R::getAll('SELECT er.*, budget_items.name_budget_item FROM er, budget_items WHERE (budget_items.id = er.id_budget_item) AND (data_end >= CURDATE()) AND id_partner = ?', [$partner_id]);
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
        $ers = \R::getAll("SELECT er.*, budget_items.name_budget_item FROM er, budget_items WHERE (budget_items.id = er.id_budget_item) AND (data_start <= '{$date}') AND (data_end >= '{$date}') AND id_partner = ?", [$partner_id]);
        if (!empty($ers)) return $ers;
        return false;
    }

}
