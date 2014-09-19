<?php
global $current_user;
if(is_admin($current_user)) {
    $app_list_strings['moduleList']['WFModules'] = 'Модули в маршрутизации';
    $app_list_strings['moduleList']['WFWorkflows'] = 'Маршруты';
    $app_list_strings['moduleList']['WFStatuses'] = 'Статусы маршрутов';
    $app_list_strings['moduleList']['WFEvents'] = 'Переходы маршрутов';
}

$app_list_strings['in_role_types'] = array(
	'role' => 'Все в роли',
	// 'old' => 'Ранее закрепленный',
	'function' => 'Из функции выбора ответственного',
);
$app_list_strings['out_role_types'] = array(
	'role' => 'Все в роли',
	'assigned' => 'Ответственный на статусе',
	'owner' => 'Владелец записи',
);

$app_list_strings['edit_role_types'] = array(
    'nobody' => 'Никто кроме администратора',
//	'role' => 'Все в роли',
//	'assigned' => 'Ответственный на статусе',
	'owner' => 'Владелец записи',
);

$app_strings['LBL_CONFIRM_LIST'] = 'Журнал согласования';
$app_strings['LBL_USER'] = 'Пользователь';
$app_strings['LBL_STATUS_CHANGE'] = 'Перевод на статус';
$app_strings['LBL_RESOLUTION'] = 'Резолюция';
