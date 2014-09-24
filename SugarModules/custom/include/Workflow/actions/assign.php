<?php
global $db;
require_once ('custom/include/Workflow/WFManager.php');

$bean = BeanFactory::getBean($_POST['module'], $_POST['record']);
if (empty($bean->id))
    sugar_die ("Запись не найдена");

$role_id = $db->quote($_POST['role']);
$assigned2 = $db->quote($_POST['new_assign_user']);

$statusField = WFManager::getBeanStatusField($bean);
if(!$statusField) {
    sugar_die('Поле статуса не найдено');
}
$status1 = $bean->$statusField;

$roleStatuses = WFManager::getStatusesWithRole($role_id);
if(empty($roleStatuses)) {
    sugar_die('Статусы для указанной роли не найдены');
}
foreach($roleStatuses as $st) {
    if(!WFManager::canChangeAssignedUser($bean, $st)) { 
        sugar_die('У Вас нет прав на смену ответственного');
    }
    if(!WFManager::isInConfirmUsers($assigned2, $bean, $st)) {
        sugar_die('Указанного пользователя нельзя назначить ответственным');
    }
}

require_once 'custom/include/Workflow/WFStatusAssigned.php';
if(!WFStatusAssigned::hasAssignedUser($role_id, $bean->id, $bean->module_name, $assigned2)) {
    WFStatusAssigned::setAssignedUser($role_id, $bean->id, $bean->module_name, $assigned2);
}

if(in_array($status1, $roleStatuses)) {
    $bean->assigned_user_id = $assigned2;
    $bean->save(true);
}

$url = "index.php?action={$_POST['return_action']}&module={$_POST['return_module']}&record={$_POST['return_record']}";
header("Location: $url");
?>
