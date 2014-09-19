<?php
require_once 'custom/include/Workflow/WFStatusAssigned.php';

class StatusExAssignedUserList extends BaseUserList {
    
    public function getList($bean) {
        return WFStatusAssigned::getAssignedUsersEx($this->status2_data['id'], $bean->id, $bean->module_name);
    }

    public function getName() {
        return 'Ответственные на статусе, включая родительский статус';
    }
}
?>
