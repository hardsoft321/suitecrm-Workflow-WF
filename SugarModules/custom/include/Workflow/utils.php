<?php

function wf_getNewStatuses($focus = null, $name = null, $value = null, $view = null) {
    require_once "custom/include/Workflow/WFManager.php";
    
    if($view == 'SearchForm_basic_search' || $view == 'SearchForm_advanced_search' || $view == 'list_view') { /* list_view defined in upgrade_unsafe/../SugarBean.php */
        return WFManager::getAllStatuses($focus);
    }
    
    $res = array();
    if($view == 'DetailView' || ($view == 'EditView' && !empty($focus->fetched_row['id']))) {
        $status = WFManager::getBeanCurrentStatus($focus);
        if($status)
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

function wf_getValidateFunctions($focus = null, $name = null, $value = null, $view = null) {
    $files = glob(__DIR__.'/functions/validators/*.php');
    $res = array(''=>'');
    require_once __DIR__.'/functions/BaseValidator.php';
    foreach($files as $f) {
        $name = basename($f, '.php');
        require_once $f;
        $func = new $name;
        $res[$name] = $func->getName();
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

function wf_getAssignedListFunctions($focus = null, $name = null, $value = null, $view = null) {
    $files = glob(__DIR__.'/functions/userlists/*.php');
    $res = array(''=>'');
    require_once __DIR__.'/functions/BaseUserList.php';
    foreach($files as $f) {
        $name = basename($f, '.php');
        require_once $f;
        $func = new $name;
        $res[$name] = $func->getName();
    }
    return $res;
}

function wf_assign_die($msg) {
    sugar_die(wf_translate($msg));
}

function wf_confirm_die($msg) {
    sugar_die(wf_translate($msg));
}

function wf_before_save_die($msg) {
    sugar_die(wf_translate($msg));
}

function wf_translate($msg, $arrReplace = array()) {
    global $current_language;
    $mod_strings = return_module_language($current_language, 'WFWorkflows');
    if(isset($mod_strings[$msg])) {
        $msg = $mod_strings[$msg];
    }
    foreach($arrReplace as $search => $replace) {
        $msg = str_replace($search, $replace, $msg);
    }
    return $msg;
}
?>
