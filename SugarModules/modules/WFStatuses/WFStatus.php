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

	function ACLAccess($view, $is_owner='not_set', $in_group = 'not_set')
	{
		return $GLOBALS['current_user']->isAdmin();
	}

	function getEventsToStatusQuery()
	{
		return "
SELECT wf_events.*, s1.name AS status1_name, s2.name AS status2_name, w.name AS workflow_name
FROM wf_events
LEFT JOIN wf_statuses s1 ON wf_events.status1_id = s1.id
LEFT JOIN wf_statuses s2 ON wf_events.status2_id = s2.id
LEFT JOIN wf_workflows w ON w.id = wf_events.workflow_id
WHERE s2.id = '{$this->id}'
  AND wf_events.deleted = 0
  AND (s1.deleted = 0 OR (s1.id IS NULL AND (wf_events.status1_id IS NULL OR wf_events.status1_id = '')))
  AND s2.deleted = 0 AND w.deleted = 0
";
	}

	function getEventsFromStatusQuery()
	{
		return "
SELECT wf_events.*, s1.name AS status1_name, s2.name AS status2_name, w.name AS workflow_name
FROM wf_events, wf_statuses s1, wf_statuses s2, wf_workflows w
WHERE s1.id = '{$this->id}'
  AND wf_events.status1_id = s1.id AND wf_events.status2_id = s2.id
  AND w.id = wf_events.workflow_id
  AND wf_events.deleted = 0 AND s1.deleted = 0 AND s2.deleted = 0 AND w.deleted = 0
";
	}
}

require_once ("custom/include/Workflow/utils.php");
