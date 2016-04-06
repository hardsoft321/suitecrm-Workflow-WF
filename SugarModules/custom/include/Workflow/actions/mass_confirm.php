<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

if(empty($_SESSION['authenticated_user_id'])) {
    sugar_die(translate('LBL_SESSION_EXPIRED', 'Users'));
}
if(empty($GLOBALS['current_user'])) {
    $GLOBALS['current_user'] = BeanFactory::newBean('Users');
}
if(empty($GLOBALS['current_user']->id)) {
    $GLOBALS['current_user']->retrieve($_SESSION['authenticated_user_id']);
}
if(empty($GLOBALS['current_user']->id)) {
    sugar_die("User not loaded");
}

require_once ('custom/include/Workflow/WFMassUpdate.php');
require_once 'custom/include/Workflow/utils.php';

$out = array();
$errors = array();
if($_REQUEST['checkedRecords']['mode'] == 'entire') {
    $errors[] = wf_translate('ERR_ENTIRE_LIST_MASS_CONFIRM');
}
else {
    $massUpdate = new WFMassUpdate();
    $massUpdate->setBeans($_REQUEST['module'], !empty($_REQUEST['checkedRecords']['items']) ? $_REQUEST['checkedRecords']['items'] : array());
    
    if($_REQUEST['action'] == 'save') {
        $massUpdate->setNextStatus($_REQUEST['status'], $_REQUEST['assigned_user']);
    }
    
    $errors = $massUpdate->getErrors();
    
    if(empty($errors) && $_REQUEST['action'] == 'save') {
        $res = $massUpdate->saveBeans(array(
            'last_resolution' => $_REQUEST['resolution'],
        ));
        $out['saved'] = $res;
    }
    
    $bean = $massUpdate->getWorkflowBean();
    $out['editFormData'] = $bean ? WFManager::getEditFormData($bean) : array();
}
$out['errors'] = $errors;
echo json_encode($out);
