<?php

$admin_options_defs = array();
$admin_options_defs['WF']['Workflows'] = array (
    'WorkFlow',
    'LBL_WF_WORKFLOWS_TITLE',
    'LBL_WF_WORKFLOWS_DESC',
    './index.php?module=WFWorkflows&action=index',
);
$admin_options_defs['WF']['Modules'] = array (
    'WorkFlow',
    'LBL_WF_MODULES_TITLE',
    'LBL_WF_MODULES_DESC',
    './index.php?module=WFModules&action=index',
);
$admin_options_defs['WF']['Statuses'] = array (
    'WorkFlow',
    'LBL_WF_STATUSES_TITLE',
    'LBL_WF_STATUSES_DESC',
    './index.php?module=WFStatuses&action=index',
);
$admin_options_defs['WF']['Events'] = array (
    'WorkFlow',
    'LBL_WF_EVENTS_TITLE',
    'LBL_WF_EVENTS_DESC',
    './index.php?module=WFEvents&action=index',
);

/* Находим панель в массиве панелей */
/* Ищем по названию панели */
$groupHeader = null;
$groupHeaderKey = -1;
foreach ($admin_group_header as $key => $value) {
    if ( $value[0] === 'LBL_STUDIO_TITLE' ) {
        $groupHeader = $value;
        $groupHeaderKey = $key;
    }
}

/* Если нашли */
if ( $groupHeaderKey !== -1 ) {
    /* Добавляем нашу панель в массив панелей */
    $groupHeader[3] = array_merge($groupHeader[3], $admin_options_defs);
    $admin_group_header[$groupHeaderKey] = $groupHeader;
} else {
    /* Иначе создаем новый блок */
    $admin_group_header[] = array (
        'LBL_WF_WORKFLOW_TITLE',
        '',
        false,
        $admin_options_defs,
        '',
    );
}
