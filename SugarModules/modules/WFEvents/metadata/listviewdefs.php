<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$listViewDefs['WFEvents'] = array(
  'STATUS1_ID' => array(
    'width' => '40',
    'label' => 'LBL_LIST_STATUS1_NAME',
    'default' => true,
    'link' => true,
  ),
  'STATUS2_ID' => array(
    'width' => '40',
    'label' => 'LBL_LIST_STATUS2_NAME',
    'default' => true,
    'link' => true,
  ),
  'WORKFLOW_ID' => array(
    'width' => '40',
    'label' => 'LBL_LIST_WORKFLOW_NAME',
    'default' => true,
    'link' => true,
  ),
  'SORT' => array(
    'width' => '40',
    'label' => 'LBL_LIST_SORT',
    'default' => true,
    'link' => false,
  ),
);
?>
