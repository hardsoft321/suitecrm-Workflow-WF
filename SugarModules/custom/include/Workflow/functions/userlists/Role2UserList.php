<?php
require_once __DIR__.'/RoleUserList.php';

/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author  Evgeny Pervushin <pea@lab321.ru>
 * @since version 0.13.0
 *
 * Все в роли 2
 * Функция работает так же, как функция "Все в роли", но для определения
 * роли используется поле "Роль 2" статуса.
 */
class Role2UserList extends RoleUserList {

    public function getList($bean) {
        $this->statusRoleField = 'role2_id';
        return parent::getList($bean);
    }
}
