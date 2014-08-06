<?php
 if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['WFModule'] = array(
  'table' => 'wf_modules',
  'unified_search' => true,
  'fields' => array (
        'wf_module' => array (
          'name' => 'wf_module',
          'vname' => 'LBL_WF_MODULE',
          'type' => 'varchar',
          'required' => true,
        ),
		'type_field' => array (
			'name' => 'type_field',
			'vname' => 'LBL_TYPE_FIELD',
			'type' => 'varchar',
			'required' => true,
		),		
  ),
);
//$dictionary["WFStatus"]['indices'][] = array('name'=>'wfstatus_name_uk', 'type'=>'unique', 'fields'=>array('name'));

VardefManager::createVardef('WFModule', 'WFModule', array('default'));
?>
