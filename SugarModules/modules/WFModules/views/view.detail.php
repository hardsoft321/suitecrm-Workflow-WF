<?php

require_once 'include/MVC/View/views/view.detail.php';

class WFModulesViewDetail extends ViewDetail
{
    public function preDisplay()
    {
        $GLOBALS['sugar_config']['enable_line_editing_detail'] = false;
        parent::preDisplay();
    }

    public function display()
    {
        global $db;
        $utility_fields_tpl = '';
        $utility_fields_file_name = $this->bean->getUtilityVardefsFileName();
        $utility_fields_tpl .= 'Vardefs file: ';
        if (!file_exists($utility_fields_file_name)) {
            $utility_fields_tpl .= <<<TPL
<input type="submit" class="button" title="Create Vardefs File"
id="create-vardefs-btn"
onclick="var form=document.forms['formDetailView'];
form.return_module.value='WFModules';
form.return_action.value='DetailView';
form.return_id.value='{$this->bean->id}';
form.action.value='createVardefs';
form.module.value='WFModules';
form.submit()"
name="Create" value="Create"/>
TPL;
        }
        else {
            $utility_fields_tpl .= 'Ok';

            $utility_fields_tpl .= <<<TPL
 <input type="submit" class="button remove-vardefs-btn" title="Remove Vardefs File"
onclick="var form=document.forms['formDetailView'];
form.return_module.value='WFModules';
form.return_action.value='DetailView';
form.return_id.value='{$this->bean->id}';
form.action.value='removeVardefs';
form.module.value='WFModules';
form.submit()"
name="Remove" value="Remove"/>
TPL;

            $utility_fields_tpl .= '<br />Db fields: ';
            $moduleBean = BeanFactory::newBean($this->bean->wf_module);
            $check = $db->getOne("SELECT COUNT(*) + 1 FROM {$moduleBean->table_name} WHERE wf_id IS NULL");
            if ($check !== false) {
                $utility_fields_tpl .= 'Ok';
            }
            else {
                $utility_fields_tpl .= 'Fail - <a href="index.php?module=Administration&action=repair">'
                    . translate('LBL_QUICK_REPAIR_AND_REBUILD', 'Administration') . '</a>';
            }
        }

        $this->ss->assign('utility_fields', $utility_fields_tpl);

        $logic_hooks_tpl = '';
        $logic_hooks = $this->bean->getLogicHooks();
        $add_logic = false;
        foreach($logic_hooks as $hook) {
            $add_logic = $add_logic || wf_check_logic_hook_file($hook['module'], $hook['hook'], array($hook['order'], $hook['description'],  $hook['file'], $hook['class'], $hook['function']));
        }
        $logic_hooks_tpl .= 'Hooks: ';
        if ($add_logic) {
            $logic_hooks_tpl .= <<<TPL
<input type="submit" class="button" title="Add Logic Hooks"
id="create-hooks-btn"
onclick="var form=document.forms['formDetailView'];
form.return_module.value='WFModules';
form.return_action.value='DetailView';
form.return_id.value='{$this->bean->id}';
form.action.value='createHooks';
form.module.value='WFModules';
form.submit()"
name="createHooks" value="Add Logic Hooks"/>
TPL;
        }
        else {
            $logic_hooks_tpl .= 'Ok';

            $logic_hooks_tpl .= <<<TPL
 <input type="submit" class="button" title="Remove Logic Hooks"
id="remove-hooks-btn"
onclick="var form=document.forms['formDetailView'];
form.return_module.value='WFModules';
form.return_action.value='DetailView';
form.return_id.value='{$this->bean->id}';
form.action.value='removeHooks';
form.module.value='WFModules';
form.submit()"
name="removeHooks" value="Remove Logic Hooks"/>
TPL;
        }

        $this->ss->assign('logic_hooks', $logic_hooks_tpl);

        parent::display();
    }
}

function wf_check_logic_hook_file($module_name, $event, $action_array)
{
    require_once 'include/utils/logic_utils.php';
    $add_logic = false;

    if (file_exists("custom/modules/$module_name/logic_hooks.php")) {
        $hook_array = get_hook_array($module_name);

        if (check_existing_element($hook_array, $event, $action_array) == true) {
            //the hook at hand is present, so do nothing
        } else {
            $add_logic = true;
        }
    } else {
        $add_logic = true;
    }
    return $add_logic;
}
