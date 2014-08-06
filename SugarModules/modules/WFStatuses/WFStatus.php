<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class WFStatus extends SugarBean {

	var $id;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $name;
	var $uniq_name;
	var $wf_module;
	var $role_id;
	var $role_name;
	var $in_role_type;
	var $out_role_type;

	var $table_name = "wf_statuses";
	var $object_name = "WFStatus";
	var $module_dir = 'WFStatuses';
	var $importable = true;

	function WFStatus() {
		parent::SugarBean();
	}

	var $new_schema = true;

	function get_summary_text()
	{
		return "{$this->name}";
	}

	function bean_implements($interface){
		switch($interface){
			case 'ACL':return true;
		}
		return false;
	}
}

require_once ("custom/include/Workflow/utils.php");
