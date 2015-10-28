<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $mod_strings;
global $current_language;
$module_menu = Array();

$module_menu[]=	Array("index.php?module=WFWorkflows&action=EditView&return_module=WFWorkflows&return_action=DetailView", $mod_strings['LBL_NEW_FORM_TITLE'],"CreateWorkflows");

$module_menu[]=	Array("index.php?module=WFWorkflows&action=index&return_module=WFWorkflows&return_action=DetailView", $mod_strings['LBL_LIST_FORM_TITLE'],"Workflows");

$statuses_mod_strings = return_module_language($current_language, 'WFStatuses');
$module_menu[]=	Array("index.php?module=WFStatuses&action=index&return_module=WFStatuses&return_action=DetailView", $statuses_mod_strings['LBL_LIST_FORM_TITLE'],"WFStatuses");

$events_mod_strings = return_module_language($current_language, 'WFEvents');
$module_menu[]=	Array("index.php?module=WFEvents&action=index&return_module=WFEvents&return_action=DetailView", $events_mod_strings['LBL_LIST_FORM_TITLE'],"WFEvents");

$module_menu[]=	Array("index.php?module=WFWorkflows&action=CheckWorkflows", $mod_strings['LBL_CHECK_WORKFLOWS'],"Workflows");

$module_menu[]=	Array("index.php?module=WFWorkflows&action=FunctionsDoc", $mod_strings['LBL_FUNCTIONS_DOC'],"Workflows");

?>