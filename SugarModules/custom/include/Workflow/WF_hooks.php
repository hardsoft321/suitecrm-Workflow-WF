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
            WFManager::autoFillAssignedUser($focus, $status1);
        }
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
