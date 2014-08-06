<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
		$subpanel_layout = array(
	
		'top_buttons' => array(
		),
	
		'where' => '',
	
	
		'list_fields' => array(
	        'name'=>array(
			 	'vname' => 'LBL_LIST_NAME',
				'widget_class' => 'SubPanelDetailViewLink',
				'width' => '70%',
			),
			'wf_module'=>array(
			 	'vname' => 'LBL_WF_MODULE',
				'width' => '15%',
			),
		),
	);
?>
