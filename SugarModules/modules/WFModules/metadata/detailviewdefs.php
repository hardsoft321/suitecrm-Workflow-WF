<?php

$viewdefs ['WFModules'] = array (  'DetailView' => 
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
        array ('wf_module', 'type_field'),
        array (
          array(
            'name' => 'utility_fields',
            'customCode' => '{$utility_fields}',
            'customLabel' => 'Utility Fields',
          ),
          array(
            'name' => 'logic_hooks',
            'customCode' => '{$logic_hooks}',
            'customLabel' => 'Logic Hooks',
          ),
        ),
      ),
    ),
  ),
);
?>
