<?php
/* STATUSASSIGNED */
$dictionary['wf_status_assigned'] = array(
    'table' => 'wf_status_assigned',
    
    'fields' => array (
       array('name' =>'id', 'type' =>'char', 'len'=>'36')
      , array('name' =>'status_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'record_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'module', 'type' =>'varchar', 'len'=>'100')
      , array('name' =>'user_id', 'type' =>'char', 'len'=>'36', )
      , array('name' =>'date_modified','type' => 'datetime')
      , array('name' =>'created_by', 'type' =>'char', 'len'=>'36')
      , array('name' =>'modified_user_id', 'type' =>'char', 'len'=>'36')
      , array('name' =>'deleted', 'type' =>'bool', 'len'=>'1', 'default'=>'0','required'=>false)
     )
     , 'indices' => array (
       array('name' =>'wf_st_assigned_pk', 'type' =>'primary', 'fields'=>array('id'))
      , array('name' =>'idx_wf_st_assigned_rec_mod', 'type' =>'index', 'fields'=>array('record_id', 'module'))
      , array('name' =>'idx_wf_st_assigned_rel', 'type' =>'index', 'fields'=>array('status_id', 'record_id', 'module', 'user_id'))
      , array('name' =>'idx_wf_st_assigned_user', 'type' =>'index', 'fields'=>array('user_id'))
     ) 	 
);
?>
