<?php

$searchdefs ['WFModules'] = array ( 
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4', 
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),

  'layout' => array (
    'basic_search' => array (
      'name' => array('name'=>'wf_module'),
    ),
    'advanced_search' => array (
      'wf_module' => array (
        'name' => 'wf_module',
        'default' => true,
        'width' => '10%',
      ),
	  'type_field' => array (
        'name' => 'type_field',
        'default' => true,
        'width' => '10%',
      ),
    ),
  ),
);
?>
