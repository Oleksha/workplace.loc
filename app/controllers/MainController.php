<?php

namespace app\controllers;

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
                'date_pay' => $value->date_pay,
                'delay' => isset($delay) ? $delay : null
            ];
        }
      
      // формируем метатеги для страницы
      $this->setMeta('Главная страница', 'Содержит информацию о неоплаченных приходах', 'Ключевые слова');
      
      // Передаем полученные данные в вид
      $this->set(compact('receipt'));

    }

}