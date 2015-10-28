<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class WFWorkflow extends SugarBean {

	var $id;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $name;
	var $wf_module;
	var $status_field;
	var $bean_type;

	var $table_name = "wf_workflows";
	var $object_name = "WFWorkflow";
	var $module_dir = 'WFWorkflows';
	var $importable = true;

	function WFWorkflow() {
		parent::SugarBean();
	}

	var $new_schema = true;

	function get_summary_text()
	{
		return "{$this->wf_module} / {$this->name}";
	}

	function ACLAccess($view,$is_owner='not_set')
	{
		return $GLOBALS['current_user']->isAdmin();
	}
}

require_once ("custom/include/Workflow/utils.php");
