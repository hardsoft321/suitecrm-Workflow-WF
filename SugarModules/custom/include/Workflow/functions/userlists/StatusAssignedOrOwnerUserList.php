<?php
require_once __DIR__.'/DefaultUserList.php';
require_once 'custom/include/Workflow/WFStatusAssigned.php';

/**
 * Закрепленный за ролью или ответственный
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
