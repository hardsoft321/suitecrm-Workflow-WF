<?php
require_once __DIR__.'/DefaultUserList.php';

/**
 * Все в роли 2 и в группе
 * Функция работает так же, как функция "Все в роли и группе", но для определения
 * роли используется поле "Роль 2" статуса.
 */
class DefaultRole2UserList extends DefaultUserList {
    
    public function getList($bean) {
        $this->statusRoleField = 'role2_id';
        return parent::getList($bean);
    }
}
?>
