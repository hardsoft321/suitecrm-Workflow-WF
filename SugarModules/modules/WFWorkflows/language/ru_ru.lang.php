<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$mod_strings = array (

'LBL_MODULE_NAME' => 'Маршруты' ,
'LBL_MODULE_TITLE' => 'Маршруты - ГЛАВНАЯ' ,
'LBL_SEARCH_FORM_TITLE' => 'Список маршрутов' ,
'LBL_LIST_FORM_TITLE' => 'Список маршрутов' ,
'LBL_NEW_FORM_TITLE' => 'Добавить маршрут' ,
'LBL_NEW_FORM_LINK' => 'Добавить маршрут' ,

'LBL_INFORMATION' => 'Основная информация' ,

'LBL_NAME' => 'Название:' ,
'LBL_WF_MODULE' => 'Модуль',
//'LBL_TYPE' => 'Тип',
'LBL_STATUS_FIELD' => 'Название поля со статусом',


'LBL_LIST_NAME' => 'Название',
'LBL_LIST_WF_MODULE' => 'Модуль',

'LBL_EXPORT_NAME' => 'Название',
'LBL_EXPORT_WF_MODULE' => 'Модуль',

'LBL_UNIQ_NAME' => 'Уникальное имя',

'LBL_BEAN_TYPE' => 'Значение поля тип в модуле',

//custom/include/Workflow
'LBL_TOGGLE_BUTTON' => 'Панель согласования',
'LBL_ASSIGNED_CHANGE_TITLE' => 'Смена ответственного',
'LBL_ROLE' => 'Роль',
'LBL_NEW_ASSIGNED' => 'Новый ответственный',
'LBL_ASSIGN_SUBMIT' => 'Изменить',
'LBL_CONFIRM_SUBMIT' => 'Изменить',
'LBL_RESOLUTION' => 'Резолюция',
'LBL_ASSIGNED' => 'Ответственный',
'LBL_ASSIGNEDS' => 'Ответственные',
'LBL_CONFIRM_STATUS' => 'Согласование',
'LBL_NEW_STATUS' => 'Новый статус',
'LBL_RECIPIENT_LIST' => 'Список рассылки уведомления',
'ERR_RECORD_NOT_FOUND' => 'Запись не найдена',
'ERR_STATUS_FIELD_NOT_FOUND' => 'Не удается определить статус',
'ERR_ROLE_STATUS_NOT_FOUND' => 'Статусы для указанной роли не найдены',
'ERR_ASSIGN_DENIED' => 'У Вас нет прав на смену ответственного',
'ERR_INVALID_ASSIGNED' => 'Указанного пользователя нельзя назначить ответственным',
'ERR_ENTIRE_LIST_MASS_CONFIRM' => 'Перевод всех записей не поддерживается. Пожалуйcта, выберите записи, находящиеся на одном статусе.',
'ERR_FIELD_REQUIRED' => 'Не заполнено поле',
'ERR_INVALID_EVENT' => 'Недопустимый переход',// 'Status changing is not allowed',
'ERR_CONFIRM_DENIED' => 'Перевод запрещен',// 'Access Denied',
'ERR_NO_RECORD' => 'Ни одна запись не выбрана',
'ERR_MODULE_NOT_FOUND' => 'Модуль не найден',
'ERR_SOME_RECORD_NOT_FOUND' => 'Не все записи найдены',
'ERR_NO_WORKFLOW_FOR' => "Нет маршрута для записи '#NAME#'",
'ERR_NOT_SAME_WORKFLOW' => "Невозможно перевести на один статус записи, находящиеся в разных маршрутах (записи '#NAME1#' и '#NAME2#')",
'ERR_NOT_SAME_STATUS' => "Невозможно перевести на один статус записи, находящиеся на разных статусах (записи '#NAME1#' и '#NAME2#')",
'ERR_STATUS_REQUIRED' => 'Необходимо выбрать статус',
'ERR_STATUS_NOT_CHANGING' => 'Необходимо выбрать следующий статус',
'ERR_ASSIGNED_REQUIRED' => 'Необходимо выбрать ответственного',
'ERR_CONFIRM_INVALID_FOR' => "Невозможно сменить статус для записи '#NAME#'",
'ERR_CONFIRM_DENIED_FOR' => "Вы не можете сменить статус для записи '#NAME#'",
'ERR_ASSIGNED_INVALID_FOR' => "Ответственный задан не верно для записи '#NAME#'",
'ERR_VALIDATE_FUNCTION_NOT_FOUND' => 'При валидации перехода произошла ошибка',

'DefaultGroupUserList' => 'Групповые пользователи в роли и в группе',
'DefaultNonGroupUserList' => 'Все кроме групповых в роли и в группе',
'DefaultRole2UserList' => 'Все в роли 2 и в группе',
'DefaultUserList' => 'Все в роли и в группе',
'OwnerUserList' => 'Ответственный',
'StatusAssignedUserList' => 'Закрепленный за ролью или групповой',
'StatusAssignedDefaultUserList' => 'Закрепленный за ролью или все в роли и в группе',
'SFFormFieldsRequired' => 'Проверка обязательных для статуса полей',
'DefaultCurrentUserList' => 'Текущий пользователь в роли и в группе',
'ParentStatusAssignedUserList' => 'Закрепленный за ролью в родительской записи или групповой',
'EmptyUserList' => 'Никто',

);


?>
