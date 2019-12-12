<?php
require_once __DIR__.'/RoleUserList.php';
require_once 'custom/include/Workflow/WFStatusAssigned.php';

/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author  Evgeny Pervushin <pea@lab321.ru>
 * @since version 0.13.0
 *
 * Закрепленный за ролью или все в роли
 * Сначала осуществляется поиск пользователя, закрепленного за ролью в записи.
 * Роль берется из поля "Роль" в статусе.
 * Пользователи со статусом "Не активен" игнорируются.
 * Если пользователей не найдено, возвращаются "Все в роли".
 */
class StatusAssignedRoleUserList extends RoleUserList {

    public function getList($bean) {
        $statusUsers = WFStatusAssigned::getAssignedUsers($this->status_data['role_id'], $bean->id, $bean->module_name);
        if(!empty($statusUsers)) {
            return $statusUsers;
        }
        return parent::getList($bean);
    }
}
