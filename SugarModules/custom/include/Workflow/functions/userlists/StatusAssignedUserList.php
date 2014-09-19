<?php
require_once 'custom/include/Workflow/WFStatusAssigned.php';

class StatusAssignedUserList extends BaseUserList {
    
    public function getList($bean) {
        return WFStatusAssigned::getAssignedUsers($this->status2_data['id'], $bean->id, $bean->module_name);
    }

    public function getName() {
        return 'Ответственные на статусе';
    }
}
?>
