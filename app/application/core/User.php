<?php

namespace application\core;

use application\core\User;

class User {

    public $name = ''; // имя пользователя
    public $role = ''; // роль пользователя

    public function __construct()
    {
        // извлекаем данные из сессии
        if(isset($_SESSION['authorize']['user_name']) && isset($_SESSION['authorize']['role'])) {
            $this->name = strval($_SESSION['authorize']['user_name']);
            $this->role = strval($_SESSION['authorize']['role']);
        }
    }

    /**
     * Проверка состояния авторизации пользователя
     * return bool true/false - авторизован/нет
     *
     */
    public function isAuthorized()
    {
        if(isset($_SESSION['authorize'])) {
            return true;
        }

        return false;
    }
}
