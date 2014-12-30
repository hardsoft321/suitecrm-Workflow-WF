<?php
global $current_user;
if(is_admin($current_user)) {
    $app_list_strings['moduleList']['WFModules'] = 'Workflow Modules';
    $app_list_strings['moduleList']['WFWorkflows'] = 'Workflows';
    $app_list_strings['moduleList']['WFStatuses'] = 'Workflow Statuses';
    $app_list_strings['moduleList']['WFEvents'] = 'Workflow Events';
}

/*$app_list_strings['in_role_types'] = array(
    'role' => 'All in Role',
    // 'old' => 'Recent Executer',
    'function' => 'From Assigned List Function',
);*/
/*$app_list_strings['out_role_types'] = array(
    'role' => 'All in Role',
    'assigned' => 'Assigned In Status',
    'owner' => 'Record Owner',
);*/

$app_list_strings['edit_role_types'] = array(
    'nobody' => 'Nobody but Admin',
//	'role' => 'All in Role',
//	'assigned' => 'Assigned In Status',
	'owner' => 'Owner',
);

$app_strings['LBL_CONFIRM_LIST'] = 'Liste Bestätigen';
$app_strings['LBL_USER'] = 'Benutzer';
$app_strings['LBL_STATUS_CHANGE'] = 'Status geändert um';
$app_strings['LBL_RESOLUTION'] = 'Auflösung';

$app_strings['LBL_CONFIRM_ERRORS_TITLE'] = 'Fehler: ';
