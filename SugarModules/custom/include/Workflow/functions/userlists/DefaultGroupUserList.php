<?php
require_once __DIR__.'/DefaultUserList.php';

/**
 * Групповые пользователи в роли и в группе
 */
class DefaultGroupUserList extends DefaultUserList {
    
    public function getList($bean) {
        $this->additionalWhere = 'users.is_group = 1';
        return parent::getList($bean);
    }
}
?>
