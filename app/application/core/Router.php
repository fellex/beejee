<?php

namespace application\core;

use application\core\View;

class Router {

    protected $routes = []; // роуты
    protected $params = []; // параметры
    
    public function __construct()
    {
        // подтягиваем все роуты
        $arr = require __DIR__ . '/../config/routes.php';
        foreach ($arr as $key => $val) {
            $this->add($key, $val);
        }
    }

    /**
     * Наполняет массив роутов
     *
     */
    public function add($route, $params)
    {
        $route = '#^' . $route . '$#';
        $this->routes[$route] = $params;
    }

    /**
     * Проверка наличия роута по пути запроса
     * return bool true/false - роутер найден/нет
     *
     */
    public function match()
    {
        $root_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']);
        $root_path = str_replace('index.php', '', $root_path);

        if(strpos($_SERVER['REQUEST_URI'], $root_path) === 0) {
            $url = trim(substr($_SERVER['REQUEST_URI'], strlen($root_path)), '/');
            $parsed_url = parse_url($url);
            if(!isset($parsed_url['path'])) {
                $parsed_url['path'] = '';
            }
            $path = trim($parsed_url['path'], '/');

            foreach($this->routes as $route => $params) {
                if(preg_match($route, $path, $matches)) {
                    $this->params = $params;
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Основной метод
     *
     */
    public function run()
    {
        if($this->match()) { // ищем роут
            $path = 'application\controllers\\'.ucfirst($this->params['controller']).'Controller'; // путь к контроллеру
            if(class_exists($path)) { // контроллер найден
                $action = $this->params['action'].'Action';
                if(method_exists($path, $action)) { // экшн найден
                    $controller = new $path($this->params);
                    $controller->$action();
                } else {
                    $error_route = [
                        "action" => "error",
                        "controller" => "errors",
                    ];
                    $error_view = new View($error_route);
                    $error_view->render("Ошибка 404", ['header_text' => 'Ошибка 404 страница не найдена']);exit;
                }
            } else {
                $error_route = [
                    "action" => "error",
                    "controller" => "errors",
                ];
                $error_view = new View($error_route);
                $error_view->render("Ошибка 404", ['header_text' => 'Ошибка 404 страница не найдена']);exit;
            }
        } else {
            $error_route = [
                "action" => "error",
                "controller" => "errors",
            ];
            $error_view = new View($error_route);
            $error_view->render("Ошибка 404", ['header_text' => 'Ошибка 404 страница не найдена']);exit;
        }
    }
}
