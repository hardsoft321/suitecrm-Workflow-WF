<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $mod_strings;
$module_menu = Array();

$module_menu[]=	Array("index.php?module=WFWorkflows&action=EditView&return_module=WFWorkflows&return_action=DetailView", $mod_strings['LBL_NEW_FORM_TITLE'],"CreateWorkflows");

$module_menu[]=	Array("index.php?module=WFWorkflows&action=index&return_module=WFWorkflows&return_action=DetailView", $mod_strings['LBL_LIST_FORM_TITLE'],"Workflows");

$module_menu[]=	Array("index.php?module=WFWorkflows&action=CheckWorkflows", $mod_strings['LBL_CHECK_WORKFLOWS'],"Workflows");

?>