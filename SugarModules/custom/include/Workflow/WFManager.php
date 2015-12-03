<?php
require_once __DIR__.'/WFAclRoles.php';
require_once __DIR__.'/utils.php';

class WFManager {

    private static $statusFieldCache = array();
    private static $allStatusesCache = array();

    /**
     * Выбирает workflow для записи.
     * @return string Workflow id или ''
     */
    public static function getWorkflowForBean($bean, $typeField = null) {
        global $db;
        if($typeField === null) {
            $typeField = self::getWorkflowTypeField($bean);
        }
        if($typeField) {
            $q = "SELECT w.id FROM wf_workflows w
            WHERE
                w.wf_module = '{$bean->module_name}'
                AND w.bean_type LIKE '%^{$bean->$typeField}^%'
                AND w.deleted = 0
                ";
            if($row = $db->fetchOne($q))
                return $row['id'];
        }
        return '';
    }

    /**
     * Возвращает имя поля, по которому определяется маршрут
     */
    public static function getWorkflowTypeField($bean) {
        global $db;
        $q = "SELECT type_field FROM wf_modules WHERE wf_module = '{$bean->module_name}' AND deleted = 0";
        $row = $db->fetchOne($q);
        return $row ? $row['type_field'] : false;
    }

    public static function isBeanInWorkflow($bean) {
        return isset($bean->wf_id) && $bean->wf_id;
    }

    public static function getNextStatuses($bean, $status1 = null) {
        $res = array();
        $statusesEvents = self::getNextStatusesEvents($bean, $status1);
        foreach($statusesEvents as $row) {
            $res[$row['uniq_name']] = $row['name'];
        }
        return $res;
    }

    public static function getNextStatusesEvents($bean, $status1 = null) {
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

            $q = "SELECT s2.uniq_name, s2.name, e.filter_function, e.func_params, e.resolution_required, e.validate_function
            FROM wf_events e
            LEFT JOIN wf_statuses s2 ON s2.id = e.status2_id
            WHERE
                e.status1_id IN (SELECT id FROM wf_statuses WHERE uniq_name='{$status1}' AND wf_module = '{$bean->module_name}' AND deleted = 0)
                AND e.workflow_id = '{$bean->wf_id}'
                AND e.deleted = 0
                AND s2.deleted = 0
            ORDER BY e.sort
            ";
        }
        elseif(!empty($bean->wf_id) && empty($status1)) {
            $q = "SELECT s2.uniq_name, s2.name, e12.filter_function, e12.func_params, e12.resolution_required, e12.validate_function
            FROM wf_events e12
            INNER JOIN wf_statuses s2 ON s2.id = e12.status2_id
            INNER JOIN wf_events e23 ON s2.id = e23.status1_id
            WHERE
                (e12.status1_id IS NULL OR e12.status1_id = '')
                AND e23.workflow_id = '{$bean->wf_id}'
                AND e12.deleted = 0
                AND e23.deleted = 0
            ";
        }
        else {
            $q = "SELECT s2.uniq_name, s2.name, e.filter_function, e.func_params, e.resolution_required, e.validate_function
            FROM wf_events e
            LEFT JOIN wf_statuses s2 ON s2.id = e.status2_id
            WHERE
                (e.status1_id = '' OR e.status1_id IS NULL)
                AND e.deleted = 0
                AND s2.wf_module = '{$bean->module_name}'
                AND s2.deleted = 0
            ORDER BY e.sort
            ";
        }

        $qr = $db->query($q);
        $res = array();
        while ($row = $db->fetchByAssoc($qr, false)) {
            $enabled = true;
            if($row['filter_function'] && !self::checkBeanAgainstFunction($bean, $row['filter_function'], $row)) {
                $enabled = false;
            }
            if($enabled)
                $res[] = $row;
        }
        return $res;
    }

    public static function getAllStatuses($bean) {
        if(!$bean)
            return array();
        if(isset(self::$allStatusesCache[$bean->module_name]))
            return self::$allStatusesCache[$bean->module_name];

        $q = "SELECT uniq_name, name FROM wf_statuses
        WHERE
            wf_module = '{$bean->module_name}'
            AND deleted = 0
        ";

        $qr = $bean->db->query($q);
        $res = array();
        while ($row = $bean->db->fetchByAssoc($qr)) {
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

    public static function validateEvent($bean, $status1, $status2) {
        global $db;

        if($status1 == $status2) {
            return array();
        }

        $q = "SELECT e.id, e.validate_function
            , s1.uniq_name AS s1_uniq_name
            , s2.uniq_name AS s2_uniq_name
            , e.func_params
        FROM wf_events e, wf_statuses s1, wf_statuses s2
        WHERE e.workflow_id = '{$bean->wf_id}'
            AND e.status1_id = s1.id
            AND e.status2_id = s2.id
            AND e.validate_function IS NOT NULL
            AND e.deleted = 0
            AND s1.uniq_name = '$status1' AND s1.wf_module = '{$bean->module_name}' AND s1.deleted = 0
            AND s2.uniq_name = '$status2' AND s2.wf_module = '{$bean->module_name}' AND s2.deleted = 0
        ";
        $qr = $db->query($q);
        $res = array();
        while ($row = $db->fetchByAssoc($qr, false)) {
            if(empty($row['validate_function'])) {
                continue;
            }
            foreach(explode('^,^', trim($row['validate_function'], '^')) as $functionName) {
            if(file_exists(__DIR__.'/functions/validators/'.$functionName.'.php')) {
                require_once __DIR__.'/functions/BaseValidator.php';
                require_once __DIR__.'/functions/validators/'.$functionName.'.php';
                $func = new $functionName;
                $func->event_id = $row['id'];
                $func->status1_data = array(
                    'uniq_name' => $row['s1_uniq_name'],
                );
                $func->status2_data = array(
                    'uniq_name' => $row['s2_uniq_name'],
                );
                if(empty($row['func_params'])) {
                    $func->func_params = array();
                }
                else {
                    $func->func_params = json_decode($row['func_params'], true);
                    if(json_last_error() != JSON_ERROR_NONE) {
                        $GLOBALS['log']->error("WFManager: error parsing func_params = {$row['func_params']}, event_id = {$row['id']}, error = ".json_last_error());
                        return array("Error parsing func_params");
                    }
                }
                $errors = $func->validate($bean);
                $res = array_merge($res, $errors);
            }
            elseif($functionName) {
                $GLOBALS['log']->error("WFManager: validate function $functionName not found");
                $errors = array(wf_translate('ERR_VALIDATE_FUNCTION_NOT_FOUND'));
                $res = array_merge($res, $errors);
            }
            }
        }
        return $res;
    }

    public static function canChangeStatus($bean, $status1) {
        global $current_user;
        if(isset($bean->workflowData['autosave']) && $bean->workflowData['autosave'] === true)
            return true;
        if(is_admin($current_user))
            return true;
        return
            array_key_exists($current_user->id, self::getUserList($bean, $status1, 'confirm_check_list_function')) &&
            array_key_exists($current_user->id, self::getUserList($bean, $status1, 'confirm_list_function'));
    }

    public static function isInFrontAssignedUsers($user_id, $bean, $status1) {
        return array_key_exists($user_id, self::getUserList($bean, $status1, 'front_assigned_list_function'));
    }

    public static function getFrontAssignedUserList($bean, $status) {
        return self::getUserList($bean, $status, 'front_assigned_list_function');
    }

    public static function isInConfirmUsers($user_id, $bean, $status1) {
        return array_key_exists($user_id, self::getUserList($bean, $status1, 'confirm_list_function'));
    }

    public static function canChangeAssignedUser($bean, $status) {
        global $current_user;
        if(isset($bean->workflowData['autosave']) && $bean->workflowData['autosave'] === true)
            return true;
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
        if(empty($bean->wf_id))
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

        $cur_date = $timedate->handle_offset(gmdate($timedate->get_db_date_time_format()), 'd.m.Y H:i', 'd.m.Y H:i', $current_user, 'Europe/Moscow') . " (МСК)";
        $confirm_text = '';
        $confirm_text .= 'Перевод на "'.
            self::translateStatus($status2, $bean->module_name) . '", '.
            $cur_date . ', '.
            $current_user->full_name.
            (isset($bean->workflowData['autosave']) && $bean->workflowData['autosave'] === true ? ' (автопереход)' : '').
            (isset($bean->last_resolution) && $bean->last_resolution ? ' -- ' . $bean->last_resolution : '').
            '; ';
        $bean->confirm_list = $confirm_text . (isset($bean->confirm_list) ? $bean->confirm_list : '');

        if($saveBean) {
            $oldValue = !empty($bean->skipValidationHooks);
            $bean->skipValidationHooks = true;
            $bean->save();
            $bean->skipValidationHooks = $oldValue;
        }
    }

    /**
     * Добавляет сообщение в журнал согласования записи $toBean из $fromBean.
     * В текущей реализации эту функцию следует вызывать сразу после
     * сохранения новой резолюции $fromBean под этим же пользователем.
     */
    public static function copyLastLog($fromBean, $toBean, $saveBean = true) {
        global $timedate;
        global $current_user;
        $statusField = self::getBeanStatusField($fromBean);
        $status2 = $statusField ? $fromBean->$statusField : '';
        $cur_date = $timedate->handle_offset(gmdate($timedate->get_db_date_time_format()), 'd.m.Y H:i', 'd.m.Y H:i', $current_user, 'Europe/Moscow') . " (МСК)";
        $confirm_text = 'Перевод на "'.
            ($status2 ? self::translateStatus($status2, $fromBean->module_name) : '_') . '", '.
            $cur_date . ', '.
            $current_user->full_name.
            (isset($fromBean->workflowData['autosave']) && $fromBean->workflowData['autosave'] === true ? ' (автопереход)' : '').
            (isset($fromBean->last_resolution) && $fromBean->last_resolution ? ' -- ' . $fromBean->last_resolution : '').
            '; ';
        $toBean->confirm_list = $confirm_text . (isset($toBean->confirm_list) ? $toBean->confirm_list : '');
        if($saveBean) {
            $oldValue = !empty($toBean->skipValidationHooks);
            $toBean->skipValidationHooks = true;
            $toBean->save();
            $toBean->skipValidationHooks = $oldValue;
        }
    }

    protected static function logFinalStatusToArchive($status, $module) {
        $finalStatuses = self::getFinalStatuses($module);
            if(in_array($status, $finalStatuses)) {
                return 'Перевод задачи в архив ';
            }
        else {
            return '';
        }
    }

    public static function logAssignedChange($bean, $status1, $assigned2, $saveBean = true, $role_id = null) {
        global $db;
        global $timedate;
        global $current_user;
        global $app_list_strings;

        $role_name = null;
        if($role_id === null) {
            $q = "SELECT s.role_id, r.name FROM wf_statuses s, acl_roles r WHERE s.role_id = r.id AND s.id = '$status1'";
            $row = $db->fetchOne($q);
            if($row) {
                $role_id = $row['role_id'];
                $role_name = $row['name'];
            }
        }

        $assigned1_name = '';
        if($role_id) {
            $userList = WFStatusAssigned::getAssignedUsers($role_id, $bean->id, $bean->module_name);
            if(count($userList) == 1) {
                $assigned1_user = reset($userList);
                if($assigned1_user->id == $assigned2) {
                    return;
                }
            }

            foreach($userList as $user) {
                if($assigned1_name) {
                    $assigned1_name .= ', ';
                }
                $assigned1_name .= $user->first_name.' '.$user->last_name;
                $assigned1 = $user->id;
            }

            if(!$role_name) {
                $q = "SELECT name FROM acl_roles WHERE id = '$role_id'";
                $row = $db->fetchOne($q);
                if($row) {
                    $role_name = $row['name'];
                }
            }
        }

        $assigned2_user = BeanFactory::getBean('Users', $assigned2);
        $assigned2_name = $assigned2_user ? $assigned2_user->full_name : '-';

        $cur_date = $timedate->handle_offset(gmdate($timedate->get_db_date_time_format()), 'd.m.Y H:i', 'd.m.Y H:i', $current_user, 'Europe/Moscow') . " (МСК)";

        $confirm_text = "{$assigned2_name} установлен как ответственный для роли '{$role_name}', ";
        if($assigned1_name) {
            $confirm_text .= "предыдущий ответственный $assigned1_name, ";
        }
        $confirm_text .= $cur_date . ', '.
            $current_user->full_name.
            '; ';

        $bean->confirm_list = $confirm_text . (isset($bean->confirm_list) ? $bean->confirm_list : '');

        if($saveBean) {
            $oldValue = !empty($bean->skipValidationHooks);
            $bean->skipValidationHooks = true;
            $bean->save();
            $bean->skipValidationHooks = $oldValue;
        }
    }

    public static function runAfterEventHooks($bean, $status1, $status2) {
        global $db;

        $q = "SELECT e.after_save
                    , s1.role_id AS s1_role_id
                    , s2.role_id AS s2_role_id
                    , e.func_params
                    , e.id
          FROM wf_events e, wf_statuses s1, wf_statuses s2
          WHERE
                e.status1_id = s1.id AND s1.uniq_name='{$status1}' AND s1.wf_module = '{$bean->module_name}' AND s1.deleted = 0
            AND e.status2_id = s2.id AND s2.uniq_name='{$status2}' AND s2.wf_module = '{$bean->module_name}' AND s2.deleted = 0
            AND e.workflow_id = '{$bean->wf_id}'
            AND e.after_save IS NOT NULL
            AND e.deleted = 0
        ";

        $res = $db->query($q);
        while ($row = $db->fetchByAssoc($res, false)) {
            if(!$row['after_save']) {
                continue;
            }
            foreach(explode('^,^', trim($row['after_save'], '^')) as $procName) {
                require_once __DIR__.'/functions/BaseProcedure.php';
                require_once 'custom/include/Workflow/functions/procedures/'.$procName.'.php';
                $proc = new $procName;
                $proc->event_id = $row['id'];
                $proc->status1_data = array(
                    'uniq_name' => $status1,
                    'role_id' => $row['s1_role_id'],
                );
                $proc->status2_data = array(
                    'uniq_name' => $status2,
                    'role_id' => $row['s2_role_id'],
                );
                if(empty($row['func_params'])) {
                    $proc->func_params = array();
                }
                else {
                    $proc->func_params = json_decode($row['func_params'], true);
                    if(json_last_error() != JSON_ERROR_NONE) {
                        $GLOBALS['log']->error("WFManager: error parsing func_params = {$row['func_params']}, event_id={$row['id']}, error = ".json_last_error());
                    }
                }
                $proc->doWork($bean);
            }
        }
    }

    public static function autoFillAssignedUser($bean, $status1) {
        global $current_user;
        if(isset($bean->workflowData['autosave']) && $bean->workflowData['autosave'] === true)
            return;
        $statusBean = self::getStatusBeanByName($status1, $bean->module_name);
        if(!$statusBean) {
            $GLOBALS['log']->error("WFManager: status {$status1} not found for {$bean->module_name} {$bean->id}");
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
            $data['wf_id'] = $bean->wf_id;
            $status1 = $bean->$statusField;
            $statusBean = self::getStatusBeanByName($status1, $bean->module_name);

            $statusesEvents = self::getNextStatusesEvents($bean, $status1);
            $statuses = array();
            $resolutionRequiredData = array();
            foreach($statusesEvents as $row) {
                $statuses[$row['uniq_name']] = $row['name'];
                if(empty($resolutionRequiredData[$row['uniq_name']])) {
                    $resolutionRequiredData[$row['uniq_name']] = $row['resolution_required'];
                }
            }
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
            
            $data['currentStatus'] = $status1;
            $data['include_script'] = self::getVersionedScript();
            if(!empty($statuses)) {
                $data['confirmData'] = array(
                    'formName' => 'confirmForm',
                    'newStatuses' => $statuses,
                    'assignedUsersData' => $assignedUsersData,
                    'resolutionRequiredData' => $resolutionRequiredData,
                );
            }
            $data['assignedUsers'] = $assignedUsersData;
            $data['roles'] = $roles;
            $data['assignFormName'] = 'assignForm';
            $data['confirmUsersString'] = json_encode($confirmUsersData);
            $data['currentRole'] = $statusBean ? $statusBean->role_id : false;
            $data['statusAssignedUsers'] = $statusAssignedUsers;

            /* Кастомный код внутри блока согласования перед сабмитом */
            if(!empty($data['confirmData']['newStatuses']) && empty($bean->workflowData['skipWFHooks'])) {
                $logicHook = new LogicHook();
                $logicHook->setBean($bean);
                ob_start();
                $logicHook->call_custom_logic($bean->module_dir, 'wf_after_confirm_fields', $data);
                $customView = ob_get_clean();
                if($customView) {
                    $data['confirmData']['customFields'] = $customView;
                }
            }

            /* Кастомный код внутри панели под всеми блоками */
            if(empty($bean->workflowData['skipWFHooks'])) {
                $logicHook = new LogicHook();
                $logicHook->setBean($bean);
                ob_start();
                $logicHook->call_custom_logic($bean->module_dir, 'wf_after_editform', $data);
                $customView = ob_get_clean();
                if($customView) {
                    $data['customView'] = $customView;
                }
            }
        }
        return $data;
    }

    public static function getVersionedScript() {
        require_once __DIR__.'/config.php';
        global $wf_config;
        return getVersionedScript('custom/include/Workflow/js/wf_ui.js', $wf_config['js_custom_version']);
    }

    public static function getStatusesWithRole($role_id, $wf_id) {
        global $db;
        //$q = "SELECT uniq_name FROM wf_statuses WHERE role_id = '$role_id' AND deleted = 0";
        $q = "SELECT DISTINCT s.uniq_name FROM wf_statuses s, wf_events e
            WHERE s.role_id = '$role_id'
                AND s.deleted = 0
                AND e.deleted = 0
                AND e.workflow_id = '$wf_id'
                AND (e.status1_id = s.id OR e.status2_id = s.id)";
        $qr = $db->query($q);
        $statuses = array();
        while($row = $db->fetchByAssoc($qr)) {
            $statuses[] = $row['uniq_name'];
        }
        return $statuses;
    }

    /**
     * Возвращает статусы маршрута, которые идут после пустого.
     * Из начального статуса должен существовать переход в следующий статус.
     */
    public static function getFirstNonEmptyStatuses($wf_id) {
        global $db;
        $q = "SELECT DISTINCT s2.uniq_name
            FROM wf_events e12
            INNER JOIN wf_statuses s2 ON s2.id = e12.status2_id
            INNER JOIN wf_events e23 ON s2.id = e23.status1_id
            WHERE
                (e12.status1_id IS NULL OR e12.status1_id = '')
                AND e23.workflow_id = '{$wf_id}'
                AND e12.deleted = 0
                AND e23.deleted = 0
            ";
        $qr = $db->query($q);
        $statuses = array();
        while($row = $db->fetchByAssoc($qr)) {
            $statuses[] = $row['uniq_name'];
        }
        return $statuses;
    }

    public static function checkWorkflows() {
        global $db;
        $result = array();
/* Статусы в пределах одного маршрута с одной ролью, но с разными значениями assigned_list_function, confirm_list_function, role2_id */
        $q =
"SELECT w.id as workflow_id, w.name as workflow_name, r.id as role_id, r.name as role_name, st.id as status_id, st.name as status_name FROM (
    SELECT workflow_id, role_id FROM (
        SELECT e.workflow_id, s.role_id, s.assigned_list_function, s.confirm_list_function, s.role2_id
        FROM wf_statuses s, wf_events e
        WHERE
           s.deleted = 0 AND e.deleted = 0
            AND (e.status1_id = s.id OR e.status2_id = s.id)
        GROUP BY e.workflow_id, s.role_id, s.assigned_list_function, s.confirm_list_function, s.role2_id
    ) role_st
    GROUP BY workflow_id, role_id
    HAVING count(*) > 1
) w_r,
wf_workflows w,
acl_roles r,
wf_statuses st
WHERE w_r.workflow_id = w.id AND w_r.role_id = r.id
    AND w.deleted = 0 AND r.deleted = 0
    AND st.deleted = 0 AND st.role_id = w_r.role_id
    AND st.id IN (
            SELECT status1_id FROM wf_events e WHERE e.deleted = 0 AND e.workflow_id = w.id
            UNION ALL
            SELECT status2_id FROM wf_events e WHERE e.deleted = 0 AND e.workflow_id = w.id
        )
ORDER BY w.name, r.name";
        $dbRes = $db->query($q);
        while($row = $db->fetchByAssoc($dbRes)) {
            $result['wf_st_roles_conflict'][] = $row;
        }

/* Уникальность uniq_name в маршрутах */
        $q =
"SELECT w.name, w.id, w.uniq_name FROM (
    SELECT uniq_name FROM wf_workflows
    WHERE deleted = 0
    GROUP BY uniq_name
    HAVING count(*) > 1
) d,
wf_workflows w
WHERE w.deleted = 0 AND w.uniq_name = d.uniq_name
ORDER BY w.uniq_name
";
        $dbRes = $db->query($q);
        while($row = $db->fetchByAssoc($dbRes)) {
            $result['wf_w_uniq'][] = $row;
        }

/* Уникальность uniq_name, wf_module в статусах */
        $q =
"SELECT s.name, s.id, s.wf_module FROM (
    SELECT uniq_name, wf_module FROM wf_statuses
    WHERE deleted = 0
    GROUP BY uniq_name, wf_module
    HAVING count(*) > 1
) d,
wf_statuses s
WHERE s.deleted = 0 AND s.uniq_name = d.uniq_name AND s.wf_module = d.wf_module
ORDER BY s.wf_module, s.uniq_name
";
        $dbRes = $db->query($q);
        while($row = $db->fetchByAssoc($dbRes)) {
            $result['wf_st_uniq'][] = $row;
        }

/* Уникальность переходов */
        $q =
"SELECT w.id AS workflow_id, w.name AS workflow_name, e.id, st1.name AS status1_name, st2.name AS status2_name FROM (
    SELECT s1.uniq_name AS status1, s1.wf_module AS status1_module, s2.uniq_name AS status2, s2.wf_module AS status2_module, e.workflow_id
    FROM wf_events e, wf_statuses s1, wf_statuses s2
    WHERE e.status1_id = s1.id AND e.status2_id = s2.id
        AND e.deleted = 0
    GROUP BY s1.uniq_name, s1.wf_module, s2.uniq_name, s2.wf_module, e.workflow_id
    HAVING count(*) > 1
) d,
wf_events e, wf_statuses st1, wf_statuses st2, wf_workflows w
WHERE d.workflow_id = e.workflow_id AND e.deleted = 0
    AND st1.id = e.status1_id AND st2.id = e.status2_id
    AND st1.uniq_name = d.status1 AND st1.wf_module = d.status1_module AND st1.deleted = 0
    AND st2.uniq_name = d.status2 AND st2.wf_module = d.status2_module AND st2.deleted = 0
    AND e.workflow_id = w.id
ORDER BY w.name, st1.name, st2.name
";
        $dbRes = $db->query($q);
        while($row = $db->fetchByAssoc($dbRes)) {
            $result['wf_events_uniq'][] = $row;
        }

/* Статусы без переходов в них */
        $q =
"SELECT s2.id, s2.name, s2.uniq_name, s2.wf_module FROM wf_statuses s2
WHERE NOT EXISTS (
        SELECT 1
        FROM wf_events e
        LEFT JOIN wf_statuses s1 ON e.status1_id = s1.id AND s1.deleted = 0
        WHERE e.status2_id = s2.id AND e.deleted = 0
            AND (e.status1_id IS NULL OR e.status1_id = '' OR s1.id IS NOT NULL)
    )
    AND s2.deleted = 0
";
        $dbRes = $db->query($q);
        while($row = $db->fetchByAssoc($dbRes)) {
            $result['wf_statuses_without_events'][] = $row;
        }

/* Несоответствие признака isfinal */
//SELECT s1.*, 1 as isfinal_mustbe FROM wf_statuses s1
//WHERE NOT EXISTS (
//        SELECT 1
//        FROM wf_events e
//        LEFT JOIN wf_statuses s2 ON e.status2_id = s2.id AND s2.deleted = 0
//        WHERE e.status1_id = s1.id AND e.deleted = 0
//            AND s2.id IS NOT NULL
//    )
//    AND s1.deleted = 0
//    AND s1.isfinal != 1
//UNION ALL
//SELECT s1.*, 0 as isfinal_mustbe FROM wf_statuses s1
//WHERE EXISTS (
//        SELECT 1
//        FROM wf_events e
//        LEFT JOIN wf_statuses s2 ON e.status2_id = s2.id AND s2.deleted = 0
//        WHERE e.status1_id = s1.id AND e.deleted = 0
//            AND s2.id IS NOT NULL
//    )
//    AND s1.deleted = 0
//    AND s1.isfinal != 0

        return $result;
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
                $GLOBALS['log']->error("WFManager: user list function $functionName not found");
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
        $q = "SELECT DISTINCT s.uniq_name, s.role_id, r.name AS role_name
        FROM wf_statuses s, acl_roles r, wf_events e
        WHERE
            s.wf_module='{$bean->module_name}' AND s.deleted = 0
            AND s.role_id = r.id
            AND r.deleted = 0
            AND e.deleted = 0 AND (e.status1_id = s.id OR e.status2_id = s.id)
            AND e.workflow_id = '{$bean->wf_id}'";
        $qr = $db->query($q);
        $res = array();
        $rolesPermissions = array();
        while($row = $db->fetchByAssoc($qr)) {
            if(isset($rolesPermissions[$row['role_id']]) && $rolesPermissions[$row['role_id']] === false) {
                continue;
            }
            if(self::canChangeAssignedUser($bean, $row['uniq_name'])) {
                $res[$row['role_id']]['role_name'] = $row['role_name'];
                $users = self::getUserList($bean, $row['uniq_name'], 'confirm_list_function');
                $res[$row['role_id']]['users'] = isset($res[$row['role_id']]['users']) ? array_intersect_key($users, $res[$row['role_id']]['users']) : $users;
                $GLOBALS['log']->debug('WFManager getAllowedRolesData '.$row['uniq_name'].' '.$row['role_name'].' add');
            }
            else {
                $rolesPermissions[$row['role_id']] = false;
                unset($res[$row['role_id']]);
                $GLOBALS['log']->debug('WFManager getAllowedRolesData '.$row['uniq_name'].' '.$row['role_name'].' unset');
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

    public static function getStatusIdByName($status, $module_name) {
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

    protected static function checkBeanAgainstFunction($bean, $filter_function, $event_data) {
        require_once 'custom/include/Workflow/functions/filters/'.$filter_function.'.php';
        $filter = new $filter_function();
        $filter->event_data = $event_data;
        if(empty($event_data['func_params'])) {
            $filter->func_params = array();
        }
        else {
            $filter->func_params = json_decode($event_data['func_params'], true);
            if(json_last_error() != JSON_ERROR_NONE) {
                $GLOBALS['log']->error("WFManager: error parsing func_params = {$event_data['func_params']}, status2 = {$event_data['uniq_name']}, error = ".json_last_error());
            }
        }
        return $filter->checkBean($bean);
    }

    public static function getFinalStatuses($module) {
        global $db;
        $row = $db->query("SELECT uniq_name FROM wf_statuses WHERE wf_module = '{$module}' AND isfinal = 1 AND deleted = 0");
        $finStatuses = array();
        while ($res = $db->fetchByAssoc($row)) {
            $finStatuses[] = $res['uniq_name'];
        }
        return $finStatuses;
    }
}
?>
