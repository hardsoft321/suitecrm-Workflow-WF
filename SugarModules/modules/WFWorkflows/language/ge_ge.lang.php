<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$mod_strings = array (
'LBL_MODULE_NAME' => 'WFWorkflows' ,
'LBL_OBJECT_NAME' => 'Workflow',
'LBL_MODULE_TITLE' => 'WFWorkflow - HOME' ,
'LBL_SEARCH_FORM_TITLE' => 'Liste der Workflows' ,
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

'LBL_UNIQ_NAME' => 'Eindeutiger Name',

'LBL_BEAN_TYPE' => 'Bean Art Wert',

'LBL_CHECK_WORKFLOWS' => 'Workflows prüfen',
'LBL_CONFLICTS_FOUND' => 'Konflikte gefunden',
'LBL_ROLE' => 'Rolle',
'LBL_EVENT' => 'Ereignis',
'LBL_STATUS' => 'Status',
'LBL_STATUS_ROLE_FUNCTIONS_CHECK' => 'Überprüfen Sie Assign-Funktionen',
'LBL_STATUS_ROLE_FUNCTIONS_INFO' => 'Zustände innerhalb eines Workflows mit einer Rolle müssen dieselben Werte von "Assigned Listenfunktion", "Confirm Listenfunktion", "Role2".',
'LBL_STATUS_UNIQ_CHECK' => 'Überprüfen Status Einzigartigkeit',
'LBL_STATUS_UNIQ_INFO' => 'Status Einzigartige Name muss innerhalb Modul sein.',
'LBL_EVENT_UNIQ_CHECK' => 'Check Events Einzigartigkeit',
'LBL_EVENT_UNIQ_INFO' => 'Event Einzigartigkeit hängt status1 und status2 uniq Namen und Modul.',
'MSG_CONFLICT_FOUND_AFTER_SAVE' => 'Konflikt gefunden. Weitere Informationen unter "Workflows prüfen" Seite.',

//custom/include/Workflow
'LBL_TOGGLE_BUTTON' => 'Bestätigen Steuerung',
'LBL_ASSIGNED_CHANGE_TITLE' => 'Ändern Sie Zugewiesene Benutzer',
'LBL_ROLE' => 'Rolle',
'LBL_NEW_ASSIGNED' => 'Neu Zugewiesene Benutzer',
'LBL_ASSIGN_SUBMIT' => 'Einreichen',
'LBL_CONFIRM_SUBMIT' => 'Einreichen',
'LBL_RESOLUTION' => 'Auflösung',
'LBL_ASSIGNED' => 'Zugewiesene Benutzer',
'LBL_ASSIGNEDS' => 'Zugewiesene Benutzer',
'LBL_CONFIRM_STATUS' => 'Status ändern',
'LBL_NEW_STATUS' => 'Neuer Status',
'LBL_RECIPIENT_LIST' => 'Mail an',
'ERR_RECORD_NOT_FOUND' => 'Nehmen Sie nicht gefunden',
'ERR_STATUS_FIELD_NOT_FOUND' => 'Statusfeld nicht gefunden',
'ERR_ROLE_STATUS_NOT_FOUND' => 'Status für Rolle nicht gefunden',
'ERR_ASSIGN_DENIED' => 'Zugriff verweigert',
'ERR_INVALID_ASSIGNED' => 'Ungültige zugewiesenen Benutzer',
'ERR_ENTIRE_LIST_MASS_CONFIRM' => 'Sie können nicht wählen gesamte Liste. Bitte wählen Sie Datensätze ',
'ERR_FIELD_REQUIRED' => 'Feld erforderlich',
'ERR_INVALID_EVENT' => 'Statuswechselist nicht erlaubt',
'ERR_CONFIRM_DENIED' => 'Zugriff verweigert',
'ERR_NO_RECORD' => 'Kein Eintrag gewählt',
'ERR_MODULE_NOT_FOUND' => 'Modul nicht gefunden',
'ERR_SOME_RECORD_NOT_FOUND' => 'Nehmen Sie nicht gefunden',
'ERR_NO_WORKFLOW_FOR' => "Kein Workflow für '#NAME#'",
'ERR_NOT_SAME_WORKFLOW' => "Gewählte Datensätze in verschiedenen Workflows (records '#NAME1#' und '#NAME2#')",
'ERR_NOT_SAME_STATUS' => "Gewählte Datensätze in unterschiedlichen Status (records '#NAME1#' und '#NAME2#')",
'ERR_STATUS_REQUIRED' => 'Status erforderlich',
'ERR_STATUS_NOT_CHANGING' => 'Bitte wählen Sie Folgestatus',
'ERR_ASSIGNED_REQUIRED' => 'Zugeordnete Benutzer erforderlich',
'ERR_CONFIRM_INVALID_FOR' => "Kann nicht Status ändern '#NAME#'",
'ERR_CONFIRM_DENIED_FOR' => "Sie können nicht Status ändern '#NAME#'",
'ERR_ASSIGNED_INVALID_FOR' => "Ungültige zugewiesen Benutzer '#NAME#'",
'ERR_VALIDATE_FUNCTION_NOT_FOUND' => 'Kann nicht validieren',

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
