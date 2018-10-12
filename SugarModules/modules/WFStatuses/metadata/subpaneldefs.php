<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$layout_defs['WFStatuses'] = array(
    'subpanel_setup' => array(
        'prev_statuses' => array (
            'order' => 10,
            'sort_by' => 'workflow_id',
            'sort_order' => 'desc',
            'module' => 'WFEvents',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'function:getEventsToStatusQuery',
            'title_key' => 'LBL_EVENTS_TO_STATUS',
        ),
        'next_statuses' => array (
            'order' => 20,
            'sort_by' => 'workflow_id',
            'sort_order' => 'desc',
            'module' => 'WFEvents',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'function:getEventsFromStatusQuery',
            'title_key' => 'LBL_EVENTS_FROM_STATUS',
        ),
    ),
);
