<?php

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
        sugar_die('No workflow for bean');
        return false;
    }

    public static function isBeanInWorkflow($bean) {
        return isset($bean->wf_id);
    }

    public static function getNextStatuses($bean, $status1 = null) {
        global $db;
        global $current_user;
        $mustCheckRole = !is_admin($current_user);
        
        if($bean->wf_id && $status1 === null) {
            $statusField = self::getBeanStatusField($bean);
            if(!$statusField)
                sugar_die('Field for status not found');
            $status1 = $bean->$statusField;
        }
        if($bean->wf_id && $status1) {
            if($mustCheckRole) //TODO: добавить роли, привязанные к группе
                $q = "SELECT DISTINCT s2.uniq_name, s2.name, e.filter_function FROM wf_events e
                LEFT JOIN wf_statuses s2 ON s2.id = e.status2_id
                LEFT JOIN wf_statuses s1 ON s1.id = e.status1_id
                INNER JOIN acl_roles_users aru ON (s1.role_id = aru.role_id)
                WHERE
                    e.status1_id IN (SELECT id FROM wf_statuses WHERE uniq_name='{$status1}' AND wf_module = '{$bean->module_name}' AND deleted = 0)
                    AND e.workflow_id = '{$bean->wf_id}'
                    AND e.deleted = 0
                    AND aru.user_id = '{$current_user->id}'
                ORDER BY e.sort
                ";
            else
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
            ($bean->last_resolution ? ' -- ' . $bean->last_resolution : '').
            '; ';
        $bean->confirm_list = $confirm_text . $bean->confirm_list;
        
        if($saveBean) {
            $bean->save();
        }
    }
    
    public static function runAfterEventHooks($bean, $status1, $status2) {
        global $db;
        
        $q = "SELECT after_save FROM wf_events e
        WHERE
            e.status1_id IN (SELECT id FROM wf_statuses WHERE uniq_name='{$status1}' AND wf_module = '{$bean->module_name}' AND deleted = 0)
            AND e.status2_id IN (SELECT id FROM wf_statuses WHERE uniq_name='{$status2}' AND wf_module = '{$bean->module_name}' AND deleted = 0)
            AND e.workflow_id = '{$bean->wf_id}'
            AND e.after_save IS NOT NULL
            AND e.deleted = 0
        ";
        
        $res = $db->query($q);
        while ($row = $db->fetchByAssoc($res)) {
            if($row['after_save']) {
                require_once 'custom/include/Workflow/functions/procedures/'.$row['after_save'].'.php';
                $proc = new $row['after_save'];
                $proc->doWork($bean);
            }
        }
    }
    
    protected static function translateStatus($status, $module_name) {
        global $db;
        $q = "SELECT name FROM wf_statuses WHERE uniq_name='{$status}' AND wf_module='{$module_name}'";
        $row = $db->fetchOne($q);
        if(!$row)
            return $status;
        return $row['name'];
    }
    
    protected static function checkBeanAgainstFunction($bean, $filter_function) {
        require_once 'custom/include/Workflow/functions/filters/'.$filter_function.'.php';
        $filter = new $filter_function();
        return $filter->checkBean($bean);
    }
}
?>
