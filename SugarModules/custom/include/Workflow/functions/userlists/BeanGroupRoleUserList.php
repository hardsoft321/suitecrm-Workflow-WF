<?php
class BeanGroupRoleUserList extends BaseUserList {
    
    public function getList($bean) {
        $q = "SELECT DISTINCT u.* 
        FROM securitygroups_records sgr, securitygroups sg, securitygroups_users sgu, users u, acl_roles_users ru
        WHERE 
            sgr.record_id = '$bean->id' AND sgr.module = '$bean->module_name'
            AND sgr.securitygroup_id = sg.id AND sg.id = sgu.securitygroup_id AND sgu.user_id = u.id
            AND ru.user_id = u.id
            AND sgr.deleted = 0 AND sg.deleted = 0 AND sgu.deleted = 0 AND u.deleted = 0 AND ru.deleted = 0
            AND u.is_group = 0 AND ru.role_id = '".$this->status2_data['role_id']."'
        ORDER BY last_name"; //Роль через securitygroups здесь не проверяется, т.е. роль должна быть привязана напрямую к пользователю
        return parent::getUsersBySql($q);
    }

    public function getName() {
        return 'Пользователи в роли и в группе';
    }
}
?>
