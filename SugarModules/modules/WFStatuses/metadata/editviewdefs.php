<?php

$viewdefs ['WFStatuses'] = array ( 'EditView' => 
  array (
    'templateMeta' => array (
      'form' =>  array (
        'buttons' => array (
           'SAVE',
           'CANCEL',
        ),
      ),
      'maxColumns' => '2',
      'widths' => array (
        array (
          'label' => '10',
          'field' => '30',
        ),
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
    ),
    'panels' => array (
      'lbl_information' => array (
        array ('name', 'uniq_name'),
        array ('role_name', 'wf_module'),
		array ('edit_role_type','isfinal'),
		array ('front_assigned_list_function'),
		array ('assigned_list_function', 'role2_name'),
		array ('confirm_list_function'),
		array ('confirm_check_list_function'),
      ),
      
    ),
  ),
);
?>
