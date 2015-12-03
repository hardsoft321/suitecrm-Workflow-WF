<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author  Evgeny Pervushin <pea@lab321.ru>
 * @since version 0.7.9.6
 */

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once ('custom/include/Workflow/WFManager.php');
require_once('modules/SecurityGroups/SecurityGroup.php');
global $current_user, $db;
?>
<style>
.error {color:red}
.success {color:green}
table {border-collapse: collapse;}
table td {vertical-align: top; border: 1px solid #ccc; padding: 5px;}
table table td {padding: 0 5px;}
pre {max-height: 300px; overflow: auto;}
p {margin:5px;}
pre {border: 1px solid #ccc}
</style>
<h1>Отладка маршрута</h1>
<?php
if(!isset($_REQUEST['module'])) {
    echo '<p class="error">Не заполнено поле: module</p>';
    return;
}
if(!isset($_REQUEST['record'])) {
    echo '<p class="error">Не заполнено поле: record</p>';
    return;
}
$bean = BeanFactory::getBean($_REQUEST['module'], $_REQUEST['record']);
if (empty($bean->id)) {
    echo '<p class="error">Запись не найдена</p>';
    return;
}

echo '<h2>Хуки</h2>';
$logicHook = new LogicHook();
$logicHook->setBean($bean);
echo '<pre>';
print_r($logicHook->getHooks($bean->module_name));
print_r($logicHook->getHooks(''));
echo '</pre>';

echo '<h2>Запись</h2>';
if(isset($bean->name)) {
    echo "<p>Имя: <a href=\"index.php?module={$bean->module_name}&action=DetailView&record={$bean->id}\">{$bean->name}</a></p>";
}
echo "<p>ID: {$bean->id}</p>";
$typeField = WFManager::getWorkflowTypeField($bean);
echo '<p>Поле типа: ',($typeField ? $typeField : '<span class="error">Не определено</span>'),'</p>';
echo '<p>Тип: ',$bean->$typeField,'</p>';
echo '<p>Предполагаемый маршрут: ',WFManager::getWorkflowForBean($bean),'</p>';

echo '<p>Маршрут: '.($bean->wf_id ? $bean->wf_id.' "'.(BeanFactory::getBean('WFWorkflows', $bean->wf_id)->name).'"' : '<span class="error">Не найден</span>').'</p>';
$statusField = WFManager::getBeanStatusField($bean);
if(!$statusField) {
    echo "<p class=\"error\">Поле статуса не определено</p>";
    return;
}
echo "<p>Поле статуса: {$statusField}</p>";
echo "<p>Cтатус: {$bean->$statusField}</p>";
if(isset($bean->assigned_user_id)) {
    echo "<p>Ответственный: ".($bean->assigned_user_id ? BeanFactory::getBean('Users', $bean->assigned_user_id)->user_name : '')."</p>";
}

echo '<h3>Группы записи</h3>';
$groupFocus = new SecurityGroup();
$groups = $groupFocus->getAllRecordGroupsIds($bean->id, $bean->module_name);
echo '<ul>';
foreach($groups as $group_id) {
    echo "<li>".BeanFactory::getBean('SecurityGroups', $group_id)->name."</li>";
}
echo '</ul>';


echo '<h2>Пользователь</h2>';
echo "<p>Имя пользователя: {$current_user->user_name}</p>";
echo "<p>Администратор: ".(is_admin($current_user) ? 'Да' : 'Нет')."</p>";

echo '<h3>Группы пользователя</h3>';
$q =   "SELECT securitygroups.name
        FROM securitygroups, securitygroups_users, users
        WHERE
            securitygroups.id = securitygroups_users.securitygroup_id AND securitygroups_users.user_id = users.id
            AND users.id = '{$current_user->id}'
            AND securitygroups.deleted = 0 AND securitygroups_users.deleted = 0 AND users.deleted = 0 AND users.status != 'Inactive'";
$dbRes = $db->query($q);
echo '<ul>';
while($row = $db->fetchByAssoc($dbRes)) {
    echo "<li>{$row['name']}</li>";
}
echo '</ul>';

echo '<h3>Роли пользователя</h3>';
$q =   "SELECT acl_roles.name
        FROM users, acl_roles_users, acl_roles
        WHERE
            acl_roles_users.user_id = users.id
            AND users.id = '{$current_user->id}'
            AND acl_roles_users.role_id = acl_roles.id
            AND users.deleted = 0 AND users.status != 'Inactive' AND acl_roles_users.deleted = 0
            AND acl_roles.deleted = 0";
$dbRes = $db->query($q);
echo '<ul>';
while($row = $db->fetchByAssoc($dbRes)) {
    echo "<li>{$row['name']}</li>";
}
echo '</ul>';

echo "<h2>Статус</h2>";
echo statusInfo($bean, $bean->$statusField);
echo WFManager::canChangeStatus($bean, $bean->$statusField) ? '<p class="success">Смена статуса разрешена</p>' : '<p class="error">Смена статуса запрещена</p>';

function statusInfo($bean, $status) {
    global $db, $current_user;
    ob_start();
    $q = "SELECT id, name, uniq_name, role_id, role2_id, edit_role_type,
                            assigned_list_function, confirm_list_function, front_assigned_list_function, 
                            confirm_check_list_function, isfinal
                        FROM wf_statuses WHERE uniq_name = '{$status}' AND wf_module = '{$bean->module_name}' AND deleted = 0";
    $dbRes = $db->query($q);
    $status = array();
    while($row = $db->fetchByAssoc($dbRes)) {
        $status[] = $row;
    }
    if(empty($status)) {
        echo "<p class=\"error\">Статус не найден в таблице</p>";
        return;
    }
    if(count($status) > 1) {
        echo "<p class=\"error\">В таблице несколько записей для статуса</p>";
    }
    $status = reset($status);
    echo '<table>';
    foreach($status as $name => $value) {
        echo "<tr><td>{$name}</td><td>{$value}";
        if(in_array($name, array('assigned_list_function','confirm_list_function','front_assigned_list_function','confirm_check_list_function'))) {

            $functionName = $status[$name] ? $status[$name] : 'DefaultUserList';
            if(file_exists('custom/include/Workflow/functions/userlists/'.$functionName.'.php')) {
                require_once 'custom/include/Workflow/functions/BaseUserList.php';
                require_once 'custom/include/Workflow/functions/userlists/'.$functionName.'.php';
                $func = new $functionName;
                $func->status_data = array(
                    'id' => $status['id'],
                    'role_id' => $status['role_id'],
                    'role2_id' => $status['role2_id'],
                );
                $userList = $func->getList($bean);
                echo array_key_exists($current_user->id, $userList) ? ' <span class="success">Соответствует</span>' : ' <span class="error">Не соответствует</span>';
            }
            else {
                echo "<span class=\"error\">Файл {$functionName} не найден</span>";
            }
        }
        elseif($value && in_array($name, array('role_id','role2_id'))) {
            echo ' - ',BeanFactory::getBean('ACLRoles', $value)->name;
        }
        echo "</td></tr>";
    }
    echo '</table>';
    return ob_get_clean();
}


echo '<h2>Переходы</h2>';
$q = "SELECT s2.uniq_name, s2.name, e.filter_function, e.sort, e.func_params FROM wf_events e
            LEFT JOIN wf_statuses s2 ON s2.id = e.status2_id
            WHERE
                e.status1_id IN (SELECT id FROM wf_statuses WHERE uniq_name='{$bean->$statusField}' AND wf_module = '{$bean->module_name}' AND deleted = 0)
                AND e.workflow_id = '{$bean->wf_id}'
                AND e.deleted = 0
            ORDER BY e.sort
            ";
$dbRes = $db->query($q);
echo '<table>';
$head = $db->getFieldsArray($dbRes, true);
echo "<tr>";
foreach($head as $field) {
    echo "<th>$field</th>";
}
echo "<th>статус</th>";
echo "</tr>";
while($row = $db->fetchByAssoc($dbRes, false)) {
    echo "<tr>";
    foreach($row as $name => $value) {
        echo "<td>{$value}";
        if($value && $name == 'filter_function') {
            echo "<br/>";
            $filter_function = $value;
            if(file_exists('custom/include/Workflow/functions/filters/'.$filter_function.'.php')) {
                require_once 'custom/include/Workflow/functions/filters/'.$filter_function.'.php';
                $filter = new $filter_function();
                $filter->event_data = $row;
                if(empty($row['func_params'])) {
                    $filter->func_params = array();
                }
                else {
                    $filter->func_params = json_decode($row['func_params'], true);
                    if(json_last_error() != JSON_ERROR_NONE) {
                        echo "Error parsing func_params = {$row['func_params']}, error = ".json_last_error();
                    }
                }
                echo $filter->checkBean($bean) ? '<span class="success">Доступен</span>' : '<span class="error">Не доступен</span>';
            }
            else {
                echo "<span class=\"error\">Файл {$filter_function} не найден</span>";
            }
        }
        echo "</td>";
    }
    echo '<td>',statusInfo($bean, $row['uniq_name']),'</td>';
    echo "</tr>";
}
echo '</table>';


echo '<h2>Панель согласования</h2>';
$editFormData = WFManager::getEditFormData($bean);
echo '<pre>';print_r($editFormData);echo '</pre>';

echo '<br/><p><b>*** Конец отладки ***</b></p>';
