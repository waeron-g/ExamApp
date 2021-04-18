<?

class PagesModel extends DataBase{

    public function get($user_id = 0){
        // Привожу к целому, чтобы убрать лишние символы (для защиты от sql-инъекций)
        $user_id = intval($user_id);
        if ($user_id == 0) // Если пользователь не задан, то получаю все страницы
        {
            $data = $this->db->query("SELECT * FROM `pages`");
            $data = mysqli_fetch_all($data, MYSQLI_ASSOC);
            $result = [];
            foreach ($data as $row)
            {
                $roles = $this->db->query("SELECT * FROM `roles`
                INNER JOIN `access` ON roles.role_id = access.role
                WHERE access.page = ". $row['page_id']);
                if ($roles)
                    $roles = mysqli_fetch_all($roles, MYSQLI_ASSOC);
                $result[] = array(
                    'page_id' => $row['page_id'],
                    'page_name' => $row['page_name'],
                    'access'   => $roles
                );
            }
            return($result);
        }
        else {//Получаю страницы для определенного пользователя
            $data = $this->db->query("SELECT * FROM `pages`
            INNER JOIN `access` ON pages.page_id = access.page
            WHERE access.role = ". $user_id);
            if ($data)
                return(mysqli_fetch_all($data, MYSQLI_ASSOC));
            return (null);
        }
    }

    public function post($name){ // Валидирую имя таблицы и добавляю, если оно не пустое
        $name = $this->validate($name);
        if ($name)
            $this->db->query("INSERT INTO `pages` (`page_name`) VALUES ('".$name."')");
    }

    public function getAccessList($page_id)
    {
        // функция необходима для получения всех пользователей и определения их прав на определенную страницу
        $page_id = intval($page_id);
        $data = $this->db->query("SELECT * FROM `roles`
        LEFT JOIN `access` ON roles.role_id = access.role");
        if ($data)
        {
            $data = mysqli_fetch_all($data, MYSQLI_ASSOC);
            $result = [];
            foreach ($data as $row)
            {
                if ($row['page_id'] == $page_id or $row['page_id'] == NULL)
                    $result[] = $row;
            }
            return($result);
        }
        return ([]);

    }

    public function put($id, $users = []){
        $id = intval($id);
        if (count($users) > 0)
        {
            $rows = $this->getCurrentUsers($id, $users);
            // Если есть что удалять, то генерирую запрос на удаление неактуальных записей из таблицы прав
            if (count($rows['old']) > 0)
            { 
                $delete = implode(") OR (`page` = ".$id." AND `role` = ",$rows['old']);
                $delete = "(`page` = ".$id." AND `role` = ". $delete. ")";
                $this->db->query("DELETE FROM `access` WHERE ". $delete);
            }
            // Если есть что добавлять, то генерирую запрос на добавление и выполняю сам запрос
            if (count($rows['new']) > 0)
            {
            $insert = implode("), (".$id.", ",$rows['new']);
            $insert = "(".$id.",". $insert. ")";
            $query = "INSERT INTO `access` (`page`, `role`) VALUES ". $insert;
            $this->db->query($query);
            }
        }
    }
    // беру текущие права доступа и сравниваю их с новыми, разделяя на те что нужно добавить и удалить
    private function getCurrentUsers($page_id, $users)
    {
        $data = $this->db->query("SELECT * FROM `access` WHERE `page` = ". $page_id);
        if ($data)
        {
        $data = mysqli_fetch_all($data, MYSQLI_ASSOC);
        $data = array_column($data, 'role');
        $result['old'] = array_diff($data, $users);
        $result['new'] = array_diff($users, $data);
        
        }
        else
        {
            $result['old'] = [];
            $result['new'] = $users;
        }
        return($result);
    }
    // Удаление страницы из базы данных, а также всех связанных с ней прав
    public function delete($page_id){
        $page_id = intval($page_id);
        $this->db->query("DELETE from `pages` WHERE `page_id`=" . $page_id);
        $this->db->query("DELETE from `access` WHERE `page`=" . $page_id);        
    }
}