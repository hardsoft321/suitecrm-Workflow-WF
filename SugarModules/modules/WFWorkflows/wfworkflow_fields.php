<?php
define('WF_EMPTY_BEANTYPE_VALUE', '_WF_EMPTY_BEANTYPE_VALUE_');

function wfworkflow_beantype_options($focus, $name, $value, $view)
{
    return !empty($focus->wf_module)
        ? wfworkflow_module_beantypes($focus->wf_module)
        : array();
}

function wfworkflow_module_beantypes($module, $typeField = null)
{
    global $db;
    $bean = BeanFactory::newBean($module);
    if (!$bean) {
        return array();
    }
    if ($typeField === null) {
        $q = "SELECT type_field FROM wf_modules WHERE wf_module = ".$db->quoted($module)." AND deleted = 0";
        $typeField = $db->getOne($q);
    }
    if(isset($bean->field_defs[$typeField]['options'])) {
        $optionsKey = $bean->field_defs[$typeField]['options'];
        $types = $GLOBALS['app_list_strings'][$optionsKey];
        if (isset($types[''])) {
            $emptyText = $types[''];
            unset($types['']);
            $types = array_merge(array(WF_EMPTY_BEANTYPE_VALUE => $emptyText), $types);
        }
        return $types;
    }
    return array();
}

function wfworkflow_statusfield_options($focus, $name, $value, $view)
{
    return !empty($focus->wf_module)
        ? wfworkflow_module_statusfields($focus->wf_module)
        : array();
}

function wfworkflow_module_statusfields($module, $typeField = null)
{
    global $db;
    $bean = BeanFactory::newBean($module);
    if (!$bean) {
        return array();
    }
    if ($typeField === null) {
        $q = "SELECT type_field FROM wf_modules WHERE wf_module = ".$db->quoted($module)." AND deleted = 0";
        $typeField = $db->getOne($q);
    }
    $options = array();
    $options[''] = '';
    foreach ($bean->field_defs as $def) {
        if (($def['type'] != 'enum') || (!empty($def['source']) && $def['source'] == 'non-db')) {
            continue;
        }
        if ($def['name'] == $typeField) {
            continue;
        }
        $name = $def['name'];
        $vname = $name;
        if (!empty($def['vname'])) {
            $label = translate($def['vname'], $module);
            if (!empty($label)) {
                $vname = rtrim($label, ':');
            }
        }
        $options[$name] = $vname;
    }
    asort($options);
    return $options;
}
