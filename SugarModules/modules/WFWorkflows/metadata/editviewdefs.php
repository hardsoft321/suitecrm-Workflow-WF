<?php

$viewdefs ['WFWorkflows'] = array ( 'EditView' => 
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
        array ('wf_module', 'name'),
        array ('uniq_name', 'status_field'),
        array ('bean_type'),
      ),
      
    ),
  ),
);
?>
