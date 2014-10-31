<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once ('custom/include/Workflow/WFMassUpdate.php');

$out = array();
$errors = array();
if($_REQUEST['checkedRecords']['mode'] == 'entire') {
    $errors[] = "Перевод всех записей не поддерживается. Пожалуйcта, выберите записи, находящиеся на одном статусе.";
}
else {
    $massUpdate = new WFMassUpdate();
    $massUpdate->setBeans($_REQUEST['module'], $_REQUEST['checkedRecords']['items']);
    
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
