<?php
 if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['WFModule'] = array(
  'table' => 'wf_modules',
  'unified_search' => false,
  'audited' => true,
  'fields' => array (
        'wf_module' => array (
          'name' => 'wf_module',
          'vname' => 'LBL_WF_MODULE',
          'type' => 'enum',
          'required' => true,
          'function' => array(
            'include' => 'modules/WFModules/wfmodule_fields.php',
            'name' => 'wfmodule_wfmodule_options',
            'returns' => 'options',
          ),
          'audited' => true,
        ),
        'type_field' => array (
          'name' => 'type_field',
          'vname' => 'LBL_TYPE_FIELD',
          'type' => 'enum',
          'required' => true,
          'function' => array(
            'include' => 'modules/WFModules/wfmodule_fields.php',
            'name' => 'wfmodule_typefield_options',
            'returns' => 'options',
          ),
          'audited' => true,
        ),
  ),
);

VardefManager::createVardef('WFModules', 'WFModule', array('default'));

$dictionary['WFModule']['relationships']['wfmodules_modified_user'] = array(
   'lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'WFModules', 'rhs_table'=> 'wf_modules', 'rhs_key' => 'modified_user_id',
   'relationship_type'=>'one-to-many');
$dictionary['WFModule']['relationships']['wfmodules_created_by'] = array(
   'lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'WFModules', 'rhs_table'=> 'wf_modules', 'rhs_key' => 'created_by',
   'relationship_type'=>'one-to-many');
?>
