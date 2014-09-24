<?php
class OwnerUserList extends BaseUserList {
    
    public function getList($bean) {
        $q = "SELECT u.* FROM users u WHERE u.id = '".$bean->fetched_row['assigned_user_id']."'";
        return parent::getUsersBySql($q);
    }
        
    public function getName() {
        return 'Ответственный';
    }
}
?>
