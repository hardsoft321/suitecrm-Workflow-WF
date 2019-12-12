<?php

$manifest = array (
  'acceptable_sugar_versions' => array (),
  'acceptable_sugar_flavors' => array ('CE'),
  'author' => 'nlv, pea',
  'description' => 'Workflow',
  'icon' => '',
  'is_uninstallable' => true,
  'name' => 'Workflow-WF',
  'published_date' => '2014-06-24',
  'type' => 'module',
  'remove_tables' => 'prompt',
  'readme' => 'See README.md', // file_get_contents(__DIR__ . '/README.md'),
  'version' => '0.13.0',
);
$installdefs = array (
  'id' => 'Workflow-WF',
  'administration' => array(
    array(
      'from'=>'<basepath>/SugarModules/administration/workflow.php',
    ),
  ),
  'beans' =>
  array (
    array (
      'module' => 'WFModules',
      'class' => 'WFModule',
      'path' => 'modules/WFModules/WFModule.php',
      'tab' => false,
    ),
	array (
      'module' => 'WFWorkflows',
      'class' => 'WFWorkflow',
      'path' => 'modules/WFWorkflows/WFWorkflow.php',
      'tab' => false,
    ),
	array (
      'module' => 'WFStatuses',
      'class' => 'WFStatus',
      'path' => 'modules/WFStatuses/WFStatus.php',
      'tab' => false,
    ),
	array (
      'module' => 'WFEvents',
      'class' => 'WFEvent',
      'path' => 'modules/WFEvents/WFEvent.php',
      'tab' => false,
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
      'from' => '<basepath>/SugarModules/custom/',
      'to' => 'custom/',
    ),
    array (
      'from' => '<basepath>/SugarModules/modules',
      'to' => 'modules',
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
    array(
      'from'=> '<basepath>/SugarModules/language/modules/Administration/mod_strings_ru_ru.php',
      'to_module'=> 'Administration',
      'language'=>'ru_ru'
    ),
    array(
      'from'=> '<basepath>/SugarModules/language/modules/Administration/mod_strings_en_us.php',
      'to_module'=> 'Administration',
      'language'=>'en_us'
    ),
    array(
      'from'=> '<basepath>/SugarModules/language/modules/Administration/mod_strings_ge_ge.php',
      'to_module'=> 'Administration',
      'language'=>'ge_ge'
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

