<?php
require_once __DIR__.'/DefaultUserList.php';

/**
 * Групповые пользователи в роли и в группе
 * К функции "Все в роли и группе" добавляется условие так, что возвращаются
 * только групповые пользователи.
 */
class DefaultGroupUserList extends DefaultUserList {
    
    public function getList($bean) {
        $this->additionalWhere = 'users.is_group = 1';
        return parent::getList($bean);
    }
}
?>
