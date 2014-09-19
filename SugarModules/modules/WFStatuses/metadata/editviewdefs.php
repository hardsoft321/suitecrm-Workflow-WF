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
//        array ('in_role_type', 'out_role_type'),
		array ('edit_role_type', 'parent_status_name'),
		array ('front_assigned_list_function'),
		array ('assigned_list_function'),
		array ('confirm_list_function'),
      ),
      
    ),
  ),
);
?>
