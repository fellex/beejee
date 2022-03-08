<?php

namespace application\models;

use application\core\Model;

class Users extends Main {

    /**
     * Ищет пользователя в БД по логину и паролю
     * params string $login - логин
     * params string $password - пароль
     * return array ['login', 'role'] - в случае успеха
     * return bool false - в случае провала
     *
     */
    public function findUser($login, $password)
    {
        $this->validationEmpty('login', $login);
        $this->validationEmpty('password', $password);
        if(!empty($this->errors)) {
            return false;
        }
        $sql = 'SELECT `login`, `role` FROM `users` WHERE `login` = :login AND `password` = :password;';
        $params = [
            'login' => $login,
            'password' => md5($password),
        ];
        $row = $this->db->row($sql, $params);
        return $row;
    }
}
