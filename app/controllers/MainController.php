<?php

namespace app\controllers;

use RedBeanPHP\R;
use workplace\Cache;
use workplace\App;
use workplace\libs\Pagination;

class MainController extends AppController {

    public function indexAction() {
      
      // получаем номер страницы
      $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
      // получаем количество приходов на одну страницу
      $perpage = App::$app->getProperty('pagination');
      // получаем количество приходов не оплаченных
      $total = \R::count('receipt', 'date_pay is NULL');
      $pagination = new Pagination($page, $perpage, $total);
      $start = $pagination->getStart();
      
      // получение списка не оплаченных приходов из БД
      $receipt = \R::find('receipt', "WHERE (date_pay is NULL) OR (date_pay = CURDATE()) ORDER BY partner LIMIT $start, $perpage");
        $receipt_all = \R::find('receipt', "WHERE (date_pay is NULL) OR (date_pay = CURDATE()) ORDER BY partner");
      /*
      array(
            [1] (
                    partner
                    receipt_num
                    receipt_date
                    receipt_sum
                    delay
                    pay_date
                    status
                )
      )
      */
        $rec = [];
      // Получаем дополнительную информацию
        foreach ($receipt_all as $value) {
            // Получаем номер оплаты если он есть
            $partner = \R::findOne('partner', 'name = ?', [$value->partner]);
            $delay = [];
            if ($value->num_pay) {
                // оплат может быть несколько
                $payments = explode(';', $value->num_pay);
                $er = [];
                foreach ($payments as $item) {
                    // проходимся по каждой оплате
                    $pay = explode('/', $item);
                    $pay_num = $pay[0];
                    $pay_year = $pay[1];
                    // получаем номера ЕР (их может быть несколько по каждой оплате)
                    $receipt = \R::find('payment', "WHERE (YEAR(date) = ?) AND (number = ?)", [$pay_year, $pay_num]);
                    if ($receipt) {
                        // если мы что-то получили пройдемся по всем элементам
                        foreach ($receipt as $item) {
                            $ers = explode(';', $item->num_er);
                            foreach ($ers as $item) {
                                $er[] = $item;
                            }
                        }
                    }
                }
                // получаем отсрочку платежа
                if (!empty($er)) {
                    foreach ($er as $item) {
                        $er_full = \R::findOne('er', 'number = ?', [$item]);
                        $delay[] = $er_full->otsrochka;
                    }
                }
            }
            $rec[$value->id] = [
                'partner' => $value->partner,
                'inn' => $partner->inn,
                'number' => $value->number,
                'date' => $value->date,
                'sum' => $value->sum,
                'num_pay' => $value->num_pay,
                'date_pay' => $value->date_pay,
                'delay' => isset($delay[0]) ? $delay[0] : null
            ];

        }

      
      // формируем метатеги для страницы
      $this->setMeta('Главная страница', 'Описание...', 'Ключевые слова...');
      
      // Передаем полученные данные в вид
      $this->set(compact('rec', 'pagination'));

    }

}