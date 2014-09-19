<?php
class AllRoleUserList extends BaseUserList {
    
    public function getList($bean) {
        $q = "SELECT DISTINCT u.* 
        FROM users u, acl_roles_users ru
        WHERE 
            ru.user_id = u.id
            AND u.deleted = 0 AND ru.deleted = 0
            AND ru.role_id = '".$this->status2_data['role_id']."'
        ORDER BY last_name";
        return parent::getUsersBySql($q);
    }
    
    public function getName() {
        return 'Все пользователи в роли';
    }
}
?>
