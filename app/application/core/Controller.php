<?php

namespace application\core;

use application\core\View;
use application\components\Components;

abstract class Controller {

	public $route;
	public $view;
	public $acl;
    public $components;
    public $user;
    public $config;

	public function __construct($route) {
		$this->route = $route;
        $this->user = new User();
		if (!$this->checkAcl()) { // не прошел проверку пользователя на доступ к этой странице
            // выясняем AJAX-запрос или нет
            $is_ajax = false;
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $is_ajax = true;
            }

            if($this->user->isAuthorized()) { // пользователь авторизован, значит сообщим, что сюда доступ закрыт
                $error_route = [
                    "action" => "error",
                    "controller" => "errors",
                ];
                $error_view = new View($error_route);
                $error_view->render("Ошибка 403", ['header_text' => 'Ошибка 403 доступ запрещен']);exit;
            } else { // не авторизован
        	    // запишем реферер
        	    $_SESSION['referer'] = $_SERVER['REQUEST_URI'];

                $login_route = [
                    "action" => "login",
                    "controller" => "account",
                ];
                $login_view = new View($login_route);
                // отправляем на авторизацию
                if($is_ajax) {
                    $return = [
                        'status' => 'redirect',
                        'url' => $login_view->getRedirectUrl(),
                    ];
                    die(json_encode($return));
                } else {
                    $login_view->redirect();
                }
            }
        }

        // почистим GET POST от XSS уязвимости
        $this->prepareData($_POST);
        $this->prepareData($_GET);

        $this->view = new View($route); // подтягиваем вьюху
		$this->model = $this->loadModel($route['controller']); // подтягиваем модель
        $this->config = require __DIR__ . '/../config/config.php'; // подтягиваем конфиг
	}

	public function loadModel($name) {
		$path = 'application\models\\'.ucfirst($name);
		if (class_exists($path)) {
			return new $path;
		}
	}

    /**
     * Проверка на доступ пользователя к запрашиваемой странице
     *
     */
	public function checkAcl() {
		$this->acl = require __DIR__ . '/../acl/'.$this->route['controller'].'.php';
		if ($this->isAcl('all')) { // доступно всем
			return true;
		}
		elseif (isset($_SESSION['authorize']['user_name']) and $this->isAcl('authorize')) { // доступно авторизованным
			return true;
		}
		elseif (!isset($_SESSION['authorize']['user_name']) and $this->isAcl('guest')) { // доступно гостю
			return true;
		}
		elseif (isset($_SESSION['authorize']['user_name']) and $_SESSION['authorize']['user_name'] == 'admin' and $this->isAcl('admin')) { // доступно админу
			return true;
		}
		return false; // не доступно
	}

	public function isAcl($key) {
		return in_array($this->route['action'], $this->acl[$key]);
	}

    /**
     * Проверка массива на XSS уязвимости
     * params array &$arr
     *
     */
    private function prepareData(&$arr) {
        foreach($arr as $k => $v) {
            $arr[$k] = $this->clearCodeXSS($v);
        }
    }

    /**
     * Проверка на XSS уязвимости
     * params string $str
     * return string $str - очищенная строка
     *
     */
    private function clearCodeXSS($str) {
        $str = strip_tags($str);
        $str = htmlentities($str, ENT_QUOTES, "UTF-8");
        return $str;
    }
}
