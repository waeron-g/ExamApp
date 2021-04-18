<?php

class DataBase {

    var $db;

    public function __construct(){
        // Считываю настройки подключения
        $dbParams = parse_ini_file("config.ini");
        try {
        // Проверяю возможность подключения и в случае успеха проверяю наличие таблиц, иначе вывожу ошибку
            $this->db = new mysqli($dbParams['db_adress'], $dbParams['db_login'], $dbParams['db_pass'], $dbParams['db_name']);
            if ($this->db->connect_errno > 0)
                throw new Exception("Ошибка подключения к базе данных. Проверьте файл настроек БД config.ini");
            if ($this->db->query("SHOW TABLES FROM ".$dbParams['db_name'])->num_rows == 0)
                $this->init_database();
        } catch (Exception $e) {
            print_r(date('d-m-Y H:m:s')." : ". $e->getMessage(). PHP_EOL);
        }
    }

    private function init_database()
    {
        // Создаю все необходимые для проекта таблицы
        $this->db->query("CREATE TABLE IF NOT EXISTS `roles` (
            `role_id` int(11) NOT NULL primary key AUTO_INCREMENT,
            `role_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $this->db->query("CREATE TABLE IF NOT EXISTS `pages` (
            `page_id` int(11) NOT NULL primary key AUTO_INCREMENT,
            `page_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $this->db->query("CREATE TABLE IF NOT EXISTS `access` (
            `page` int(11) NOT NULL,
            `role` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    public function validate($value)
    {
        // Проверяю строку на лишние символы и очищаю их для большей безопсности, также можно было воспользоваться PDO, что более безопасно
        $value = trim($value);
        $value = strip_tags($value);
        $value = htmlentities($value, ENT_QUOTES,'UTF-8');
        return $value;
    }
}