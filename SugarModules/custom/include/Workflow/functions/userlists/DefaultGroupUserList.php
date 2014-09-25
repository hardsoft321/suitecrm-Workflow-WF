<?php
require_once __DIR__.'/DefaultUserList.php';

class DefaultGroupUserList extends DefaultUserList {
    
    public function getList($bean) {
        $this->additionalWhere = 'users.is_group = 1';
        return parent::getList($bean);
    }

    public function getName() {
        return 'Групповые пользователи в роли и в группе';
    }
}
?>
