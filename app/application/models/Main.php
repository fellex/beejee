<?php

namespace application\models;

use application\core\Model;

class Main extends Model {
    protected $errors = []; // список ошибок

    /**
     * Валидация формата Email
     * params string $prop_name - название поля
     * params string $prop_val - значение поля
     * результат работы будет записан в $this->errors
     *
     */
    protected function validationEmail($prop_name, $prop_val)
    {
        $matches = [];
        $pattern = '/([a-z0-9]+@[a-z0-9]+\.[a-z]+)/';
        preg_match($pattern, $prop_val, $matches);
        if(!isset($matches[1])) {
            if(isset($this->errors[$prop_name])) {
                $this->errors[$prop_name] .= " Неверный формат email.";
            } else {
                $this->errors[$prop_name] = "Неверный формат email.";
            }

        }
    }

    /**
     * Валидация пустого значения
     * params string $prop_name - название поля
     * params string $prop_val - значение поля
     * результат работы будет записан в $this->errors
     *
     */
    protected function validationEmpty($prop_name, $prop_val)
    {
        if(empty($prop_val)) {
            if(isset($this->errors[$prop_name])) {
                $this->errors[$prop_name] .= " Не может быть пустым.";
            } else {
                $this->errors[$prop_name] = "Не может быть пустым.";
            }
        }
    }

    /**
     * Возвращает ошибки валидации
     * return array $this->errors
     *
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
