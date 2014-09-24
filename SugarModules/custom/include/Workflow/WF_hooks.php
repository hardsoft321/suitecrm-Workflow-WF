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
    if(empty($focus->fetched_row['id'])) {       
        $focus->wf_id = WFManager::getWorkflowForBean($focus);
    }
    $statusField = WFManager::getBeanStatusField($focus);
    if($statusField) {
        $status1 = empty($focus->fetched_row['id']) ? '' : $focus->fetched_row[$statusField];
        $status2 = $focus->$statusField;
        $assigned1 = empty($focus->fetched_row['id']) ? '' : $focus->fetched_row['assigned_user_id'];
        $assigned2 = $focus->assigned_user_id;
        if($status1 != $status2) {
            if(!WFManager::isEventAllowed($focus, $status1, $status2)) {
                sugar_die('Status changing is not allowed');
            }
            
            if(!empty($focus->fetched_row['id'])) {
                if(!WFManager::canChangeStatus($focus, $status1)) {
                    sugar_die('Access Denied');
                }
                
                if(!WFManager::isInFrontAssignedUsers($assigned2, $focus, $status2)) {
                    sugar_die('Ответственный задан не верно');
                }
            }
            
            WFManager::logStatusChange($focus, $status1, $status2, false);
        }
        else {
            if(!empty($focus->fetched_row['id']) && $assigned1 != $assigned2) {
                if(!WFManager::canChangeAssignedUser($focus, $status1)) { 
                    sugar_die('У Вас нет прав на смену ответственного');
                }
                if(!WFManager::isInConfirmUsers($assigned2, $focus, $status1)) {
                    sugar_die('Указанного пользователя нельзя назначить ответственным');
                }
            }
        }
    }
    
    if ( !empty($focus->id) ) {
        self::$fetchedRow[$focus->id] = $focus->fetched_row;
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
}
?>
