<?php

$entry_point_registry['wf_confirm'] = array (
  'file' => 'custom/include/Workflow/actions/confirm.php',
  'auth' => false,
);
$entry_point_registry['wf_assign'] = array (
  'file' => 'custom/include/Workflow/actions/assign.php',
  'auth' => true,
);
$entry_point_registry['wf_mass_confirm'] = array (
  'file' => 'custom/include/Workflow/actions/mass_confirm.php',
  'auth' => false,
);
$entry_point_registry['wf_debug'] = array (
  'file' => 'custom/include/Workflow/actions/debug.php',
  'auth' => true,
);

?>
