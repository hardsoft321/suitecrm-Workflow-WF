<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class WFStatus extends SugarBean {

	var $id;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
	var $name;
	
	/**
     * Имя должно быть уникально в пределах модуля
     */
	var $uniq_name;
	
	/**
     * ID родительского статуса.
     * Используется для наследования свойств статуса.
     * Например, чтобы связать ответственного на таких статусах, как "Заполнение" и "Доработка заполнения"
     */
    var $parent_status_id;
	var $wf_module;
	var $role_id;
	var $role_name;
	
	/**
     * Признак для определения того, кто может ставить ответственного
     * role - Все в роли
     * function - Из функции выбора ответственного
     */
	//var $in_role_type;
	
	/**
     * Признак для определения того, кто может перевести запись с текущего стутуса на другой 
     * (new: кого можно поставить ответственным, перевести на следующий статус может только ответственный)
     * role - все в роли
     * //assigned - ответственный на статусе (таблица wf_status_asssigned)
     * owner(по умолчанию) - владелец (текущий ответственный)
     * function - Из функции выбора ответственного
     */
	//var $out_role_type;
	
	/**
     * Признак для определения того, кто может редактировать и удалять запись, находящуюся на данном статусе
     * 'nobody' => 'Никто кроме администратора',
     * 'owner' => 'Ответственный',
     */
	var $edit_role_type;
	
	/**
     * Функция, выбирающая список пользователей перед переходом на данный статус (групповые ответственные)
     */
    var $front_assigned_list_function;
    
    /**
     * Функция, выбирающая список пользователей, которые могут установить согласующего (ответственные)
     */
    var $assigned_list_function;

	/**
     * Функция, выбирающая список пользователей, находясь на данном статусе (согласующие)
     */
    var $confirm_list_function;
    
	var $table_name = "wf_statuses";
	var $object_name = "WFStatus";
	var $module_dir = 'WFStatuses';
	var $importable = true;

	function WFStatus() {
		parent::SugarBean();
	}

	var $new_schema = true;

	function get_summary_text()
	{
		return "{$this->name}";
	}

	function bean_implements($interface){
		switch($interface){
			case 'ACL':return true;
		}
		return false;
	}
}

require_once ("custom/include/Workflow/utils.php");
