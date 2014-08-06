<?php
require_once ('custom/include/Workflow/WFManager.php');

$bean = BeanFactory::getBean($_POST['module'], $_POST['record']);
if (empty($bean->id))
    sugar_die ("Запись не найдена");

$statusField = WFManager::getBeanStatusField($bean);
if(!$statusField)
    sugar_die('Field for status not found');
$bean->$statusField = $_POST['status'];
$bean->last_resolution = $_POST['resolution'];

$bean->save(true);

$url = "index.php?action={$_POST['return_action']}&module={$_POST['return_module']}&record={$_POST['return_record']}";
header("Location: $url");
?>
