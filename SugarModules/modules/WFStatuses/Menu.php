<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $mod_strings;
$module_menu = Array();

$module_menu[]=	Array("index.php?module=WFStatuses&action=EditView&return_module=WFStatuses&return_action=DetailView", $mod_strings['LBL_NEW_FORM_TITLE'],"CreateWFStatuses");

$module_menu[]=	Array("index.php?module=WFStatuses&action=index&return_module=WFStatuses&return_action=DetailView", $mod_strings['LBL_LIST_FORM_TITLE'],"WFStatuses");

?>