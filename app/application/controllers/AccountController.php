<?php

namespace application\controllers;

use application\core\Controller,
    application\core\View,
    application\models\Users;

class AccountController extends Controller {

    /**
     * Авторизация
     *
     */
	public function loginAction()
    {
        $errors = [];
        if($_POST) {
            $login = $_POST['login'];
            $password = $_POST['password'];
            $user = new Users();
            if($user_data = $user->findUser($login, $password)) { // авторизация пройдена
                $_SESSION['authorize']['user_name'] = $user_data['login'];
                $_SESSION['authorize']['role'] = $user_data['role'];
                if(isset($_SESSION['referer']) && !empty($_SESSION['referer'])) { // редирект по рефереру
                    $redirect_url = $_SESSION['referer'];
                    unset($_SESSION['referer']);
                } else { // редирект на стартовую страницу
                    $redirect_url = $this->view->app_root_path;
                }
                $this->view->redirect($redirect_url);
                die();
            } else {
                $errors = $user->getErrors();
                $errors['fail'] = 'Неверный логин/пароль';
            }
        }
        // рендер
		$this->view->render('Вход', ['errors' => $errors]);
	}

    /**
     * Логаут
     *
     */
	public function logoutAction() {
        unset($_SESSION['authorize']);
        
		$login_route = [
            "action" => "login",
            "controller" => "account",
        ];
        $login_view = new View($login_route);
        $login_view->redirect(); // редиректим на страницу Авторизации
        die();
	}
}