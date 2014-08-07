<?php

function wf_getNewStatuses($focus = null, $name = null, $value = null, $view = null) {
    require_once "custom/include/Workflow/WFManager.php";
    
    if($view == 'SearchForm_basic_search' || $view == 'SearchForm_advanced_search' || $view == 'list_view') { /* list_view defined in upgrade_unsafe/../SugarBean.php */
        return WFManager::getAllStatuses($focus);
    }
    
    $res = array();
    if($view == 'DetailView' || ($view == 'EditView' && !empty($focus->fetched_row['id']))) {
        $status = WFManager::getBeanCurrentStatus($focus);
        $res = array_merge($res, array($status->uniq_name => $status->name));
    }
    if($view == 'EditView'/* && empty($focus->fetched_row['id'])*/ || $view == 'QuickCreate') {
        $res = array_merge($res, WFManager::getNextStatuses($focus));
    }
    return $res;
}

function wf_getModulesList($focus = null, $name = null, $value = null, $view = null) {
    global $db;
    global $app_list_strings;
    $q = "SELECT DISTINCT wf_module FROM wf_modules WHERE deleted = 0";
    $qr = $db->query($q);
    $res = array();
    while ($row = $db->fetchByAssoc($qr)) {
        $moduleName = $row['wf_module'];
        $res[$moduleName] = $app_list_strings['moduleList'][$moduleName];
    }
    return $res;
}

function wf_getFilterFunctions($focus = null, $name = null, $value = null, $view = null) {
    $files = glob(__DIR__.'/functions/filters/*.php');
    $res = array(''=>'');
    foreach($files as $f) {
        $name = basename($f, '.php');
        $res[$name] = $name;
    }
    return $res;
}

function wf_getProcedures($focus = null, $name = null, $value = null, $view = null) {
    $files = glob(__DIR__.'/functions/procedures/*.php');
    $res = array(''=>'');
    foreach($files as $f) {
        $name = basename($f, '.php');
        $res[$name] = $name;
    }
    return $res;
}

?>
