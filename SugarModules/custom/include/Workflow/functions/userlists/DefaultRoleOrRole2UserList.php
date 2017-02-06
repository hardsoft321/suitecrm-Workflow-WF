<?php
require_once __DIR__.'/DefaultUserList.php';

/**
 * Все в роли и в группе или все в роли 2 и в группе
 * Функция работает так же, как функция "Все в роли и группе", но для определения
 * роли использоется поле "Роль" и "Роль 2" статуса.
 */
class DefaultRoleOrRole2UserList extends DefaultUserList {
    
    public function getList($bean) {
        $users = array();
        $this->statusRoleField = 'role_id';
        $users = parent::getList($bean);
        $this->statusRoleField = 'role2_id';
        return array_merge($users, parent::getList($bean));
    }

    public function getName() {
        return 'Все в роли и в группе или все в роли 2 и в группе';
    }

}
?>
