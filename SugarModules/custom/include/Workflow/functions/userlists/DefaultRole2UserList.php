<?php
require_once __DIR__.'/DefaultUserList.php';

/**
 * Все в роли 2 и в группе
 */
class DefaultRole2UserList extends DefaultUserList {
    
    public function getList($bean) {
        $this->statusRoleField = 'role2_id';
        return parent::getList($bean);
    }
}
?>
