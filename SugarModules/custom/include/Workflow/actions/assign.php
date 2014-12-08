<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $db;

require_once 'custom/include/Workflow/WFManager.php';
require_once 'custom/include/Workflow/WFStatusAssigned.php';
require_once 'custom/include/Workflow/utils.php';

$bean = BeanFactory::getBean($_POST['module'], $_POST['record']);
if (empty($bean->id))
    wf_assign_die('ERR_RECORD_NOT_FOUND');

$role_id = $db->quote($_POST['role']);
$assigned2 = $db->quote($_POST['new_assign_user']);

$statusField = WFManager::getBeanStatusField($bean);
if(!$statusField) {
    wf_assign_die('ERR_STATUS_FIELD_NOT_FOUND');
}
$status1 = $bean->$statusField;

$roleStatuses = WFManager::getStatusesWithRole($role_id, $bean->wf_id);
if(empty($roleStatuses)) {
    wf_assign_die('ERR_ROLE_STATUS_NOT_FOUND');
}
foreach($roleStatuses as $st) {
    if(!WFManager::canChangeAssignedUser($bean, $st)) { 
        wf_assign_die('ERR_ASSIGN_DENIED');
    }
    if(!WFManager::isInConfirmUsers($assigned2, $bean, $st)) {
        wf_assign_die('ERR_INVALID_ASSIGNED');
    }
}

if(in_array($status1, $roleStatuses)) {
    $bean->assigned_user_id = $assigned2;
    $bean->save(true);
}

WFManager::logAssignedChange($bean, $status1, $assigned2, true, $role_id);
WFStatusAssigned::setAssignedUser($role_id, $bean->id, $bean->module_name, $assigned2);

$url = "index.php?action={$_POST['return_action']}&module={$_POST['return_module']}&record={$_POST['return_record']}";
header("Location: $url");
?>
