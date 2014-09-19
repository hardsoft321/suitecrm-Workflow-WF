<?php
class OwnerUserList extends BaseUserList {
    
    public function getList($bean) {
        $q = "SELECT u.* FROM users u WHERE u.id = '{$bean->assigned_user_id}'";
        return parent::getUsersBySql($q);
    }
    
    public function checkUser($bean, $user_id) {
        return $bean->isOwner($user_id);
    }
    
    public function getName() {
        return 'Ответственный';
    }
}
?>
