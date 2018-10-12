<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
		$subpanel_layout = array(
	
		'top_buttons' => array(
		),
	
		'where' => '',
	
	
		'list_fields' => array(
			'status1_name'=>array(
			 	'vname' => 'LBL_STATUS1_NAME',
				'width' => '15%',
				'widget_class' => 'SubPanelDetailViewLink',
			),
			'status2_name'=>array(
			 	'vname' => 'LBL_STATUS2_NAME',
				'width' => '15%',
				'widget_class' => 'SubPanelDetailViewLink',
			),
			'workflow_name'=>array(
			 	'vname' => 'LBL_WORKFLOW_NAME',
				'width' => '15%',
			),
		),
	);
?>
