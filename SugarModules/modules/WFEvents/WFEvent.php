<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class WFEvent extends SugarBean {

	var $id;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $name;
	
	var $status1_id;
	var $status1_name;
	var $status2_id;
	var $status2_name;
	var $workflow_id;
	var $workflow_name;
	var $sort;
	var $function;
    var $filter_function;
    var $validate_function;
    var $after_save;
	
	var $table_name = "wf_events";
	var $object_name = "WFEvent";
	var $module_dir = 'WFEvents';
	var $importable = true;

	function WFEvent() {
		parent::SugarBean();
	}

	var $new_schema = true;

	function get_summary_text()
	{
		return "{$this->workflow_name}: {$this->status1_name} &rarr; {$this->status2_name}";
	}

	function ACLAccess($view, $is_owner='not_set', $in_group = 'not_set')
	{
		return $GLOBALS['current_user']->isAdmin();
	}

	function get_list_view_data() {
		$data = parent::get_list_view_data();
		
		if(!empty($this->status1_id)) {
			$status = BeanFactory::getBean('WFStatuses', $this->status1_id);
			$data['STATUS1_ID'] = $status->name;
		}
		if(!empty($this->status2_id)) {
			$status = BeanFactory::getBean('WFStatuses', $this->status2_id);
			$data['STATUS2_ID'] = $status->name;
		}
		if(!empty($this->workflow_id)) {
			$workflow = BeanFactory::getBean('WFWorkflows', $this->workflow_id);
			$data['WORKFLOW_ID'] = $workflow->name;
		}
		return $data;
	}

	//TODO: сверять имя модуля статутов и маршрутов
}

require_once ("custom/include/Workflow/utils.php");
