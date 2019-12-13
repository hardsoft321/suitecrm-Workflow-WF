<?php
 if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['WFWorkflow'] = array(
  'table' => 'wf_workflows',
  'unified_search' => false,
  'audited' => true,
  'fields' => array (
        'wf_module' => array (
          'name' => 'wf_module',
          'vname' => 'LBL_WF_MODULE',
          'type' => 'enum',
          'function' => 'wf_getModulesList',
          'required' => true,
          'audited' => true,
        ),
		/*'type' => array (
          'name' => 'type',
          'vname' => 'LBL_TYPE',
          'type' => 'varchar',
		  'len' => '255',
        ),*/
		'uniq_name' => array (
          'name' => 'uniq_name',
          'vname' => 'LBL_UNIQ_NAME',
          'type' => 'varchar',
		  'len' => '30',
          'required' => true,
          'audited' => true,
        ),
        'status_field' => array (
          'name' => 'status_field',
          'vname' => 'LBL_STATUS_FIELD',
          'type' => 'enum',
		  'len' => '30',
          'required' => true,
          'function' => array(
            'include' => 'modules/WFWorkflows/wfworkflow_fields.php',
            'name' => 'wfworkflow_statusfield_options',
            'returns' => 'options',
          ),
          'audited' => true,
        ),
        'bean_type' => array (
          'name' => 'bean_type',
          'vname' => 'LBL_BEAN_TYPE',
          'type' => 'multienum',
          'isMultiSelect' => true,
          'required' => true,
          'function' => array(
            'include' => 'modules/WFWorkflows/wfworkflow_fields.php',
            'name' => 'wfworkflow_beantype_options',
            'returns' => 'options',
          ),
          'audited' => true,
        ),
  ),
);
$dictionary["WFWorkflow"]['indices'][] = array('name'=>'idx_workflow_uniq_d', 'type'=>'index', 'fields'=>array('uniq_name', 'deleted'));

VardefManager::createVardef('WFWorkflows', 'WFWorkflow', array('default'));

$dictionary['WFWorkflow']['fields']['name']['audited'] = true;

$dictionary['WFWorkflow']['relationships']['wfworkflows_modified_user'] = array(
   'lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'WFWorkflows', 'rhs_table'=> 'wf_workflows', 'rhs_key' => 'modified_user_id',
   'relationship_type'=>'one-to-many');
$dictionary['WFWorkflow']['relationships']['wfworkflows_created_by'] = array(
   'lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'WFWorkflows', 'rhs_table'=> 'wf_workflows', 'rhs_key' => 'created_by',
   'relationship_type'=>'one-to-many');
?>
