<?php
function wfmodule_wfmodule_options($focus, $name, $value, $view)
{
    $options = array();
    $options[''] = '';
    foreach ($GLOBALS['app_list_strings']['moduleList'] as $module => $moduleLabel) {
        $bean = BeanFactory::newBean($module);
        if ($bean) {
            $options[$module] = $moduleLabel;
        }
    }
    asort($options);
    return $options;
}

function wfmodule_typefield_options($focus, $name, $value, $view)
{
    return !empty($focus->wf_module)
        ? wfmodule_module_fields($focus->wf_module)
        : array();
}

function wfmodule_module_fields($module)
{
    $bean = BeanFactory::newBean($module);
    if (!$bean) {
        return array();
    }
    $options = array();
    $options[''] = '';
    foreach ($bean->field_defs as $def) {
        if (($def['type'] != 'enum') || (!empty($def['source']) && $def['source'] == 'non-db')) {
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

function wfmodule_all_typefield_options()
{
    $options = array();
    foreach ($GLOBALS['app_list_strings']['moduleList'] as $module => $moduleLabel) {
        $options[$module] = wfmodule_module_fields($module);
    }
    return $options;
}
