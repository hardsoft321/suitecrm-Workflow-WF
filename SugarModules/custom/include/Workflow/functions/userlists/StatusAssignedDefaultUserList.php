<?php
require_once __DIR__.'/DefaultUserList.php';
require_once 'custom/include/Workflow/WFStatusAssigned.php';

/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author  Evgeny Pervushin <pea@lab321.ru>
 * @since version 0.7.9.9
 *
 * Закрепленный за ролью или все в роли и группе
 */
class StatusAssignedDefaultUserList extends DefaultUserList {
    
    public function getList($bean) {
        $statusUsers = WFStatusAssigned::getAssignedUsers($this->status_data['role_id'], $bean->id, $bean->module_name);
        if(!empty($statusUsers)) {
            return $statusUsers;
        }
        return parent::getList($bean);
    }
}
?>
