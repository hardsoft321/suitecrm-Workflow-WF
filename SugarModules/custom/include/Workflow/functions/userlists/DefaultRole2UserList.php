<?php
require_once __DIR__.'/DefaultUserList.php';

class DefaultRole2UserList extends DefaultUserList {
    
    public function getList($bean) {
        $this->statusRoleField = 'role2_id';
        return parent::getList($bean);
    }

    public function getName() {
        return 'Все в роли 2 и в группе';
    }
}
?>
