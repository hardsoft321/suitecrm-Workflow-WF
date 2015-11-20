<?php
/**
 * Все в роли и в группе
 *
 * Эта функция используется по умолчанию.
 * Возвращает список пользователей, находящихся в тех же группах, что и запись,
 * включая их родительские группы (вплоть до корневой группы), а также обладающих
 * ролью, настроенной в поле "Роль" данного статуса.
 * Роль через securitygroups здесь не проверяется, т.е. роль должна быть привязана
 * напрямую к пользователю.
 * Пользователи со статусом "Не активен" игнорируются.
 */
class DefaultUserList extends BaseUserList {
    
    protected $statusRoleField = 'role_id';
    protected $additionalWhere = '';
    
    public function getList($bean) {
        $groups = $this->getBeanGroups($bean);
        if(empty($groups)) {
            return array();
        }
        $q = "SELECT DISTINCT users.* 
        FROM securitygroups, securitygroups_users, users, acl_roles_users
        WHERE 
            securitygroups.id IN ('".implode("','", $groups)."')
            AND securitygroups.id = securitygroups_users.securitygroup_id AND securitygroups_users.user_id = users.id
            AND acl_roles_users.user_id = users.id
            AND securitygroups.deleted = 0 AND securitygroups_users.deleted = 0 AND users.deleted = 0 AND users.status != 'Inactive' AND acl_roles_users.deleted = 0
            AND acl_roles_users.role_id = '".$this->status_data[$this->statusRoleField]."'"; //Роль через securitygroups здесь не проверяется, т.е. роль должна быть привязана напрямую к пользователю
        if($this->additionalWhere) {
            $q .= " AND ".$this->additionalWhere;
        }
        $q .= " ORDER BY last_name"; 
        return parent::getUsersBySql($q);
    }

    protected function getBeanGroups($bean) {
        global $db;
        if(!isset($bean->workflowData) || !isset($bean->workflowData['allRecordGroups'])) {
            if(!isset($bean->workflowData)) {
                $bean->workflowData = array();
            }
            require_once('modules/SecurityGroups/SecurityGroup.php');
            $groupFocus = new SecurityGroup();
            if(method_exists($groupFocus, 'getAllRecordGroupsIds')) { //SecurityTeams321
                $bean->workflowData['allRecordGroups'] = $groupFocus->getAllRecordGroupsIds($bean->id, $bean->module_name);
            }
            else {
                $groups = array();
                $queryGroups = "SELECT securitygroup_id AS id FROM securitygroups_records WHERE record_id = '{$bean->id}' AND module = '{$bean->module_name}' AND deleted = 0";
                $res = $db->query($queryGroups);
                while($row = $db->fetchByAssoc($res)) {
                    $groups[$row['id']] = $row['id'];
                }
                $bean->workflowData['allRecordGroups'] = $groups;
            }
        }
        return $bean->workflowData['allRecordGroups'];
    }
}
?>
