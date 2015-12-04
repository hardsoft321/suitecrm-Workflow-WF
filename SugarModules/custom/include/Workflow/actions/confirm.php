<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

if(empty($GLOBALS['current_user']) || empty($GLOBALS['current_user']->id)) {
    sugar_die(translate('LBL_SESSION_EXPIRED', 'Users'));
}

require_once ('custom/include/Workflow/WFManager.php');
require_once 'custom/include/Workflow/utils.php';

$bean = BeanFactory::getBean($_POST['module'], $_POST['record']);
if (empty($bean->id))
    wf_confirm_die('ERR_RECORD_NOT_FOUND', $bean);

$statusField = WFManager::getBeanStatusField($bean);
if(!$statusField)
    wf_confirm_die('ERR_STATUS_FIELD_NOT_FOUND', $bean);
$notify_on_save = false;
if(!empty($_POST['assigned_user']) && $_POST['assigned_user'] != $bean->assigned_user_id && $_POST['assigned_user'] != $GLOBALS['current_user']->id && empty($GLOBALS['sugar_config']['exclude_notifications'][$bean->module_dir])) {
    $notify_on_save = true;
}
$bean->$statusField = $_POST['status'];
$bean->last_resolution = $_POST['resolution'];
$bean->assigned_user_id = $_POST['assigned_user'];

$errors = array();

$saved = null;
if(empty($errors)) {
    try {
        $bean->skipValidationHooks = true;
        $saved = $bean->save($notify_on_save);
        if($saved && !empty($_POST['assigned_user_copy'])) {
            require_once 'custom/include/Workflow/functions/procedures/SendNotificationCopy.php';
            $proc = new SendNotificationCopy();
            $proc->doWork($bean);
        }
    }
    catch(WFEventValidationException $ex) {
        $errors = $ex->getErrors();
    }
}

if(isset($_REQUEST['is_ajax_call']) && $_REQUEST['is_ajax_call']) {
    echo json_encode(array(
        'errors' => $errors,
        'saved' => $saved,
    ));
}
else {
    if(!empty($errors)) {
        $errMsg = '';
        foreach($errors as $err)
            $errMsg .= $err['message']."<br/>\n";
        SugarApplication::appendErrorMessage($errMsg);
    }

    $url = "index.php?action={$_POST['return_action']}&module={$_POST['return_module']}&record={$_POST['return_record']}";
    header("Location: $url");
}
?>
