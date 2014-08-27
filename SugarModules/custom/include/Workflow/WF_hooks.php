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
        if(!empty($focus->fetched_row['id'])) {
            WFManager::checkOutRole($focus, $status1);
        }
        if($status1 != $status2) {
            WFManager::checkIsEventAllowed($focus, $status1, $status2);
            WFManager::logStatusChange($focus, $status1, $status2, false);
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
        }
    }
  }
}
?>
