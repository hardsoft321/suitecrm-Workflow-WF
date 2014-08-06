<?php
 if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['WFWorkflow'] = array(
  'table' => 'wf_workflows',
  'unified_search' => true,
  'fields' => array (
        'wf_module' => array (
          'name' => 'wf_module',
          'vname' => 'LBL_WF_MODULE',
          'type' => 'enum',
          'function' => 'wf_getModulesList',
          'required' => true,
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
        ),
        'status_field' => array (
          'name' => 'status_field',
          'vname' => 'LBL_STATUS_FIELD',
          'type' => 'enum',
		  'len' => '30',
          'required' => true,
        ),
        'bean_type' => array (
          'name' => 'bean_type',
          'vname' => 'LBL_BEAN_TYPE',
          'type' => 'multienum',
          'required' => true,
        ),
  ),
);
$dictionary["WFWorkflow"]['indices'][] = array('name'=>'workflow_uniqname_uk', 'type'=>'unique', 'fields'=>array('uniq_name'));

VardefManager::createVardef('WFWorkflows', 'WFWorkflow', array('default'));
?>
