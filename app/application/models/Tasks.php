<?php

namespace application\models;

use application\core\Model;

class Tasks extends Main {
    private $id;
    public $user_name;
    public $user_email;
    public $task_text;
    public $status;
    public $changed_by_user;

    // массив возможных статусов
    const statuses = [
        1 => 'Новая',
        2 => 'Выполнена',
    ];

    public function __construct(int $id = null)
    {
        parent::__construct();
        if(!is_null($id)) { // пытаемся найти задачу в БД по id
            $sql = "SELECT * FROM `tasks` WHERE `id` = :id;";
            if($row = $this->db->row($sql, ['id' => $id])) { // нашлась
                foreach($row as $k => $v) {
                    $this->$k = $v;
                }
            }
        }
    }

    public function __get(string $name)
    {
        if(isset($this->$name)) {
            return $this->$name;
        }
    }

    /**
     * Выдает список всех задач согласно параметров
     * params array $params - массив входящих параметров
     * return array [] - массив найденных в БД задач
     *
     */
    public function getTasksList($params = [])
    {
        // определим сортировку
        $order = '';
        if(isset($params['orders']) && is_array($params['orders'])) {
            $flag_first = true;
            foreach($params['orders'] as $col => $sort_order) {
                if(is_null($sort_order)) continue;
                if(!$flag_first) {
                    $order .= ",";
                } else {
                    $order .= "ORDER BY";
                }
                $order .= ' `' . $col . '` ' . strtoupper($sort_order);
                $flag_first = false;
            }
        }

        // определяем лимит выборки
        $limit = '';
        if(isset($params['limit'])) {
            $limit = 'LIMIT ' . $params['limit'];
        }

        // определяем смещение
        $offset = '';
        if(isset($params['offset'])) {
            $offset = 'OFFSET ' . $params['offset'];
        }

        $sql = "SELECT * FROM `tasks` {$order} {$limit} {$offset};";
        return $this->db->all($sql);
    }

    /**
     * Подсчитываем количество задач в БД
     * return int $return - количество всех задач
     *
     */
    public function getCountTasks()
    {
        $sql = 'SELECT COUNT(*) AS `cnt` FROM `tasks`;';
        $res = $this->db->row($sql);
        return intval($res['cnt']);
    }

    /**
     * Создает/обновляет в БД задачу
     * return bool true/false - успех/неудача
     *
     */
    public function save()
    {
        if($this->validate()) { // валидируем свойства объекта перед сохранением
            if(is_null($this->id)) { // если id отсуствует, значит новая запись
                $this->insert();
            } else { // иначе обновляем запись
                $this->update();
            }
            return true;
        } else { // не прошли валидацию
            return false;
        }
    }

    /**
     * Валилирует свойства объекта перед сохранением в БД
     * return bool true/false - успех/неудача
     *
     */
    private function validate()
    {
        $properties = get_object_vars($this);
        foreach($properties as $prop_name => $prop_val) {
            switch($prop_name) {
                case 'user_name':
                case 'task_text':
                case 'status':
                    $this->validationEmpty($prop_name, $prop_val);
                    break;
                case 'user_email':
                    $this->validationEmpty($prop_name, $prop_val);
                    $this->validationEmail($prop_name, $prop_val);
                    break;
            }
        }

        return empty($this->errors);
    }

    /**
     * Создает новую запись в БД и выставляем свойство id объекта
     *
     */
    private function insert()
    {
        $sql = "INSERT INTO `tasks` (`user_name`, `user_email`, `task_text`, `status`) VALUES (:user_name, :user_email, :task_text, :status);";
        $params = [
            'user_name' => $this->user_name,
            'user_email' => $this->user_email,
            'task_text' => $this->task_text,
            'status' => $this->status,
        ];

        $this->db->doQuery($sql, $params);

        // вытаскиваем id созданной записи
        $sql = "SELECT `id` FROM `tasks` WHERE `user_name` = :user_name AND `user_email` = :user_email AND `task_text` = :task_text AND `status` = :status ORDER BY `id` DESC LIMIT 1;";
        $params = [
            'user_name' => $this->user_name,
            'user_email' => $this->user_email,
            'task_text' => $this->task_text,
            'status' => $this->status,
        ];

        $row = $this->db->row($sql, $params);
        $this->id = $row['id'];
    }

    /**
     * Обновляет запись в БД
     *
     */
    private function update()
    {
        $sql = "UPDATE `tasks` SET `user_name` = :user_name, `user_email` = :user_email, `task_text` = :task_text, `status` = :status, `changed_by_user` = :changed_by_user WHERE `id` = :id;";
        $params = [
            'id' => $this->id,
            'user_name' => $this->user_name,
            'user_email' => $this->user_email,
            'task_text' => $this->task_text,
            'status' => $this->status,
            'changed_by_user' => $this->changed_by_user,
        ];

        $this->db->doQuery($sql, $params);
    }

    /**
     * Возвращает список статусов
     *
     */
    static function getStatusList()
    {
        return self::statuses;
    }
}
