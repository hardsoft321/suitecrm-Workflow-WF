<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $mod_strings;
$module_menu = Array();

$module_menu[]=	Array("index.php?module=WFEvents&action=EditView&return_module=WFEvents&return_action=DetailView", $mod_strings['LBL_NEW_FORM_TITLE'],"CreateWFEvents");

$module_menu[]=	Array("index.php?module=WFEvents&action=index&return_module=WFEvents&return_action=DetailView", $mod_strings['LBL_LIST_FORM_TITLE'],"WFEvents");

?>