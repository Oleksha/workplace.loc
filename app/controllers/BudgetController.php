namespace app\controllers;

use workplace\App;

class BudgetController extends AppController {

    public function indexAction() {
       // получение БО из БД
        $budgets = \R::find('budget', "ORDER BY scenario, number");
        // формируем метатеги для страницы
        $this->setMeta('Список бюджетных операций', 'Описание...', 'Ключевые слова...');
        // Передаем полученные данные в вид
        $this->set(compact('budgets'));
    }

}
