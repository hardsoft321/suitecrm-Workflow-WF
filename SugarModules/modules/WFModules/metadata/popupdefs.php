<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $mod_strings;

$popupMeta = array('moduleMain' => 'WFModule',
						'varName' => 'WFMODULE',
						'orderBy' => 'name',
//						'whereClauses' => 
//							array('module' => 'budgets.name'),
						'searchInputs' =>
							array('wf_module'),
						'listviewdefs' => array(
							'WF_MODULE' => array (
								'width'   => '30',  
								'label'   => 'LBL_WF_MODULE', 
								'link'    => true,
							'default' => true),
							'TYPE_FIELD' => array (
								'width'   => '30',  
								'label'   => 'LBL_TYPE_FIELD', 
								'link'    => true,
							'default' => true),
							'default' => true),
                                              
						'searchdefs'   => array(
										 	'wf_module', 'type_field'
										  )
						);


?>
