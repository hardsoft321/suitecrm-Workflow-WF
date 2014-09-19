<?php
require_once ('custom/include/Workflow/WFManager.php');

$bean = BeanFactory::getBean($_POST['module'], $_POST['record']);
if (empty($bean->id))
    sugar_die ("Запись не найдена");

$bean->assigned_user_id = $_POST['new_assign_user'];

$errors = array();

if(empty($errors)) {
    $bean->save(true);
}
else {
    $errMsg = '';
    foreach($errors as $err)
        $errMsg .= $err['message']."<br/>\n";
    SugarApplication::appendErrorMessage($errMsg);
}

$url = "index.php?action={$_POST['return_action']}&module={$_POST['return_module']}&record={$_POST['return_record']}";
header("Location: $url");
?>
