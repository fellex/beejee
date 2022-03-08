<?php

namespace application\lib;

use PDO;

class Db {

	protected $db;
	
	public function __construct() {
		$config = require __DIR__ . '/../config/config.php';
        if(empty($config['mysql']['host'])) {
            die("<b>" . __METHOD__ . "() error:</b> empty host name in config->mysql");
        }
        if(empty($config['mysql']['dbname'])) {
            die("<b>" . __METHOD__ . "() error:</b> empty database name in config->mysql");
        }
        if(empty($config['mysql']['user'])) {
            die("<b>" . __METHOD__ . "() error:</b> empty user name in config->mysql");
        }
        /*if(empty($config['password'])) {
            die("<b>" . __METHOD__ . "() error:</b> empty password in config->mysql");
        }*/

        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_COMPRESS => true,
            ];
            $this->db = new PDO("mysql:charset=utf8;host=" . $config['mysql']['host'] . ";dbname=" . $config['mysql']['dbname'], $config['mysql']['user'], $config['mysql']['password'], $options);
            $this->checkDB($config['mysql']['dbname']);
        } catch(PDOException $e) {
            die("<b>" . __METHOD__ . "() connection failed:</b>" . $e->getMessage());
        }
	}

    /**
     * Проверяет наличие нужных таблиц, при отсутствии создает их
     *
     */
    private function checkDB($dbname)
    {
        $sql = "SELECT * FROM `information_schema`.`tables` WHERE `table_schema` = :table_schema AND `table_name` = :table_name;";
        $params = [
            'table_schema' => $dbname,
            'table_name' => 'users',
        ];
        $stm = $this->doQuery($sql, $params);

        if($stm->rowCount() == 0) {
            // создаем таблицу
            $sql = "CREATE TABLE `users` (
                    	`id` INT(11) NOT NULL AUTO_INCREMENT,
                    	`login` VARCHAR(50) NOT NULL,
                    	`password` VARCHAR(32) NOT NULL,
                    	`role` VARCHAR(10) NOT NULL,
                    	PRIMARY KEY (`id`)
                    )
                    COLLATE='utf8_general_ci'
                    ENGINE=InnoDB;";
            $this->doQuery($sql);
            // добавляем данные
            $sql = "INSERT INTO `users` (`id`, `login`, `password`, `role`) VALUES (1, 'admin', '202cb962ac59075b964b07152d234b70', 'admin');";
            $this->doQuery($sql);
        }

        $sql = "SELECT * FROM `information_schema`.`tables` WHERE `table_name` = 'tasks';";
        $stm = $this->doQuery($sql);

        if($stm->rowCount() == 0) {
            // создаем таблицу
            $sql = "CREATE TABLE `tasks` (
                    	`id` INT(11) NOT NULL AUTO_INCREMENT,
                    	`user_name` VARCHAR(50) NOT NULL,
                    	`user_email` VARCHAR(50) NOT NULL,
                    	`task_text` TEXT NOT NULL,
                    	`status` TINYINT(4) NOT NULL,
                    	`changed_by_user` VARCHAR(50) NULL DEFAULT NULL,
                    	PRIMARY KEY (`id`)
                    )
                    COLLATE='utf8_general_ci'
                    ENGINE=InnoDB;";
            $this->doQuery($sql);

            // добавляем данные
            $sql = "INSERT INTO `tasks` (`id`, `user_name`, `user_email`, `task_text`, `status`, `changed_by_user`) VALUES
            	(1, 'Имя пользователя', 'user_email@mail.ru', 'Текст задачи описывающий задачу', 1, NULL),
            	(2, 'Имя пользователя 2', 'email_user@yandex.ru', 'Какой-то текст какой-то задачи', 1, NULL),
            	(3, 'Иванов Иван Иванович', 'fff@f.ru', 'И снова описание какой-то задачи для выполнения каких-либо полезных действий', 1, NULL),
            	(4, 'Петров Петр', 'pppp@pp.ru', 'Задача для Петрова. Пусть не скучает! :)', 1, NULL),
            	(5, 'Сидоров Сидор', 'sss@ss.ru', 'Задачка с подвохом. Сделает ли?', 1, NULL),
            	(6, 'Семен Семенович', 'sdf@sdf.ru', 'А вот и поспела 6-ая задачка', 1, NULL),
            	(7, 'Хитрый Лис', 'hhls@fsd.ru', 'Заголовок 1alert(&#039;text alert&#039;);', 1, 'admin');";
            $this->doQuery($sql);
        }
    }

    /**
     * Подготавливает и выполняет SQL-запрос
     * params string $sql - SQL-запрос
     * $params array $params - параметры SQL-запроса
     *
     */
    public function doQuery($sql, $params = array())
    {
        $e = null;
        if(empty($sql)) {
            die('<b>' . __METHOD__ . '() error sql execution:</b> empty $sql query');
        }
        if(!is_array($params)) {
            die('<b>' . __METHOD__ . '() error sql execution:</b> type of $params must be array');
        }

        $stm = $this->db->prepare($sql);
        try {
            foreach($params as $k => $v) {
                if(is_int($v)) {
                    $stm->bindValue($k, $v, PDO::PARAM_INT);
                } else {
                    $stm->bindValue($k, $v, PDO::PARAM_STR);
                }
            }
            $res = $stm->execute();

            if(!$stm instanceof \PDOStatement || $res === false) { // если запрос не выполнен
                die('<b>' . __METHOD__ . '() error sql execution:</b> unknown error');
            }
            return $stm;
        } catch (Throwable $e) {

        } catch (Exception $e) {

        }

        if(!is_null($e)) {
            die('<b>' . __METHOD__ . '() error sql execution:</b> '. $e->getMessage());
        }
    }

    /**
     * Запрос одной строки
     *
     */
	public function row($sql, $params = []) {
		$result = $this->doQuery($sql, $params);
		return $result->fetch();
	}

    /**
     * Запрос всех строк
     *
     */
	public function all($sql, $params = []) {
		$result = $this->doQuery($sql, $params);
		return $result->fetchAll();
	}
}
