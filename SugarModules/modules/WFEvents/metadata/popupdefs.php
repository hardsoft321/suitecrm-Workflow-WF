<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $mod_strings;

$popupMeta = array('moduleMain' => 'WFEvent',
						'varName' => 'WFEVENT',
						'orderBy' => 'name',
//						'whereClauses' => 
//							array('module' => 'budgets.name'),
						'searchInputs' =>
							array('status1_name','status2_name'),
						'listviewdefs' => array(
							'STATUS1_NAME' => array (
								'width'   => '30',  
								'label'   => 'LBL_STATUS1_NAME', 
								'link'    => true,
							'default' => true),
							'STATUS2_NAME' => array (
								'width'   => '30',  
								'label'   => 'LBL_STATUS2_NAME', 
								'link'    => true,
							'default' => true),
							'WORKFLOW_NAME' => array (
								'width'   => '30',  
								'label'   => 'LBL_WORKFLOW_NAME', 
								'link'    => true,
							'default' => true),
                                                ),
						'searchdefs'   => array(
										 	'status1_name', 'status1_name', 'name', 'workflow_name'
										  )
						);


?>
