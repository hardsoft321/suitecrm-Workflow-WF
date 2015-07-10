<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$listViewDefs['WFWorkflows'] = array(
  'NAME' => array(
    'width' => '40', 
    'label' => 'LBL_LIST_NAME', 
    'link' => true,
    'default' => true
  ),
  'WF_MODULE' => array(
    'width' => '40', 
    'label' => 'LBL_LIST_WF_MODULE', 
    'default' => true
  ),
);
?>
