<?php

namespace app\controllers;

class BudgetController extends AppController {

    public function indexAction() {
        $year = isset($_GET['y']) ? $_GET['y'] : date('Y');
        $month = isset($_GET['m']) ? $_GET['m'] : date('m');
        $budget_date = $year . '-' . $month . '-01';
        //debug($budget_date);die;
        // получение БО из БД
        $budgets = \R::find('budget', "WHERE scenario = '{$budget_date}' ORDER BY scenario, number");
        // формируем метатеги для страницы
        $this->setMeta('Список бюджетных операций', 'Описание...', 'Ключевые слова...');
        // Передаем полученные данные в вид
        $this->set(compact('budgets', 'year', 'month'));
    }

}