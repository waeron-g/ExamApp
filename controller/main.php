<?php
 class MainController
 {

    var $data = [];
    var $pages;
    var $role;

    public function __construct()
    {
        // Подключаю все необходимые файлы и инициализирую модели
        require_once "model/database.php";
        require_once "model/roles.php";
        require_once "model/pages.php";
        $this->pages = new PagesModel();
        $this->role = new RolesModel();
        // Обрабатываю действия со стороны клиента
        if ($_POST)
            $this->PreparePost();
        // Получаю все необходимые данные, которые отображаются у всех пользователей
        $this->data['allPages'] = $this->pages->get();
        $this->data['roles'] = $this->role->get();
        // Получаю доступные для роли страницы
        if ($_GET['role'] > 0)
            $this->data['accessPages'] = $this->pages->get($_GET['role']);
        // Получаю список пользователей и их права на определенную страницу (можно было сделать и через сессии)
        if ($_GET['pageEdit'] > 0)
            $this->data['accessRoles'] = $this->pages->getAccessList($_GET['pageEdit']);
        // Отрисовываю представление
        require_once "view/main.php";
    }

    private function PreparePost()
    {
        // В зависимости от действия вызываю метод и очищаю пост через переадресацию
        if ($_POST['action'] == "addMenu")
            $this->pages->post($_POST['name']);
        if ($_POST['action'] == "addRole")
            $this->role->post($_POST['name']);
        if ($_POST['action'] == "deletePage")
            $this->pages->delete($_POST['page_id']);
        if ($_POST['action'] == "editPage")
            $this->pages->put($_POST['page_id'], $_POST['roles']); 
        header("Location:/");
    }
 }