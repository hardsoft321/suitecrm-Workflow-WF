<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $mod_strings;

$popupMeta = array('moduleMain' => 'WFStatus',
						'varName' => 'WFSTATUS',
						'orderBy' => 'wf_module',
//						'whereClauses' => 
//							array('module' => 'budgets.name'),
						'searchInputs' =>
							array('wf_module','name'),
						'listviewdefs' => array(
							'NAME' => array (
								'width'   => '30',  
								'label'   => 'LBL_NAME', 
								'link'    => true,
							'default' => true),
                            'WF_MODULE' => array (
								'width'   => '30',  
								'label'   => 'LBL_WF_MODULE', 
								'link'    => true,
							'default' => true),
							
                                                ),
						'searchdefs'   => array(
										 	'name', 'wf_module'
										  )
						);


?>
