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

		'in_role_type' => array (
          'name' => 'in_role_type',
          'vname' => 'LBL_IN_ROLE_TYPE',
          'type' => 'enum',
		  'len' => '10',
		  'options' => 'in_role_types',
		  'default' => 'default',
          'required' => true,
        ),
		'out_role_type' => array (
          'name' => 'out_role_type',
          'vname' => 'LBL_OUT_ROLE_TYPE',
          'type' => 'enum',
		  'len' => '10',
		  'options' => 'out_role_types',
		  'default' => 'default',
          'required' => true,
        ),
  ),
);
$dictionary["WFStatus"]['indices'][] = array('name'=>'idx_wfstatus_uniq_m_d', 'type'=>'index', 'fields'=>array('uniq_name', 'wf_module', 'deleted'));

VardefManager::createVardef('WFStatuses', 'WFStatus', array('default'));
?>
