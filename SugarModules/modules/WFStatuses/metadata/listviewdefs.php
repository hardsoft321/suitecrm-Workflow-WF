<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$listViewDefs['WFStatuses'] = array(
  'NAME' => array(
    'width' => '40', 
    'label' => 'LBL_LIST_NAME', 
    'link' => true,
    'default' => true
  ),
  'UNIQ_NAME' => array(
    'width' => '40',
    'label' => 'LBL_UNIQ_NAME',
    'default' => true,
    'link' => true,
  ),
  'WF_MODULE' => array(
    'width' => '40',
    'label' => 'LBL_WF_MODULE',
    'default' => true,
    'link' => true,
  ),
);
?>
