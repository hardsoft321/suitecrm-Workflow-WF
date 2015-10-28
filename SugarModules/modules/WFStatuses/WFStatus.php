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
     *
     * Удалено, так как ответственного на статусе привязали к роли, а не к статусу.
     * Тогда нужно следить, чтобы на статусах с одинаковой ролью были указаны одни и те же функции.
     */
    //var $parent_status_id;
	var $wf_module;
	var $role_id;
	var $role_name;
	
	/**
	 * Id дополнительной роли.
	 * Для использования в некоторых функциях списков пользователей.
	 */
	var $role2_id;
		
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
     * Функция, выбирающая список пользователей, находясь на данном статусе (согласующие), для назначения нового ответственного
     */
    var $confirm_list_function;
    
    /**
     * Функция, выбирающая список пользователей, находясь на данном статусе, для проверки согласующего при переходе на статус
     */
    var $confirm_check_list_function;
    
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

	function ACLAccess($view,$is_owner='not_set')
	{
		return $GLOBALS['current_user']->isAdmin();
	}
}

require_once ("custom/include/Workflow/utils.php");
