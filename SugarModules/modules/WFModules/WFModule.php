<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class WFModule extends SugarBean {

	var $id;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $name;
	
	var $wf_module;
	var $type_field;
	
	var $table_name = "wf_modules";
	var $object_name = "WFModule";
	var $module_dir = 'WFModules';
	var $importable = true;

	function WFModule() {
		parent::SugarBean();
	}

	var $new_schema = true;

	function get_summary_text()
	{
		return $this->wf_module;
	}

	function bean_implements($interface){
		switch($interface){
			case 'ACL':return true;
		}
		return false;
	}
}

