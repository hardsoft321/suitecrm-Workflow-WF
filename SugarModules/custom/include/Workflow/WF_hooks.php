<?php
class WF_hooks {

  protected static $fetchedRow = array();
  
  function after_entry_point ($event) {
    require_once ('custom/include/Workflow/utils.php');
  }

  function before_save (&$focus, $event) {
    if(isset($focus->workflowData['skipWFHooks']) && $focus->workflowData['skipWFHooks'] === true)
        return;
    require_once ('custom/include/Workflow/WFManager.php');
    require_once ('custom/include/Workflow/utils.php');
    
    $focus->wf_id = $this->getNewWfId($focus);
    
    $statusField = WFManager::getBeanStatusField($focus);
    if($statusField) {
        $status1 = empty($focus->fetched_row['id']) ? '' : $focus->fetched_row[$statusField];
        if($focus->fetched_row['wf_id'] != $focus->wf_id) {
            $possibleFirstStatuses = WFManager::getFirstNonEmptyStatuses($focus->wf_id);
            /* Сами установим первый статус */
            $focus->$statusField = !empty($possibleFirstStatuses) && !in_array($focus->$statusField, $possibleFirstStatuses) ? reset($possibleFirstStatuses) : $focus->$statusField;
            $status1 = '';
        }
        $status2 = $focus->$statusField;
        $assigned1 = empty($focus->fetched_row['id']) ? '' : $focus->fetched_row['assigned_user_id'];
        $assigned2 = isset($focus->assigned_user_id) ? $focus->assigned_user_id : '';
        if($status1 != '' && $status1 != $status2) {
            if(!WFManager::isEventAllowed($focus, $status1, $status2)) {
                wf_before_save_die('ERR_INVALID_EVENT', $focus);
            }
            
            $validationErrors = WFManager::validateEvent($focus, $status1, $status2);
            if(!empty($validationErrors)) {
                require_once __DIR__.'/WFEventValidationException.php';
                throw new WFEventValidationException($validationErrors);
            }

            if(!empty($focus->fetched_row['id']) && (!isset($focus->workflowData['autosave']) || $focus->workflowData['autosave'] !== true)) {
                if(!WFManager::canChangeStatus($focus, $status1)) {
                    wf_before_save_die('ERR_CONFIRM_DENIED', $focus);
                }
                
                if(!WFManager::isInFrontAssignedUsers($assigned2, $focus, $status2)) {
                    wf_before_save_die('ERR_INVALID_ASSIGNED', $focus);
                }
            }
            
            WFManager::logStatusChange($focus, $status1, $status2, false);
        }
        else {
            if(!empty($focus->fetched_row['id']) && $assigned1 != $assigned2) {
                if(!WFManager::canChangeAssignedUser($focus, $status1)) { 
                    wf_before_save_die('ERR_ASSIGN_DENIED', $focus);
                }
                if(!WFManager::isInConfirmUsers($assigned2, $focus, $status1)) {
                    wf_before_save_die('ERR_INVALID_ASSIGNED', $focus);
                }
            }
        }
        
        if ( !empty($focus->id) ) {
            self::$fetchedRow[$focus->id] = $focus->fetched_row;
        }
    }
  }
  
  function after_save (&$focus, $event) {
    if(isset($focus->workflowData['skipWFHooks']) && $focus->workflowData['skipWFHooks'] === true)
        return;
    require_once ('custom/include/Workflow/WFManager.php');
    
    $statusField = WFManager::getBeanStatusField($focus);
    if($statusField) {
        $status1 = isset(self::$fetchedRow[$focus->id]) ? self::$fetchedRow[$focus->id][$statusField] : '';
        $status2 = $focus->$statusField;
        if($status1 != $status2) {
            WFManager::runAfterEventHooks($focus, $status1, $status2);
            if($status1) {
                WFManager::autoFillAssignedUser($focus, $status1);
            }
        }
    }
  }

    function assignCreator($focus)
    {
        $isNew = !empty($focus->date_entered);
        if (!$isNew) {
            return;
        }
        require_once 'custom/include/Workflow/WFManager.php';
        require_once 'custom/include/Workflow/WFStatusAssigned.php';
        $statusField = WFManager::getBeanStatusField($focus);
        if(!$statusField) {
            $GLOBALS['log']->error("WF_hooks::assignCreator: no status field for {$focus->module_name} {$focus->id}");
            return;
        }
        $statusBean = WFManager::getStatusBeanByName($focus->$statusField, $focus->module_name);
        if (!$statusBean) {
            $GLOBALS['log']->error("WF_hooks::assignCreator: no status bean for {$focus->module_name} {$focus->id}");
            return;
        }
        $user_id = !empty($focus->assigned_user_id) ? $focus->assigned_user_id : $GLOBALS['current_user']->id;
        WFStatusAssigned::setAssignedUser($statusBean, $focus->id, $focus->module_name, $user_id);
    }

    /**
     * Хук панели выбора пользователей для уведомления при смене статуса
     */
    public function displayNotificationFields($bean, $event, $arguments)
    {
        require_once 'custom/include/NotificationCopy/NotificationCopy.php';
        $formName = $arguments['confirmData']['formName'];
        echo '<tr class="assigned_copy">
<td><label>'.translate("LBL_RECIPIENT_LIST", "WFWorkflows").':</label></td>
<td>';
        $params = array(
            'show_just_store' => true,
            'hide_label' => true,
        );
        echo NotificationCopy::getFormFieldHtml($bean, $formName, $params);
        echo '</td></tr>';
    }

    /**
     * Выводит js-код для смены статуса в зависимости от типа.
     * Запускать после parent::display edit-формы.
     * Опции для поля статуса в $GLOBALS['app_list_strings'] должны содержать все статусы (маршрутные и не маршрутные).
     */
    public static function displayEditViewJs($bean, $statusField)
    {
        global $app_list_strings, $db, $current_language;
        require_once 'custom/include/Workflow/WFManager.php';
        $typeField = WFManager::getWorkflowTypeField($bean);
        if(!$typeField) {
            return;
        }

      if(empty($bean->fetched_row['id']) || !WFManager::isBeanInWorkflow($bean)) {
        $allFirstStatuses = null;
        if(empty($allFirstStatuses))
        {
            $allFirstStatuses = array();
            $q = "SELECT DISTINCT s2.uniq_name, s2.name, w.bean_type
            FROM wf_events e12
            INNER JOIN wf_statuses s2 ON s2.id = e12.status2_id
            INNER JOIN wf_events e23 ON s2.id = e23.status1_id
            INNER JOIN wf_workflows w ON e23.workflow_id = w.id
            WHERE
                (e12.status1_id IS NULL OR e12.status1_id = '')
                AND e12.deleted = 0
                AND e23.deleted = 0
                AND w.deleted = 0 AND w.wf_module = 'Tasks'";
            $dbRes = $db->query($q);
            while($row = $db->fetchByAssoc($dbRes)) {
                $allFirstStatuses[$row['uniq_name']]['name'] = $row['name'];
                $allFirstStatuses[$row['uniq_name']]['class'] = (empty($allFirstStatuses[$row['uniq_name']]['class']) ? '' : $allFirstStatuses[$row['uniq_name']]['class'])
                    .' '.implode(' ', explode('^,^', trim($row['bean_type'], '^')));
            }
            $allStatuses = $app_list_strings[$bean->field_defs[$statusField]['options']];
            $wfStatuses = WFManager::getAllStatuses($bean);
            $notWfStatuses = array_diff_key($allStatuses, $wfStatuses);
            foreach($notWfStatuses as $key => $name) {
                $allFirstStatuses[$key]['name'] = $name;
                $allFirstStatuses[$key]['class'] = (empty($allFirstStatuses[$key]['class']) ? '': $allFirstStatuses[$key]['class']).' no-wf';
            }
        }
        $statusesHtml = '';
        foreach($allFirstStatuses as $uniq_name => $params) {
            $statusesHtml .= "<option value='{$uniq_name}' class='{$params['class']}'>".htmlspecialchars($params['name'])."</option>";
        }
        echo '<script>
var statusSelect = $("select#'.$statusField.'");
statusSelect.html("'.$statusesHtml.'");
var typeField ="'.$typeField.'";';
        echo <<<'SCRIPT'
$('select#'+typeField).change(function() {
  statusSelect.find('option').attr('disabled', 'disabled').hide();
  if(!this.value || !statusSelect.find('option.'+this.value).removeAttr('disabled').show().length) {
    statusSelect.find('option.no-wf').removeAttr('disabled').show();
  }
  var selected = statusSelect.find('option[value="'+statusSelect.val()+'"]');
  if(!selected.length || selected.is(':disabled')) {
    statusSelect.val(statusSelect.find('option').not(':disabled').val());
  }
}).change();
</script>
SCRIPT;
      }
      else if(WFManager::isBeanInWorkflow($bean)) {
        $res = array();
        $status = WFManager::getBeanCurrentStatus($bean);
        if($status) {
            $res[$status->uniq_name] = $status->name;
        }
        $res = array_merge($res, WFManager::getNextStatuses($bean));
        $statusesHtml = '';
        foreach($res as $uniq_name => $name) {
            $statusesHtml .= "<option value='{$uniq_name}'>".htmlspecialchars($name)."</option>";
        }
        echo '<script>
$("select#'.$statusField.'").html("'.$statusesHtml.'");
</script>';
      }
    }

  protected function getNewWfId($focus) {
    if(empty($focus->fetched_row['id'])) {
        return WFManager::getWorkflowForBean($focus);
    }
    $typeField = WFManager::getWorkflowTypeField($focus);
    if($focus->fetched_row[$typeField] != $focus->$typeField) {
        if(!WFManager::isBeanInWorkflow($focus)) {
            return WFManager::getWorkflowForBean($focus, $typeField);
        }
        $statusField = WFManager::getBeanStatusField($focus);
        $possibleFirstStatuses = WFManager::getFirstNonEmptyStatuses($focus->wf_id);
        $status1 = $focus->fetched_row[$statusField];
        /* Смена маршрута разрешена на первом статусе */
        if(in_array($status1, $possibleFirstStatuses)) {
            return WFManager::getWorkflowForBean($focus, $typeField);
        }
    }
    return $focus->wf_id;
  }
}
?>
