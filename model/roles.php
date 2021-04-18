<?

class RolesModel extends DataBase
{
    // Получение списка ролей
    public function get(){
        $data = $this->db->query("SELECT * FROM `roles`");
        return (mysqli_fetch_all($data, MYSQLI_ASSOC));
    }

    // Добавление роли
    public function post($name){
        $name = $this->validate($name);
        if ($name)
            $this->db->query("INSERT INTO `roles` (`role_name`) VALUES ('" .$name. "')");
    }
    // Удаление роли
    public function delete($role_id){
        $role_id = intval($role_id);
        $this->db->query("DELETE from `roles` WHERE `role_id`=" . $role_id);
        $this->db->query("DELETE from `access` WHERE `role`=" . $role_id);        
    }
}