<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $mod_strings;
$module_menu = Array();

$module_menu[]=	Array("index.php?module=WFModules&action=index&return_module=WFModules&return_action=DetailView", $mod_strings['LBL_LIST_FORM_TITLE'],"WFModules");

?>