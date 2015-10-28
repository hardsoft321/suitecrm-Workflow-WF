<?php
require_once __DIR__.'/DefaultUserList.php';
require_once 'custom/include/Workflow/WFStatusAssigned.php';

/**
 * Закрепленный за ролью или групповой
 * Сначала осуществляется поиск пользователя, закрепленного за ролью в записи.
 * Роль берется из поля "Роль" в статусе.
 * Пользователи со статусом "Не активен" игнорируются.
 * Если пользователей не найдено, возвращаются "Групповые пользователи в роли и в группе".
 */
class StatusAssignedUserList extends DefaultUserList {
    
    public function getList($bean) {
        $statusUsers = WFStatusAssigned::getAssignedUsers($this->status_data['role_id'], $bean->id, $bean->module_name);
        if(!empty($statusUsers)) {
            return $statusUsers;
        }
        $this->additionalWhere = 'users.is_group = 1';
        return parent::getList($bean);
    }
}
?>
