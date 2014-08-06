<?php

$searchdefs ['WFEvents'] = array ( 
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
      'status1_name' => array (
        'name' => 'status1_name',
        'default' => true,
        'width' => '10%',
      ),
	  'status2_name' => array (
        'name' => 'status2_name',
        'default' => true,
        'width' => '10%',
      ),
	  'workflow_name' => array (
        'name' => 'workflow_name',
        'default' => true,
        'width' => '10%',
      ),
    ),
  ),
);
?>
