<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$fields_array['WFEvent'] = array (

  'column_fields' => array(
    "id",
    "status1_id",
    "status1_name",
    "status2_id",
    "status2_name",
  ),

  'list_fields' => array (
    'id', 
    "status1_id",
    "status1_name",
    "status2_id",
    "status2_name",
	"workflow_id",
    "workflow_name",
  ),
);
?>
