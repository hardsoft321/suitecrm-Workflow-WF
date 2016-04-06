<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$mod_strings = array (
'LBL_MODULE_NAME' => 'WFWorkflows' ,
'LBL_OBJECT_NAME' => 'Workflow',
'LBL_MODULE_TITLE' => 'WFWorkflow - HOME' ,
'LBL_SEARCH_FORM_TITLE' => 'List of Workflows' ,
'LBL_LIST_FORM_TITLE' => 'List of Workflows' ,
'LBL_NEW_FORM_TITLE' => 'Insert Workflow' ,

'LBL_INFORMATION' => 'Information' ,

'LBL_NAME' => 'Name:' ,
'LBL_WF_MODULE' => 'Module',
'LBL_STATUS_FIELD' => 'Status Field',

'LBL_LIST_NAME' => 'Name',
'LBL_LIST_WF_MODULE' => 'Module',

'LBL_EXPORT_NAME' => 'Name',
'LBL_EXPORT_WF_MODULE' => 'Module',

'LBL_UNIQ_NAME' => 'Unique Name',

'LBL_BEAN_TYPE' => 'Bean Type Value',

'LBL_CHECK_WORKFLOWS' => 'Workflows Checking',
'LBL_CONFLICTS_FOUND' => 'Conflicts Found',
'LBL_ROLE' => 'Role',
'LBL_EVENT' => 'Event',
'LBL_STATUS' => 'Status',
'LBL_STATUS_ROLE_FUNCTIONS_CHECK' => 'Check Assign Functions',
'LBL_STATUS_ROLE_FUNCTIONS_INFO' => 'Statuses within a workflow with one role must have same values of "Assigned List Function", "Confirm List Function", "Role2".',
'LBL_WORKFLOW_UNIQ_CHECK' => 'Check Workflows Uniqueness',
'LBL_WORKFLOW_UNIQ_INFO' => 'Workflow Unique Name must be unique.',
'LBL_STATUS_UNIQ_CHECK' => 'Check Statuses Uniqueness',
'LBL_STATUS_UNIQ_INFO' => 'Status Unique Name must be unique within module.',
'LBL_EVENT_UNIQ_CHECK' => 'Check Events Uniqueness',
'LBL_EVENT_UNIQ_INFO' => 'Event uniqueness depends on status1 and status2 uniq names and module.',
'MSG_CONFLICT_FOUND_AFTER_SAVE' => 'Conflict Found. More information at "Workflows Checking" page.',
'LBL_STATUSES_WITHOUT_EVENTS' => 'Statuses without events',

'LBL_FUNCTIONS_DOC' => 'Functions',
'LBL_USAGES' => 'Usages',
'LBL_USER_LISTS_FUNCTIONS' => 'User Lists',

//custom/include/Workflow
'LBL_TOGGLE_BUTTON' => 'Confirm Panel',
'LBL_EXPAND' => 'Expand',
'LBL_ASSIGNED_CHANGE_TITLE' => 'Change Assigned User',
'LBL_ROLE' => 'Role',
'LBL_NEW_ASSIGNED' => 'New Assigned User',
'LBL_ASSIGN_SUBMIT' => 'Submit',
'LBL_CONFIRM_SUBMIT' => 'Submit',
'LBL_RESOLUTION' => 'Resolution',
'LBL_ASSIGNED' => 'Assigned User',
'LBL_ASSIGNEDS' => 'Assigned Users',
'LBL_CONFIRM_STATUS' => 'Change Status',
'LBL_NEW_STATUS' => 'New Status',
'LBL_RECIPIENT_LIST' => 'Mail To',
'ERR_RECORD_NOT_FOUND' => 'Record not found',
'ERR_STATUS_FIELD_NOT_FOUND' => 'Status field not found',
'ERR_STATUS_NOT_FOUND' => 'Status not found',
'ERR_ROLE_STATUS_NOT_FOUND' => 'Statuses for role not found',
'ERR_ASSIGN_DENIED' => 'Access denied',
'ERR_INVALID_ASSIGNED' => 'Invalid assigned user',
'ERR_ENTIRE_LIST_MASS_CONFIRM' => 'You cannot choose entire list. Please choose records.',
'ERR_FIELD_REQUIRED' => 'Field required',
'ERR_INVALID_EVENT' => 'Status changing is not allowed',
'ERR_CONFIRM_DENIED' => 'Access Denied',
'ERR_NO_RECORD' => 'No record chosen',
'ERR_MODULE_NOT_FOUND' => 'Module not found',
'ERR_SOME_RECORD_NOT_FOUND' => 'Record not found',
'ERR_NO_WORKFLOW_FOR' => "No workflow for '#NAME#'",
'ERR_NOT_SAME_WORKFLOW' => "Chosen records in different workflows (records '#NAME1#' and '#NAME2#')",
'ERR_NOT_SAME_STATUS' => "Chosen records in different statuses (records '#NAME1#' and '#NAME2#')",
'ERR_STATUS_REQUIRED' => 'Status required',
'ERR_STATUS_NOT_CHANGING' => 'Please choose next status',
'ERR_ASSIGNED_REQUIRED' => 'Assigned user required',
'ERR_CONFIRM_INVALID_FOR' => "Cannot change status for '#NAME#'",
'ERR_CONFIRM_DENIED_FOR' => "You cannot change status for '#NAME#'",
'ERR_ASSIGNED_INVALID_FOR' => "Invalid assigned user for '#NAME#'",
'ERR_VALIDATE_FUNCTION_NOT_FOUND' => 'Cannot validate',
'ERR_RECORD_NOT_IN_STATUS' => "Record '#NAME#' must be in status '#STATUS#'",

'DefaultGroupUserList' => 'Group Users in Role within Group',
'DefaultNonGroupUserList' => 'Non Group Users in Role within Group',
'DefaultRole2UserList' => 'Users in Role 2 within Group',
'DefaultUserList' => 'Users in Role within Group',
'OwnerUserList' => 'Assigned User',
'StatusAssignedUserList' => 'Assigned for Role or Group User',
'StatusAssignedDefaultUserList' => 'Assigned for Role or Users in Role within Group',
'StatusAssignedOrOwnerUserList' => 'Assigned for Role or Assigned User',
'SFFormFieldsRequired' => 'Required Fields for Status',
'DefaultCurrentUserList' => 'Current User in Role within Group',
'ParentStatusAssignedUserList' => 'Assigned for Role in Parent Record or Group User',
'EmptyUserList' => 'Nobody',

);


?>
