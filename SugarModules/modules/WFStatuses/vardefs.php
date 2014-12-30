<?php
 if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['WFStatus'] = array(
  'table' => 'wf_statuses',
  'unified_search' => true,
  'fields' => array (
        'wf_module' => array (
          'name' => 'wf_module',
          'vname' => 'LBL_WF_MODULE',
          'type' => 'enum',
          'function' => 'wf_getModulesList',
          'required' => true,
        ),
		'uniq_name' => array (
          'name' => 'uniq_name',
          'vname' => 'LBL_UNIQ_NAME',
          'type' => 'varchar',
		  'len' => '255',
          'required' => true,
        ),
        /* 'parent_status_id' => array (
          'name' => 'parent_status_id',
          'vname' => 'LBL_PARENT_STATUS_ID',
          'type' => 'char',
		  'len' => '36',
          'required' => false,
        ),
        'parent_status_name' => array (
			'name' => 'parent_status_name',
			'rname' => 'name',
			'id_name' => 'parent_status_id',
			'vname' => 'LBL_PARENT_STATUS_NAME',
			'type' => 'relate',
			'table' => 'wf_statuses',
			'module' => 'WFStatuses',
			'source' => 'non-db',
			'required' => false,
		),*/
        
		'role_id' => array (
			'name' => 'role_id',
			'vname' => 'LBL_ROLE_ID',
			'type' => 'id',
			'required' => true,
		),
        'role_name' => array (
			'name' => 'role_name',
			'rname' => 'name',
			'id_name' => 'role_id',
			'vname' => 'LBL_ROLE_NAME',
			'type' => 'relate',
			'table' => 'acl_roles',
			'module' => 'ACLRoles',
			'dbType' => 'varchar',
			'source' => 'non-db',
			'required' => true,
		),

		'role2_id' => array (
			'name' => 'role2_id',
			'vname' => 'LBL_ROLE2_ID',
			'type' => 'id',
			'required' => false,
		),
        'role2_name' => array (
			'name' => 'role2_name',
			'rname' => 'name',
			'id_name' => 'role2_id',
			'vname' => 'LBL_ROLE2_NAME',
			'type' => 'relate',
			'table' => 'acl_roles',
			'module' => 'ACLRoles',
			'dbType' => 'varchar',
			'source' => 'non-db',
			'required' => false,
		),

        'edit_role_type' => array (
          'name' => 'edit_role_type',
          'vname' => 'LBL_EDIT_ROLE_TYPE',
          'type' => 'enum',
		  'len' => '10',
		  'options' => 'edit_role_types',
		  'default' => 'owner',
          'required' => true,
        ),
        'front_assigned_list_function' => array (
			'name' => 'front_assigned_list_function',
			'vname' => 'LBL_FRONT_ASSIGNED_LIST_FUNCTION',
			'type' => 'enum',
            'function' => 'wf_getAssignedListFunctions',
			'len' => '50',
		),
        'assigned_list_function' => array (
			'name' => 'assigned_list_function',
			'vname' => 'LBL_ASSIGNED_LIST_FUNCTION',
			'type' => 'enum',
            'function' => 'wf_getAssignedListFunctions',
			'len' => '50',
		),
		'confirm_list_function' => array (
			'name' => 'confirm_list_function',
			'vname' => 'LBL_CONFIRM_LIST_FUNCTION',
			'type' => 'enum',
            'function' => 'wf_getAssignedListFunctions',
			'len' => '50',
		),
		'confirm_check_list_function' => array (
			'name' => 'confirm_check_list_function',
			'vname' => 'LBL_CONFIRM_CHECK_LIST_FUNCTION',
			'type' => 'enum',
            'function' => 'wf_getAssignedListFunctions',
			'len' => '50',
		),
            'isfinal' => array (
                        'name' => 'isfinal',
                        'vname' => 'LBL_ISFINAL',
                        'type' => 'bool',
                        'reportable'=>false,
                        'default'=>'0',
                        'comment' => 'Indicates if item has been archived'
                ),

  ),
);
$dictionary["WFStatus"]['indices'][] = array('name'=>'idx_wfstatus_uniq_m_d', 'type'=>'index', 'fields'=>array('uniq_name', 'wf_module', 'deleted'));

VardefManager::createVardef('WFStatuses', 'WFStatus', array('default'));
?>
