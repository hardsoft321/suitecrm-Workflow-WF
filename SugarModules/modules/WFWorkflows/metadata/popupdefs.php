<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $mod_strings;

$popupMeta = array('moduleMain' => 'WFWorkflow',
						'varName' => 'WFWORKFLOW',
						'orderBy' => 'wf_module',
//						'whereClauses' => 
//							array('module' => 'budgets.name'),
						'searchInputs' =>
							array('wf_module','name','status_filed'),
						'listviewdefs' => array(
							'NAME' => array (
								'width'   => '30',  
								'label'   => 'LBL_NAME', 
								'link'    => true,
							'default' => true),
							'TYPE' => array (
								'width'   => '30',  
								'label'   => 'LBL_TYPE', 
								'link'    => true,
							'default' => true),
							'WF_MODULE' => array (
								'width'   => '30',  
								'label'   => 'LBL_WF_MODULE', 
							'default' => true),
                                                ),
						'searchdefs'   => array(
										 	'wf_module', 'name'
										  )
						);


?>
