<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $mod_strings;
$module_menu = Array();

$module_menu[]=	Array("index.php?module=WFEvents&action=EditView&return_module=WFEvents&return_action=DetailView", $mod_strings['LBL_NEW_FORM_TITLE'],"CreateWFEvents");

$module_menu[]=	Array("index.php?module=WFEvents&action=index&return_module=WFEvents&return_action=DetailView", $mod_strings['LBL_LIST_FORM_TITLE'],"WFEvents");

global $current_language;
$statuses_mod_strings = return_module_language($current_language, 'WFStatuses');
$module_menu[]=	Array("index.php?module=WFStatuses&action=index&return_module=WFStatuses&return_action=DetailView", $statuses_mod_strings['LBL_LIST_FORM_TITLE'],"WFStatuses");

?>