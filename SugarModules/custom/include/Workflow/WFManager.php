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
        
        if($bean->wf_id && $status1 === null) {
            $statusField = self::getBeanStatusField($bean);
            if(!$statusField) {
                //sugar_die('Field for status not found');
                return array();
            }
            $status1 = $bean->$statusField;
        }
        if($bean->wf_id && $status1) {
            //if(!self::isFitOutRole($bean, $status1))
            if(!self::canChangeStatus($bean, $status1))
                return array();

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
        
        return $status;
    }
    
    public static function checkIsEventAllowed($bean, $status1, $status2) {
        if($status1 == $status2)
            return;
        $nextStatuses = self::getNextStatuses($bean, $status1);
        if(!array_key_exists($status2, $nextStatuses))
            sugar_die('Status changing is not allowed');
    }
    
    /*public static function checkOutRole($bean, $status) {
        if(!self::isFitOutRole($bean, $status))
            sugar_die('Access Denied');
    }*/
    
    /*protected static function isFitOutRole($bean, $status) {
        global $db;
        global $current_user;
        if(is_admin($current_user))
            return true;
        
        $q = "SELECT id, out_role_type, role_id FROM wf_statuses WHERE uniq_name='{$status}' AND wf_module = '{$bean->module_name}' AND deleted = 0";
        if($row = $db->fetchOne($q)) {
            if($row['out_role_type'] == 'role') {//TODO: check has view access (i.e. in same group)
                return WFAclRoles::userHasRole($row['role_id']);
            }
            if($row['out_role_type'] == 'assigned') {
                require_once __DIR__."/WFStatusAssigned.php";
                return WFStatusAssigned::hasAssignedUser($row['id'], $bean->id, $bean->module_name, $current_user->id);
            }
            return $bean->isOwner($current_user->id);
        }
        return false;
    }*/
    
    /*public static function getOutRoleUsers($bean, $status) {
        global $db;
        
        $q = "SELECT id, out_role_type, role_id, assigned_list_function FROM wf_statuses WHERE uniq_name='{$status}' AND wf_module = '{$bean->module_name}' AND deleted = 0";
        if($row = $db->fetchOne($q)) {
            if($row['out_role_type'] == 'role') {//TODO: check has view access (i.e. in same group)
                return WFAclRoles::userHasRole($row['role_id']);
            }
            if($row['out_role_type'] == 'assigned') {
                require_once __DIR__."/WFStatusAssigned.php";
                return WFStatusAssigned::hasAssignedUser($row['id'], $bean->id, $bean->module_name, $current_user->id);
            }
            if($row['out_role_type'] == 'owner') {
                return $bean->isOwner($current_user->id);
            }
        }
        return array();
    }*/
    
    public static function canChangeStatus($bean, $status1) {
        global $current_user;
//        if($user_id === null)
//            $user_id = $current_user;
        if(is_admin($current_user))
            return true;
        return self::isInConfirmUsers($current_user->id, $bean, $status1);
    }
    
    public static function isInConfirmUsers($user_id, $bean, $status1) {
        $users = self::getConfirmUsers($bean, $status1, $user_id);
        return array_key_exists($user_id, $users);
    }
    
    public static function canChangeAssignedUser($bean, $status) {
        global $current_user;
        if(is_admin($current_user))
            return true;
        $assignedUsers = self::getAssignedUsers($bean, $status);
        return array_key_exists($current_user->id, $assignedUsers);
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
            (isset($bean->last_resolution) ? ' -- ' . $bean->last_resolution : '').
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
        require_once __DIR__."/WFStatusAssigned.php";
        $status_id = self::getStatusIdByName($status1, $bean->module_name);
        if(!$status_id) {
            $GLOBALS['log']->error("WFManager: status id not found for {$bean->module_name} {$bean->id}");
            return;
        }
        if(WFStatusAssigned::hasAssignedUser($status_id, $bean->id, $bean->module_name, $current_user->id))
            return;
        WFStatusAssigned::addAssignedUser($status_id, $bean->id, $bean->module_name, $current_user->id);
    }
    
    public static function getNextAssignedUsers($bean, $statuses) {
        global $db;
        
        if(is_string($statuses)) {
            $status = $statuses;
            $statuses = array($status => $status);
        }
        if(empty($statuses)) {
            return array();
        }
        
        $q = "SELECT id, uniq_name, front_assigned_list_function, role_id 
              FROM wf_statuses WHERE uniq_name IN ('".implode("','", array_keys($statuses))."') AND wf_module = '{$bean->module_name}' AND deleted = 0";
        $qr = $db->query($q);
        $res = array();
        while ($row = $db->fetchByAssoc($qr)) {
            $userList = array();
            if($row['front_assigned_list_function']) {
                require_once __DIR__.'/functions/BaseUserList.php';
                if(file_exists('custom/include/Workflow/functions/userlists/'.$row['front_assigned_list_function'].'.php')) {
                    require_once 'custom/include/Workflow/functions/userlists/'.$row['front_assigned_list_function'].'.php';
                    $func = new $row['front_assigned_list_function'];
                    $func->status2_data = array(
                        'id' => $row['id'],
                        'role_id' => $row['role_id'],
                    );
                    $userList = $func->getList($bean);
                }
                else
                    $GLOBALS['log']->error('WFManager: user list function '.$row['front_assigned_list_function'].' not found');
            }
            $res[$row['uniq_name']] = $userList;
        }
        
        if(isset($status) && isset($res[$status])) {
            return $res[$status];
        }
        return $res;
    }

    public static function getAssignedUsers($bean, $status1) {
        global $db;
        $q = "SELECT id, uniq_name, assigned_list_function, role_id 
              FROM wf_statuses WHERE uniq_name = '$status1' AND wf_module = '{$bean->module_name}' AND deleted = 0";
        $res = array();
        if ($row = $db->fetchOne($q)) {
            if($row['assigned_list_function']) {
                require_once __DIR__.'/functions/BaseUserList.php';
                if(file_exists('custom/include/Workflow/functions/userlists/'.$row['assigned_list_function'].'.php')) {
                    require_once 'custom/include/Workflow/functions/userlists/'.$row['assigned_list_function'].'.php';
                    $func = new $row['assigned_list_function'];
                    $func->status2_data = array(
                        'id' => $row['id'],
                        'role_id' => $row['role_id'],
                    );
                    $res = $func->getList($bean);
                }
                else
                    $GLOBALS['log']->error('WFManager: assigned user list function '.$row['assigned_list_function'].' not found');
            }
        }
        return $res;
    }
    
    public static function getConfirmUsers($bean, $status1, $user_id = null) {
        global $db;
        $q = "SELECT id, uniq_name, confirm_list_function, role_id 
              FROM wf_statuses WHERE uniq_name = '$status1' AND wf_module = '{$bean->module_name}' AND deleted = 0";
        $res = array();
        if ($row = $db->fetchOne($q)) {
            if($row['confirm_list_function']) {
                require_once __DIR__.'/functions/BaseUserList.php';
                if(file_exists('custom/include/Workflow/functions/userlists/'.$row['confirm_list_function'].'.php')) {
                    require_once 'custom/include/Workflow/functions/userlists/'.$row['confirm_list_function'].'.php';
                    $func = new $row['confirm_list_function'];
                    $func->status2_data = array(
                        'id' => $row['id'],
                        'role_id' => $row['role_id'],
                    );
                    if($user_id !== null && method_exists($func, 'checkUser')) {
                        if($func->checkUser($bean, $user_id)) {
                            $res[$user_id] = $user_id;
                        }
                    }
                    else {
                        $res = $func->getList($bean);
                    }
                }
                else
                    $GLOBALS['log']->error('WFManager: confirm user list function '.$row['confirm_list_function'].' not found');
            }
        }
        return $res;
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

            $statuses = self::getNextStatuses($bean, $status1);
            $assignedUsers = self::getNextAssignedUsers($bean, $statuses);
            $assignedUsersData = array();
            foreach($assignedUsers as $status => $userList) {
                $assignedUsersData[$status] = array();
                foreach($userList as $user) {
                    $assignedUsersData[$status][] = array($user->id, $user->first_name.' '.$user->last_name);
                }
            }
            
            $confirmUsersData = array();
            if(self::canChangeAssignedUser($bean, $status1)) {
                $confirmUsers = self::getConfirmUsers($bean, $status1);
                foreach($confirmUsers as $user) {
                    $confirmUsersData[$user->id] = $user->first_name.' '.$user->last_name;
                }
            }

            if (!empty($statuses) || !empty($confirmUsersData)) {
                $data['newStatuses'] = $statuses;
                $data['assignedUsersString'] = json_encode($assignedUsersData);
                $data['errors'] = array();
                $data['confirmUsers'] = $confirmUsersData;
            }            
        }
        return $data;
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
    
    protected static function checkBeanAgainstFunction($bean, $filter_function) {
        require_once 'custom/include/Workflow/functions/filters/'.$filter_function.'.php';
        $filter = new $filter_function();
        return $filter->checkBean($bean);
    }
}
?>
