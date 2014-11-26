<?php

$viewdefs ['WFEvents'] = array ( 'EditView' => 
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
        array ('workflow_name'),
        array ('status1_name', 'status2_name'),
		array ('sort', ),
        array ('filter_function', 'validate_function'),
        array ('after_save', ''),
      ),
    ),
  ),
);
?>
