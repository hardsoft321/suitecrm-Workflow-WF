<?php

function wf_getNewStatuses($focus = null, $name = null, $value = null, $view = null) {
    global $app_list_strings;
    require_once "custom/include/Workflow/WFManager.php";
    
    if(in_array($view, array('DetailView', 'SearchForm_basic_search', 'SearchForm_advanced_search', 'list_view'))) { /* list_view defined in upgrade_unsafe/../SugarBean.php */
        if(!empty($app_list_strings[$focus->field_defs[$name]['options']])) {
            return $app_list_strings[$focus->field_defs[$name]['options']];
        }
        return WFManager::getAllStatuses($focus);
    }
    
    $res = array();
    if($view == 'EditView' && !empty($focus->fetched_row['id'])) {
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

function wf_assign_die($msg, $bean = null) {
    wf_die($msg, $bean);
}

function wf_confirm_die($msg, $bean = null) {
    wf_die($msg, $bean);
}

function wf_before_save_die($msg, $bean = null) {
    require_once 'custom/include/Workflow/WFSavingException.php';
    $msg = wf_translate($msg);
    $msg = 'wf_before_save: '.$msg.($bean ? ' '.$bean->module_name.' '.$bean->id : '');
    throw new WFSavingException($msg);
}

function wf_die($msg, $bean = null) {
    $msg = wf_translate($msg);
    $GLOBALS['log']->fatal('WFWorkflow: '.$msg.($bean ? ' '.$bean->module_name.' '.$bean->id : ''));
    sugar_die($msg);
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

function wf_set_mod_strings($mod) {
    global $current_language;
    if(isset($_REQUEST['login_language'])){
        $current_language = ($_REQUEST['login_language'] == $current_language)? $current_language : $_REQUEST['login_language'];
    }
    $GLOBALS['mod_strings'] = return_module_language($current_language, $mod);
}
?>
