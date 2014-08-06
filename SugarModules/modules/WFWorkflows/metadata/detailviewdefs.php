<?php

$viewdefs ['WFWorkflows'] = array (  'DetailView' => 
  array (
    'templateMeta' => array (
      'form' => array (
        'buttons' => array (
           'EDIT',
           'DUPLICATE',
           'DELETE',
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
		array (
            array('name'=>'uniq_name'),
            array(
                'name'=>'status_field',
                'customCode'=>'{$fields.status_field.value}',
            ),
        ),
        array (
            array(
                'name'=>'bean_type',
                'customCode'=>'{$fields.bean_type.value}'
            )
        ),
      ),
    ),
  ),
);
?>
