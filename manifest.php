<?php

global $sugar_config;

$manifest = array (
  0 => 
  array (
    'acceptable_sugar_versions' => 
    array (
		'exact_matches' => array (1 => '6.5.16')
    ),
  ),
  1 => 
  array (
    'acceptable_sugar_flavors' => 
    array (
      0 => 'CE',
      1 => 'PRO',
      2 => 'ENT',
    ),
  ),
  'author' => 'nlv, pea',
  'description' => 'Workflow',
  'icon' => '',
  'is_uninstallable' => true,
  'name' => 'Workflow-WF',
  'published_date' => '2014-06-24',
  'type' => 'module',
  'version' => '0.7.15',
  'remove_tables' => 'prompt',
  'dependencies' => array(
  ),
);


$installdefs = array (
  'id' => 'Workflow-WF',
  'beans' =>
  array (
    array (
      'module' => 'WFModules',
      'class' => 'WFModule',
      'path' => 'modules/WFModules/WFModule.php',
      'tab' => true,
    ),
	array (
      'module' => 'WFWorkflows',
      'class' => 'WFWorkflow',
      'path' => 'modules/WFWorkflows/WFWorkflow.php',
      'tab' => true,
    ),
	array (
      'module' => 'WFStatuses',
      'class' => 'WFStatus',
      'path' => 'modules/WFStatuses/WFStatus.php',
      'tab' => true,
    ),
	array (
      'module' => 'WFEvents',
      'class' => 'WFEvent',
      'path' => 'modules/WFEvents/WFEvent.php',
      'tab' => true,
    ),
  ),
  'entrypoints' =>
  array (
    array (
      'from' => '<basepath>/SugarModules/entrypoints/entry_point_registry.Workflow.php',
      'to_module' => 'application',
    ),
  ),

  'vardefs' =>
  array (
  ),
  'copy' => 
  array (
    array (
      'from' => '<basepath>/SugarModules/custom/include/',
      'to' => 'custom/include',
    ),
    array (
      'from' => '<basepath>/SugarModules/modules',
      'to' => 'modules',
    ),
    array (
      'from' => "<basepath>/SugarModules/upgrade_unsafe/{$sugar_config['sugar_version']}/",
      'to' => '.',
    ),
  ),
  'language' => 
  array (
    array (
      'from' => '<basepath>/SugarModules/language/application/en_us.lang.php',
      'to_module' => 'application',
      'language' => 'en_us',
    ),
    array (
      'from' => '<basepath>/SugarModules/language/application/ru_ru.lang.php',
      'to_module' => 'application',
      'language' => 'ru_ru',
    ),
    array (
      'from' => '<basepath>/SugarModules/language/application/ge_ge.lang.php',
      'to_module' => 'application',
      'language' => 'ge_ge',
    ),
  ),
  'logic_hooks' => array (
      array (
         'module' => '',
         'hook' => 'after_entry_point',
         'order' => 99,
         'description' => 'Include workflow files',
         'file' => 'custom/include/Workflow/WF_hooks.php',
         'class' => 'WF_hooks',
         'function' => 'after_entry_point',
      ),
  ),
  'relationships' => array (
    array (
        'module' => 'Users',
        'meta_data' => '<basepath>/SugarModules/relationships/wf_status_assignedMetaData.php',
    ),
  ),
);

