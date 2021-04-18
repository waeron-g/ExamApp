<?php
// Скрываю ошибки и инициализирую приложение
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);
    require_once "controller/main.php";
    $app = new MainController;