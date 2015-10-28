<?php
require_once __DIR__.'/DefaultUserList.php';

/**
 * Текущий пользователь в роли и в группе
 * К функции "Все в роли и группе" добавляется условие так, что возвращается
 * только текущий пользователь (если он подходит по роли и группе).
 */
class DefaultCurrentUserList extends DefaultUserList {
    
    public function getList($bean) {
        global $current_user;
        $this->additionalWhere = "users.id = '{$current_user->id}'";
        return parent::getList($bean);
    }
}
?>
