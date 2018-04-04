<?php
$db_defs['wf_modules'] = array(
    'table' => 'wf_modules',
    'module' => 'WFModules',
    'fields' => array(
        'name' => array (
            'name' => 'name',
        ),
        'description' => array (
            'name' => 'description',
        ),
        'wf_module' => array (
            'name' => 'wf_module',
        ),
        'type_field' => array (
            'name' => 'type_field',
        ),
    ),
    'indices' => array(
        array('fields' => array('wf_module')),
    ),
);

$db_defs['wf_workflows'] = array(
    'table' => 'wf_workflows',
    'module' => 'WFWorkflows',
    'fields' => array(
        'name' => array (
            'name' => 'name',
        ),
        'description' => array (
            'name' => 'description',
        ),
        'wf_module' => array (
          'name' => 'wf_module',
        ),
        'uniq_name' => array (
          'name' => 'uniq_name',
        ),
        'status_field' => array (
          'name' => 'status_field',
        ),
        'bean_type' => array (
          'name' => 'bean_type',
        ),
    ),
    'indices' => array(
        array('fields' => array('uniq_name')),
    ),
);

$db_defs['wf_statuses'] = array(
    'table' => 'wf_statuses',
    'module' => 'WFStatuses',
    'fields' => array(
        'name' => array (
            'name' => 'name',
        ),
        'description' => array (
            'name' => 'description',
        ),
        'wf_module' => array (
            'name' => 'wf_module',
        ),
        'uniq_name' => array (
            'name' => 'uniq_name',
        ),
        'role_id' => array (
            'name' => 'role_id',
            'type' => 'id',
            'table' => 'acl_roles',
            'required' => false,
        ),
        'role2_id' => array (
            'name' => 'role2_id',
            'type' => 'id',
            'table' => 'acl_roles',
            'required' => false,
        ),
        'edit_role_type' => array (
            'name' => 'edit_role_type',
        ),
        'front_assigned_list_function' => array (
            'name' => 'front_assigned_list_function',
        ),
        'assigned_list_function' => array (
            'name' => 'assigned_list_function',
        ),
        'confirm_list_function' => array (
            'name' => 'confirm_list_function',
        ),
        'confirm_check_list_function' => array (
            'name' => 'confirm_check_list_function',
        ),
        'isfinal' => array (
            'name' => 'isfinal',
        ),
    ),
    'indices' => array(
        array('fields' => array('uniq_name', 'wf_module')),
    ),
);

$db_defs['wf_events'] = array(
    'table' => 'wf_events',
    'module' => 'WFEvents',
    'fields' => array(
        'name' => array (
            'name' => 'name',
        ),
        'description' => array (
            'name' => 'description',
        ),
        'status1_id' => array (
            'name' => 'status1_id',
            'type' => 'id',
            'table' => 'wf_statuses',
            'required' => false,
        ),
        'status2_id' => array (
            'name' => 'status2_id',
            'type' => 'id',
            'table' => 'wf_statuses',
            'required' => true,
        ),
        'workflow_id' => array (
            'name' => 'workflow_id',
            'type' => 'id',
            'table' => 'wf_workflows',
            'required' => true,
        ),
        'sort' => array (
            'name' => 'sort',
        ),
        'filter_function' => array (
            'name' => 'filter_function',
        ),
        'validate_function' => array (
            'name' => 'validate_function',
        ),
        'after_save' => array (
            'name' => 'after_save',
        ),
        'func_params' => array(
            'name' => 'func_params',
        ),
        'resolution_required' => array(
            'name' => 'resolution_required',
        ),
    ),
    'indices' => array(
        array('fields' => array('status1_id', 'status2_id', 'workflow_id')),
    ),
);
