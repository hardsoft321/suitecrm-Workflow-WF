<?php
 if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['WFEvent'] = array(
  'table' => 'wf_events',
  'unified_search' => true,
  'fields' => array (
        'status1_id' => array (
			'name' => 'status1_id',
			'vname' => 'LBL_STATUS1_ID',
			'type' => 'id',
		),
        /*'status1_uniq_name' => array (
			'name' => 'status1_uniq_name',
			'vname' => 'LBL_STATUS1_UNIQ_NAME',
			'type' => 'id',
		),*/
        'status1_name' => array (
			'name' => 'status1_name',
			'rname' => 'name',
			'id_name' => 'status1_id',
			'vname' => 'LBL_STATUS1_NAME',
			'type' => 'relate',
			'table' => 'wf_statuses',
			'module' => 'WFStatuses',
			'dbType' => 'varchar',
			'len' => '30',
			'source' => 'non-db',
		),
		
		'status2_id' => array (
			'name' => 'status2_id',
			'vname' => 'LBL_STATUS2_ID',
			'type' => 'id',
			'required' => true,
		),
        /*'status2_uniq_name' => array (
			'name' => 'status2_uniq_name',
			'vname' => 'LBL_STATUS2_UNIQ_NAME',
			'type' => 'id',
			'required' => true,
		),*/
        'status2_name' => array (
			'name' => 'status2_name',
			'rname' => 'name',
			'id_name' => 'status2_id',
			'vname' => 'LBL_STATUS2_NAME',
			'type' => 'relate',
			'table' => 'wf_statuses',
			'module' => 'WFStatuses',
			'dbType' => 'varchar',
			'len' => '30',
			'source' => 'non-db',
			'required' => true,
		),
		
		'workflow_id' => array (
			'name' => 'workflow_id',
			'vname' => 'LBL_WORKFLOW_ID',
			'type' => 'id',
			//'required' => true,
		),
        'workflow_name' => array (
			'name' => 'workflow_name',
			'rname' => 'name',
			'id_name' => 'workflow_id',
			'vname' => 'LBL_WORKFLOW_NAME',
			'type' => 'relate',
			'table' => 'wf_workflows',
			'module' => 'WFWorkflows',
			'dbType' => 'varchar',
			'source' => 'non-db',
			//'required' => true,
		),
		'sort' => array (
			'name' => 'sort',
			'vname' => 'LBL_SORT',
			'type' => 'int',
			'default' => '100',
		),
		/*'function' => array (
			'name' => 'function',
			'vname' => 'LBL_FUNCTION',
			'type' => 'varchar',
			'len' => '255',
		),*/
        'filter_function' => array (
			'name' => 'filter_function',
			'vname' => 'LBL_FILTER_FUNCTION',
			'type' => 'enum',
            'function' => 'wf_getFilterFunctions',
			'len' => '50',
		),
        'after_save' => array (
			'name' => 'after_save',
			'vname' => 'LBL_AFTER_SAVE',
			'type' => 'enum',
            'function' => 'wf_getProcedures',
			'len' => '50',
		),
  ),
);
//$dictionary["WFStatus"]['indices'][] = array('name'=>'wfstatus_name_uk', 'type'=>'unique', 'fields'=>array('name'));

VardefManager::createVardef('WFEvents', 'WFEvent', array('default'));
?>