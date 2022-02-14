<?php

namespace workplace\base;

/**
 * Class View
 * @package workplace\base
 */
class View {

    public $route; // содержит массив всех данных о текущем маршруте
    public $controller;
    public $model;
    public $view;
    public $layout;
    public $prefix;
    public $data = [];  // данные которые будут передаваться из контроллера в вид
    public $meta = [];  // метаданные которые будут передаваться из контроллера в вид

    public function __construct($route, $layout = '', $view = '', $meta) {
        $this->route = $route;
        $this->controller = $route['controller'];
        $this->model = $route['controller'];
        $this->view = $view;
        $this->prefix = $route['prefix'];
        $this->meta = $meta;
        if ($layout === false) {
            // Если шаблон не нужен
            $this->layout = false;
        } else {
            // если передано значение шаблона берем его,
            // инначе берем шаблон по умолчанию из настроек
            $this->layout = $layout ?: LAYOUT;
        }
    }

    /**
     * @param $data
     * метод формирующий страницу на основании данных $data
     */
    public function render($data) {
        // проверяем переданные данные являются массивом
        if (is_array($data)) extract($data);
        // формируем путь к view
        $viewFile = APP . "/views/{$this->prefix}{$this->controller}/{$this->view}.php";
        // проверяем существует ли файл
        if (is_file($viewFile)) {
            // если файл найден подключаем его, но не выводим сразу, а используем буфферизацию
            ob_start();
            require_once $viewFile;
            // поместим все что загрузили в переменную $content и очистим буффер
            $content = ob_get_clean();
        } else {
            // если файла нет ошибка
            throw new \Exception("Файл вида $viewFile не найден", 500);
        }
        if (false !== $this->layout) {
            // если мы собираемя выводить шаблон
            // формируем путь к layout
            $layoutFile = APP . "/views/layouts/{$this->layout}.php";
            // проверяем существует ли файл
            if (is_file($layoutFile)) {
                // если файл найден подключаем его
                require_once $layoutFile;
            } else {
                // если файла нет ошибка
                throw new \Exception("Шаблона {$this->layout} не найден", 500);
            }
        }
    }

    /**
     * Метод формирующий метатеги
     * @return array
     */
    public function getMeta() {
        $output = '<title>' . $this->meta['title'] . '</title>' . PHP_EOL;
        $output .= '<meta name="description" content="' . $this->meta['description'] . '">' . PHP_EOL;
        $output .= '<meta name="keywords" content="' . $this->meta['keywords'] . '">' . PHP_EOL;
        return $output;
    }

}