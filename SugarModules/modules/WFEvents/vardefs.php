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
			'link' => 'status1',
		),

		'status1' => array (
			'name' => 'status1',
            'type' => 'link',
            'relationship' => 'wfstatuses1_wfevents',
            'source' => 'non-db',
            'link_type' => 'one',
            'module' => 'WFStatuses',
            'bean_name' => 'WFStatus',
            'vname' => 'LBL_WFSTATUSES',
		),
		
		'status2_id' => array (
			'name' => 'status2_id',
			'vname' => 'LBL_STATUS2_ID',
			'type' => 'id',
			'required' => true,
		),
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
			'link' => 'status2',
		),

		'status2' => array (
			'name' => 'status2',
            'type' => 'link',
            'relationship' => 'wfstatuses2_wfevents',
            'source' => 'non-db',
            'link_type' => 'one',
            'module' => 'WFStatuses',
            'bean_name' => 'WFStatus',
            'vname' => 'LBL_WFSTATUSES',
		),

		'workflow_id' => array (
			'name' => 'workflow_id',
			'vname' => 'LBL_WORKFLOW_ID',
			'type' => 'id',
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
			'link' => 'workflow',
		),

		'workflow' => array (
			'name' => 'workflow',
            'type' => 'link',
            'relationship' => 'workflow_wfevents',
            'source' => 'non-db',
            'link_type' => 'one',
            'module' => 'WFWorkflows',
            'bean_name' => 'WFWorkflow',
            'vname' => 'LBL_WFWORKFLOW',
		),

		'sort' => array (
			'name' => 'sort',
			'vname' => 'LBL_SORT',
			'type' => 'int',
			'default' => '100',
		),

        'filter_function' => array (
			'name' => 'filter_function',
			'vname' => 'LBL_FILTER_FUNCTION',
			'type' => 'enum',
            'function' => 'wf_getFilterFunctions',
			'len' => '50',
		),
        'validate_function' => array (
			'name' => 'validate_function',
			'vname' => 'LBL_VALIDATE_FUNCTION',
			'type' => 'enum',
            'function' => 'wf_getValidateFunctions',
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
$dictionary["WFEvent"]['indices'][] = array('name'=>'idx_wfevents_st1', 'type'=>'index', 'fields'=>array('status1_id', 'deleted'));

VardefManager::createVardef('WFEvents', 'WFEvent', array('default'));

$dictionary['WFEvent']['relationships']['wfevents_modified_user'] = array(
   'lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'WFEvents', 'rhs_table'=> 'wf_events', 'rhs_key' => 'modified_user_id',
   'relationship_type'=>'one-to-many');
$dictionary['WFEvent']['relationships']['wfevents_created_by'] = array(
   'lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'WFEvents', 'rhs_table'=> 'wf_events', 'rhs_key' => 'created_by',
   'relationship_type'=>'one-to-many');

$dictionary['WFEvent']['relationships']['wfstatuses1_wfevents'] = array(
   'lhs_module'=> 'WFStatuses', 
   'lhs_table'=> 'wf_statuses', 
   'lhs_key' => 'id',
   'rhs_module'=> 'WFEvents', 
   'rhs_table'=> 'wf_events', 
   'rhs_key' => 'status1_id',
   'relationship_type'=>'one-to-many'
  );

$dictionary['WFEvent']['relationships']['wfstatuses2_wfevents'] = array(
   'lhs_module'=> 'WFStatuses', 
   'lhs_table'=> 'wf_statuses', 
   'lhs_key' => 'id',
   'rhs_module'=> 'WFEvents', 
   'rhs_table'=> 'wf_events', 
   'rhs_key' => 'status2_id',
   'relationship_type'=>'one-to-many'
  );

$dictionary['WFEvent']['relationships']['workflow_wfevents'] = array(
   'lhs_module'=> 'WFWorkflows', 
   'lhs_table'=> 'wf_workflows', 
   'lhs_key' => 'id',
   'rhs_module'=> 'WFEvents', 
   'rhs_table'=> 'wf_events', 
   'rhs_key' => 'workflow_id',
   'relationship_type'=>'one-to-many'
  );

?>


