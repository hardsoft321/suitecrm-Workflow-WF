<?php
require_once __DIR__.'/WFAclRoles.php';

class WFManager {
    
    private static $statusFieldCache = array();
    private static $allStatusesCache = array();
    
    /**
     * Выбирает workflow для записи.
     * @return mixed string Workflow id или false
     */
    public static function getWorkflowForBean($bean) {
        global $db;
        $getTypeQuery = "SELECT type_field FROM wf_modules WHERE wf_module = '{$bean->module_name}' AND deleted = 0";
        $typeResult = $db->query($getTypeQuery);
        $row = $db->fetchByAssoc($typeResult);
        $typeField = $row['type_field'];
        
        $q = "SELECT w.id FROM wf_workflows w
	    WHERE
	        w.wf_module = '{$bean->module_name}'
            AND w.bean_type LIKE '%^{$bean->$typeField}^%'
            AND w.deleted = 0
            ";

        $qr = $db->query($q);
        if($row = $db->fetchByAssoc($qr))
            return $row['id'];
        //sugar_die('No workflow for bean');
        return '';
    }

    public static function isBeanInWorkflow($bean) {
        return isset($bean->wf_id) && $bean->wf_id;
    }

    public static function getNextStatuses($bean, $status1 = null) {
        global $db;
        
        if(isset($bean->wf_id) && $bean->wf_id && $status1 === null) {
            $statusField = self::getBeanStatusField($bean);
            if(!$statusField) {
                return array();
            }
            $status1 = $bean->$statusField;
        }
        if(isset($bean->wf_id) && $bean->wf_id && $status1) {
            if(!self::canChangeStatus($bean, $status1)) {
                return array();
            }

            $q = "SELECT s2.uniq_name, s2.name, e.filter_function FROM wf_events e
            LEFT JOIN wf_statuses s2 ON s2.id = e.status2_id
            WHERE
                e.status1_id IN (SELECT id FROM wf_statuses WHERE uniq_name='{$status1}' AND wf_module = '{$bean->module_name}' AND deleted = 0)
                AND e.workflow_id = '{$bean->wf_id}'
                AND e.deleted = 0
            ORDER BY e.sort
            ";
        }
        else {
            $q = "SELECT s2.uniq_name, s2.name, e.filter_function FROM wf_events e
            LEFT JOIN wf_statuses s2 ON s2.id = e.status2_id
            WHERE
                (e.status1_id = '' OR e.status1_id IS NULL)
                AND e.deleted = 0
            ORDER BY e.sort
            ";
        }
        
        $qr = $db->query($q);
        $res = array();
        while ($row = $db->fetchByAssoc($qr)) {
            $enabled = true;
            if($row['filter_function'] && !self::checkBeanAgainstFunction($bean, $row['filter_function'])) {
                $enabled = false;
            }
            if($enabled)
                $res[$row['uniq_name']] = $row['name'];
        }
        return $res;
    }
    
    public static function getAllStatuses($bean) {
        global $db;
        
        if(isset(self::$allStatusesCache[$bean->module_name]))
            return self::$allStatusesCache[$bean->module_name];
        
        $q = "SELECT uniq_name, name FROM wf_statuses 
        WHERE
            wf_module = '{$bean->module_name}'
            AND deleted = 0
        ";
        
        $qr = $db->query($q);
        $res = array();
        while ($row = $db->fetchByAssoc($qr)) {
            $res[$row['uniq_name']] = $row['name'];
        }
        self::$allStatusesCache[$bean->module_name] = $res;
        return $res;
    }
    
    public static function getBeanCurrentStatus($bean) {
        global $db;
        
        $statusField = self::getBeanStatusField($bean);
        if(!$statusField)
            return '';
        $q = "SELECT uniq_name, name FROM wf_statuses 
        WHERE
            uniq_name = '{$bean->$statusField}'
            AND wf_module = '{$bean->module_name}'
            AND deleted = 0
        ";
        
        $qr = $db->query($q);
        $status = BeanFactory::newBean('WFStatuses');
        if ($row = $db->fetchByAssoc($qr)) {
            $status->populateFromRow($row);
        }
        else {
            return false;
        }    
        
        return $status;
    }
    
    public static function isEventAllowed($bean, $status1, $status2) {
        return $status1 == $status2 ? true : array_key_exists($status2, self::getNextStatuses($bean, $status1));
    }
    
    public static function canChangeStatus($bean, $status1) {
        global $current_user;
        if(is_admin($current_user))
            return true;
        return 
            array_key_exists($current_user->id, self::getUserList($bean, $status1, 'confirm_check_list_function')) &&
            array_key_exists($current_user->id, self::getUserList($bean, $status1, 'confirm_list_function'));
    }
    
    public static function isInFrontAssignedUsers($user_id, $bean, $status1) {
        return array_key_exists($user_id, self::getUserList($bean, $status1, 'front_assigned_list_function'));
    }
    
    public static function isInConfirmUsers($user_id, $bean, $status1) {
        return array_key_exists($user_id, self::getUserList($bean, $status1, 'confirm_list_function'));
    }
    
    public static function canChangeAssignedUser($bean, $status) {
        global $current_user;
        if(is_admin($current_user))
            return true;
        return array_key_exists($current_user->id, self::getUserList($bean, $status, 'assigned_list_function'));
    }
    
    public static function checkAccess($bean, $action) {
        if($action == 'edit' || $action == 'delete') {
            if(self::isBeanInWorkflow($bean)) {
                $statusField = self::getBeanStatusField($bean);
                if($statusField) {
                    $status1 = $bean->$statusField;
                    if(!self::isFitEditRole($bean, $status1))
                        return false;
                }
            }
        }
        return true;
    }
    
    protected static function isFitEditRole($bean, $status) {
        global $db;
        global $current_user;
        //if(is_admin($current_user))
        //    return true;
        
        $q = "SELECT edit_role_type, role_id FROM wf_statuses WHERE uniq_name='{$status}' AND wf_module = '{$bean->module_name}' AND deleted = 0";
        if($row = $db->fetchOne($q)) {
            if($row['edit_role_type'] == 'nobody') {
                return false;
            }
            /*if($row['edit_role_type'] == 'role') {
                return WFAclRoles::userHasRole($row['role_id']);
            }*/
            /*if($row['edit_role_type'] == 'assigned') {
                require_once __DIR__."/WFStatusAssigned.php";
                return WFStatusAssigned::hasAssignedUser($row['role_id'], $bean->id, $bean->module_name, $current_user->id);
            }*/
            if($row['edit_role_type'] == 'owner') {
                return $bean->isOwner($current_user->id);
            }
        }
        return false;
    }
    
    public static function getBeanStatusField($bean) {
        global $db;
        if(!$bean->wf_id)
            return false;
        if(isset(self::$statusFieldCache[$bean->wf_id]))
            return self::$statusFieldCache[$bean->wf_id];
        $statusFieldQuery = "SELECT status_field FROM wf_workflows WHERE id = '{$bean->wf_id}'";
        $statusFieldResult = $db->query($statusFieldQuery);
        $row = $db->fetchByAssoc($statusFieldResult);
        self::$statusFieldCache[$bean->wf_id] = $row['status_field'];
        return $row['status_field'];
    }
    
    /**
     * Выбирает из таблицы аудита информацию о переходах.
     * Вместо этой функции следует использовать функцию logStatusChange, так как она сохраняет текст резолюции
     */
    public static function getStatusAuditForBean($bean) {
        global $db;
        $statusField = self::getBeanStatusField($bean);
        if(!$statusField)
            return false;
        $q = "SELECT * FROM {$bean->table_name}_audit a
        LEFT JOIN wf_statuses s ON s.uniq_name = a.after_value_string
        LEFT JOIN users u ON a.created_by = u.id
        WHERE parent_id = '{$bean->id}' AND field_name = '{$statusField}'
            AND s.wf_module = '{$bean->module_name}'
        ORDER BY date_created DESC
        ";
        $res = $db->query($q);
        $audit = array();
        while ($row = $db->fetchByAssoc($res)) {
            $audit[] = $row;
        }
        return $audit;
    }
    
    public static function logStatusChange($bean, $status1, $status2, $saveBean = true) {
        global $timedate;
        global $current_user;
        global $app_list_strings;
        
        $cur_date = $timedate->handle_offset(gmdate($timedate->get_db_date_time_format()), 'd.m.Y H:i', 'd.m.Y H:i', $current_user, 'Europe/Moscow') . " (МСК)";
        $confirm_text = 'Перевод на "'.
            self::translateStatus($status2, $bean->module_name) . '", '.
            $cur_date . ', '.
            $current_user->full_name.
            (isset($bean->last_resolution) && $bean->last_resolution ? ' -- ' . $bean->last_resolution : '').
            '; ';
        $bean->confirm_list = $confirm_text . (isset($bean->confirm_list) ? $bean->confirm_list : '');
        
        if($saveBean) {
            $bean->save();
        }
    }
    
    public static function runAfterEventHooks($bean, $status1, $status2) {
        global $db;
        
        $q = "SELECT e.after_save
                    , s1.role_id AS s1_role_id
                    , s2.role_id AS s2_role_id
          FROM wf_events e, wf_statuses s1, wf_statuses s2
          WHERE
                e.status1_id = s1.id AND s1.uniq_name='{$status1}' AND s1.wf_module = '{$bean->module_name}' AND s1.deleted = 0
            AND e.status2_id = s2.id AND s2.uniq_name='{$status2}' AND s2.wf_module = '{$bean->module_name}' AND s2.deleted = 0
            AND e.workflow_id = '{$bean->wf_id}'
            AND e.after_save IS NOT NULL
            AND e.deleted = 0
        ";
        
        $res = $db->query($q);
        while ($row = $db->fetchByAssoc($res)) {
            if($row['after_save']) {
                require_once __DIR__.'/functions/BaseProcedure.php';
                require_once 'custom/include/Workflow/functions/procedures/'.$row['after_save'].'.php';
                $proc = new $row['after_save'];
                $proc->status1_data = array(
                    'role_id' => $row['s1_role_id'],
                );
                $proc->status2_data = array(
                    'role_id' => $row['s2_role_id'],
                );
                $proc->doWork($bean);
            }
        }
    }
    
    public static function autoFillAssignedUser($bean, $status1) {
        global $current_user;
        $statusBean = self::getStatusBeanByName($status1, $bean->module_name);
        if(!$statusBean) {
            $GLOBALS['log']->error("WFManager: status not found for {$bean->module_name} {$bean->id}");
            return;
        }
        require_once __DIR__."/WFStatusAssigned.php";
        if(WFStatusAssigned::hasAssignedUser($statusBean->role_id, $bean->id, $bean->module_name))
            return;
        WFStatusAssigned::setAssignedUser($statusBean->role_id, $bean->id, $bean->module_name, $current_user->id);
    }
    
    public static function getEditFormData($bean) {
        $data = array();
        if(self::isBeanInWorkflow($bean)) {
            global $current_user;
            
            $statusField = self::getBeanStatusField($bean);
            if(!$statusField) {
                return array();
            }
            $status1 = $bean->$statusField;
            $statusBean = self::getStatusBeanByName($status1, $bean->module_name);

            $statuses = self::getNextStatuses($bean, $status1);
            $assignedUsers = self::getUserList($bean, $statuses, 'front_assigned_list_function');
            $assignedUsersData = array();
            foreach($assignedUsers as $status => $userList) {
                $assignedUsersData[$status] = array();
                foreach($userList as $user) {
                    $assignedUsersData[$status][] = array($user->id, $user->first_name.' '.$user->last_name);
                }
            }
            
            $rolesData = self::getAllowedRolesData($bean);
            $roles = array();
            $confirmUsersData = array();
            foreach($rolesData as $role_id => $roleData) {
                $roles[$role_id] = $roleData['role_name'];
                if(isset($roleData['users'])) {
                    foreach($roleData['users'] as $user_id => $user) {
                        $confirmUsersData[$role_id][] = array($user_id, $user->first_name.' '.$user->last_name);
                    }
                }
            }
            
            require_once __DIR__.'/WFStatusAssigned.php';
            $statusAssignedUsers = WFStatusAssigned::getAllAssignedUsers($bean->id, $bean->module_name);
            
            if (!empty($statuses) || !empty($confirmUsersData) || !empty($statusAssignedUsers)) {
                $data['currentStatus'] = $status1;
                $data['newStatuses'] = $statuses;
                $data['assignedUsersString'] = json_encode($assignedUsersData);
                $data['errors'] = array();
                $data['roles'] = $roles;
                $data['confirmUsersString'] = json_encode($confirmUsersData);
                $data['currentRole'] = $statusBean->role_id;
                $data['statusAssignedUsers'] = $statusAssignedUsers;
            }            
        }
        return $data;
    }
    
    public function getStatusesWithRole($role_id) {
        global $db;
        $q = "SELECT uniq_name FROM wf_statuses WHERE role_id = '$role_id' AND deleted = 0";
        $qr = $db->query($q);
        $statuses = array();
        while($row = $db->fetchByAssoc($qr)) {
            $statuses[] = $row['uniq_name'];
        }
        return $statuses;
    }
    
    /**
     * Вычисляет список пользователей
     * $bean бин в маршруте
     * $functionField string поле в статусе, которое хранит имя функции.
     * $statuses string/array уникальное имя статуса или массив имен
     * Возвращает массив пользователей или массив массивов для каждого статуса
     */
    protected static function getUserList($bean, $statuses, $functionField) {
        global $db;
        
        if(is_string($statuses)) {
            $status = $statuses;
            $statuses = array($status => $status);
        }
        if(empty($statuses)) {
            return array();
        }
        
        $q = "SELECT id, uniq_name, $functionField, role_id, role2_id 
              FROM wf_statuses WHERE uniq_name IN ('".implode("','", array_keys($statuses))."') AND wf_module = '{$bean->module_name}' AND deleted = 0";
        $qr = $db->query($q);
        $res = array();
        while ($row = $db->fetchByAssoc($qr)) {
            $userList = array();
            $functionName = $row[$functionField] ? $row[$functionField] : 'DefaultUserList';
            if(file_exists(__DIR__.'/functions/userlists/'.$functionName.'.php')) {
                require_once __DIR__.'/functions/BaseUserList.php';
                require_once __DIR__.'/functions/userlists/'.$functionName.'.php';
                $func = new $functionName;
                $func->status_data = array(
                    'id' => $row['id'],
                    'role_id' => $row['role_id'],
                    'role2_id' => $row['role2_id'],
                );
                $userList = $func->getList($bean);
            }
            else {
                $GLOBALS['log']->error('WFManager: user list function $functionName not found');
            }
            $res[$row['uniq_name']] = $userList;
        }
        
        if(isset($status) && isset($res[$status])) {
            return $res[$status];
        }
        return $res;
    }
    
    protected static function getAllowedRolesData($bean) {
        global $db;
        $q = "SELECT s.uniq_name, s.role_id, r.name AS role_name 
        FROM wf_statuses s, acl_roles r 
        WHERE 
            s.wf_module='{$bean->module_name}' AND s.deleted = 0
            AND s.role_id = r.id
            AND r.deleted = 0";
        $qr = $db->query($q);
        $res = array();
        $rolesPermissions = array();
        while($row = $db->fetchByAssoc($qr)) {
            if((!isset($rolesPermissions[$row['role_id']]) || $rolesPermissions[$row['role_id']]) && self::canChangeAssignedUser($bean, $row['uniq_name'])) {
                $res[$row['role_id']]['role_name'] = $row['role_name'];
                $users = self::getUserList($bean, $row['uniq_name'], 'confirm_list_function');
                foreach($users as $user_id => $user) {
                    $res[$row['role_id']]['users'][$user_id] = $user;
                }
            }
            else {
                $rolesPermissions[$row['role_id']] = false;
                unset($res[$row['role_id']]);
            }
        }
        return $res;
    }
    
    protected static function translateStatus($status, $module_name) {
        global $db;
        $q = "SELECT name FROM wf_statuses WHERE uniq_name='{$status}' AND wf_module='{$module_name}' AND deleted = 0";
        $row = $db->fetchOne($q);
        if(!$row)
            return $status;
        return $row['name'];
    }
    
    protected static function getStatusIdByName($status, $module_name) {
        global $db;
        $row = $db->fetchOne("SELECT id FROM wf_statuses WHERE uniq_name='{$status}' AND wf_module='{$module_name}' AND deleted = 0");
        return $row ? $row['id'] : false;
    }
    
    protected static function getStatusBeanByName($status, $module_name) {
        global $db;
        $row = $db->fetchOne("SELECT * FROM wf_statuses WHERE uniq_name='{$status}' AND wf_module='{$module_name}' AND deleted = 0");
        if($row) {
            $statusBean = BeanFactory::newBean('WFStatuses');
            $statusBean->populateFromRow($row);
            return $statusBean;
        }
        return false;
    }
    
    protected static function checkBeanAgainstFunction($bean, $filter_function) {
        require_once 'custom/include/Workflow/functions/filters/'.$filter_function.'.php';
        $filter = new $filter_function();
        return $filter->checkBean($bean);
    }
}
?>
