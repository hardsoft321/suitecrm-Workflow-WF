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

require_once 'custom/include/Workflow/WFManager.php';
require_once 'custom/include/Workflow/utils.php';
require_once 'include/Sugar_Smarty.php';

$bean = BeanFactory::getBean($_REQUEST['target_module'], $_REQUEST['record']);
if (empty($bean->id))
    wf_confirm_die('ERR_RECORD_NOT_FOUND', $bean);

require_once 'include/TemplateHandler/TemplateHandler.php';
$ss = new Sugar_Smarty();
$data = WFManager::getEditFormData($bean);
$ss->assign('workflow', $data);
echo $ss->display('custom/include/Workflow/tpls/ConfirmPanelBody.tpl');
