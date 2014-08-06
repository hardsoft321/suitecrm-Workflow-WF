<?php

$searchdefs ['WFWorkflows'] = array ( 
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
      'name' => array('name'=>'name','query_type'=>'default', 'width'=>'10%'),
    ),
    'advanced_search' => array (
      'wf_module' => array (
        'name' => 'wf_module',
        'default' => true,
        'width' => '10%',
      ),
      'name' => array('name'=>'name','query_type'=>'default'),
      //'type' => array('name'=>'type','query_type'=>'default'),
    ),
  ),
);
?>
