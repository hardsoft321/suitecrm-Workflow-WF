<?php
require_once __DIR__.'/DefaultUserList.php';
require_once 'custom/include/Workflow/WFStatusAssigned.php';

/**
 * Закрепленный за ролью или ответственный
 *
 * Сначала осуществляется поиск пользователя, закрепленного за ролью в записи.
 * Роль берется из поля "Роль" в статусе.
 * Пользователи со статусом "Не активен" игнорируются.
 * Если пользователей не найдено, возвращается ответственный для записи,
 * если он в роли и в группе.
 */
class StatusAssignedOrOwnerUserList extends DefaultUserList {
    
    public function getList($bean) {
        $statusUsers = WFStatusAssigned::getAssignedUsers($this->status_data['role_id'], $bean->id, $bean->module_name);
        if(!empty($statusUsers)) {
            return $statusUsers;
        }
        $this->additionalWhere = "users.id = '{$bean->assigned_user_id}'";
        return parent::getList($bean);
    }
}
?>
