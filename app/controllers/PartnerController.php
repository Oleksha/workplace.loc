<?php

namespace app\controllers;

use app\models\Er;
use app\models\Partner;
use workplace\App;
use workplace\libs\Pagination;

class PartnerController extends AppController {



    public function indexAction() {
        // получаем номер страницы
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        // получаем количество КА на одну страницу
        $perpage = App::$app->getProperty('pagination');
        // получаем количество КА
        $total = \R::count('partner');
        $pagination = new Pagination($page, $perpage, $total);
        $start = $pagination->getStart();

        // получение списка КА из БД
        $partners = \R::find('partner', "ORDER BY name LIMIT $start, $perpage");
        $partners_all = \R::find('partner', "ORDER BY name");
        // формируем метатеги для страницы
        $this->setMeta('Cписок активных контрагентов', 'Описание...', 'Ключевые слова...');
        // Получение количества действующих ЕР
        foreach ($partners as $partner) {
            $ers = \R::getAll('SELECT * FROM er WHERE data_end >= CURDATE() AND id_partner = ?', [$partner['id']]);
            $partner['er'] = count($ers);
        }
        foreach ($partners_all as $partner) {
            $ers = \R::getAll('SELECT * FROM er WHERE data_end >= CURDATE() AND id_partner = ?', [$partner['id']]);
            $partner['er'] = count($ers);
        }
        // Передаем полученные данные в вид
        $this->set(compact('partners', 'pagination', 'partners_all'));
    }

    public function viewAction() {
        // получение алиаса запрашиваемого контрагента
        $inn = $this->route['inn'];
        // получение данных по КА из БД
        $partner = \R::findOne('partner', 'inn = ?', [$inn]);
        if (!$partner) {
            // Если такого контрагента нет выбрасываем исключение
            throw new \Exception('Контрагент с кодом ' . $inn . 'не найден', 500);
        }

        // хлебные крошки

        // единоличные решения
        // Получаем id контрагента
        $id = $partner['id'];
        // получение данных по ЕР для КА из БД
        // $ers = \R::getAll('SELECT er.*, budget_items.* FROM er, budget_items WHERE er.id_budget_item = budget_items.id AND er.data_end >= CURDATE() AND er.id_partner = ?', [$id]);
        $ers = \R::getAll('SELECT er.*, budget_items.name_budget_item FROM er, budget_items WHERE (budget_items.id = er.id_budget_item) AND (data_end >= CURDATE()) AND id_partner = ?', [$id]);

        // Приходы
        $name = $partner['name'];
        $receipt = \R::getAll('SELECT * FROM receipt WHERE partner = ?', [$name]);

        // формируем метатеги для страницы
        $this->setMeta($partner->name, 'Описание...', 'Ключевые слова...');
        // Передаем полученные данные в вид
        $this->set(compact('partner', 'ers', 'receipt'));
    }

    public function addAction() {
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
        redirect();
    }

}