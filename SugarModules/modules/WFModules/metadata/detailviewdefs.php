<?php

$viewdefs ['WFModules'] = array (  'DetailView' => 
  array (
    'templateMeta' => array (
      'form' => array (
        'buttons' => array (
           /*'EDIT',
           'DUPLICATE',
           'DELETE',*/
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
        array ('wf_module', 'type_field'),
      ),
    ),
  ),
);
?>
