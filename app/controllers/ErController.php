<?php

namespace app\controllers;

use app\models\Er;
use RedBeanPHP\R;

class ErController extends AppController {

    public $id = null, // ID едииноличного решения
           $id_partner = null, // ID контрагента
           $er = null, // массив данных об ЕР
           $partner = null, // массив данных о контрагенте
           $budget = null; // массив данные о статьях расхода

    public function editAction() {
        // получаем переданный идентификатор ЕР
        $this->id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        $this->id_partner = !empty($_GET['partner']) ? (int)$_GET['partner'] : null;
        if ($this->id) {
            // если у нас есть ID получаем все данные об этом ЕР
            $this->er = \R::findOne('er', 'id = ?', [$this->id]);
            if (!$this->er) return false; // если такой нет дальнейшие действия бессмысленны
            // если идентификаторы КА в ЕР и переданный не совпадают прекращаем работу
            if ((int)$this->er['id_partner'] !== $this->id_partner) return false;
            // получаем данные о контрагенте
            $this->partner = \R::findOne('partner', 'id = ?', [$this->id_partner]);
            // получаем данные о всех статьях расходов для поля со списком
            $this->budget = \R::getAll("SELECT * FROM budget_items ORDER BY name_budget_item");
        }
        $er = new Er();
        $er->editEr($this->er, $this->partner, $this->budget);
        if ($this->isAjax()) {
            // Если запрос пришел АЯКСом
            $this->loadView('er_edit_modal');
        }
        redirect();
    }

    public function editErAction() {
        // получаем данные пришедшие методом POST
        $edit_er = !empty($_POST) ? $_POST : null;
        $er = new Er();
        $er->load($edit_er);
        $er->edit('er', $edit_er['id_er']);
        redirect();
    }

    public function addAction() {
        // получаем данные пришедшие методом POST
        // получаем переданный идентификатор КА
        $this->id_partner = !empty($_GET['partner']) ? (int)$_GET['partner'] : null;
        // получаем данные о контрагенте
        $this->partner = \R::findOne('partner', 'id = ?', [$this->id_partner]);
        $this->budget = \R::getAll('SELECT * FROM budget_items ORDER BY name_budget_item');
        $er = new Er();
        $er->addEr($this->id_partner, $this->partner, $this->budget);
        if ($this->isAjax()) {
            // Если запрос пришел АЯКСом
            $this->loadView('er_add_modal');
        }
        redirect();
    }

    public function addErAction() {
        // получаем данные пришедшие методом POST
        $add_er = !empty($_POST) ? $_POST : null;
        $er = new Er();
        $er->load($add_er);
        $er->save('er');
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