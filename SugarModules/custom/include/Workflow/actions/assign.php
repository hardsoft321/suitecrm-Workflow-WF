<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $db;

require_once 'custom/include/Workflow/WFManager.php';
require_once 'custom/include/Workflow/WFStatusAssigned.php';
require_once 'custom/include/Workflow/utils.php';

$bean = BeanFactory::getBean($_POST['module'], $_POST['record']);
if (empty($bean->id))
    wf_assign_die('ERR_RECORD_NOT_FOUND');

$statusField = WFManager::getBeanStatusField($bean);
if(!$statusField) {
    wf_assign_die('ERR_STATUS_FIELD_NOT_FOUND', $bean);
}
$status1 = $bean->$statusField;

$status_id = '';
$role_id = '';
if(strpos($_POST['role'], 'status_') === 0) {
    $status_id = substr($_POST['role'], strlen('status_'));
    $roleStatusBean = BeanFactory::getBean('WFStatuses', $status_id);
    if(!$roleStatusBean) {
        wf_assign_die('ERR_STATUS_NOT_FOUND', $bean);
    }
    $status = $roleStatusBean->uniq_name;
    $roleStatuses = array($status);
}
else {
    $role_id = $db->quote($_POST['role']);
    $roleStatusBean = BeanFactory::newBean('WFStatuses');
    $roleStatusBean->role_id = $role_id;
    $roleStatuses = WFManager::getStatusesWithRole($role_id, $bean->wf_id);
}

$assigned2 = $db->quote($_POST['new_assign_user']);

if(empty($roleStatuses)) {
    wf_assign_die('ERR_ROLE_STATUS_NOT_FOUND', $bean);
}
foreach($roleStatuses as $st) {
    if(!WFManager::canChangeAssignedUser($bean, $st)) { 
        $GLOBALS['log']->fatal("WFWorkflow assign: check against $st status...");
        wf_assign_die('ERR_ASSIGN_DENIED', $bean);
    }
    if(!WFManager::isInConfirmUsers($assigned2, $bean, $st)) {
        $GLOBALS['log']->fatal("WFWorkflow assign: check against $st status...");
        wf_assign_die('ERR_INVALID_ASSIGNED', $bean);
    }
}

if(in_array($status1, $roleStatuses)) {
    $notify_on_save = false;
    if($assigned2 != $bean->assigned_user_id && $assigned2 != $GLOBALS['current_user']->id && empty($GLOBALS['sugar_config']['exclude_notifications'][$bean->module_dir])) {
        $notify_on_save = true;
    }
    $bean->assigned_user_id = $assigned2;
    $bean->skipValidationHooks = true;
    $bean->save($notify_on_save);
}

WFManager::logAssignedChange($bean, $status1, $assigned2, true, $roleStatusBean);
WFStatusAssigned::setAssignedUser($roleStatusBean, $bean->id, $bean->module_name, $assigned2);

$url = "index.php?action={$_POST['return_action']}&module={$_POST['return_module']}&record={$_POST['return_record']}";
header("Location: $url");
?>
