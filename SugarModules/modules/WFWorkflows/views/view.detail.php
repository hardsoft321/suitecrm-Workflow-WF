<?php

require_once 'include/MVC/View/views/view.detail.php';

class WFWorkflowsViewDetail extends ViewDetail
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
form.return_module.value='WFWorkflows';
form.return_action.value='DetailView';
form.return_id.value='{$this->bean->id}';
form.action.value='createVardefs';
form.module.value='WFWorkflows';
form.submit()"
name="Create" value="Create"/>
TPL;
        }
        else {
            $utility_fields_tpl .= 'Ok - <a href="index.php?module=Administration&action=repair">'
                . translate('LBL_QUICK_REPAIR_AND_REBUILD', 'Administration') . '</a>';
            $utility_fields_tpl .= <<<TPL
 <input type="submit" class="button" title="Create Vardefs File"
id="remove-vardefs-btn"
onclick="var form=document.forms['formDetailView'];
form.return_module.value='WFWorkflows';
form.return_action.value='DetailView';
form.return_id.value='{$this->bean->id}';
form.action.value='removeVardefs';
form.module.value='WFWorkflows';
form.submit()"
name="Remove" value="Remove"/>
TPL;
        }

        $this->ss->assign('utility_fields', $utility_fields_tpl);

        $status_settings_tpl = '';
        foreach ($GLOBALS['sugar_config']['languages'] as $lang => $langName) {
            $status_settings_file_name = $this->bean->getLangVardefsFileName($lang);
            $status_settings_tpl .= $langName . ': ';
            if (!file_exists($status_settings_file_name)) {
                $status_settings_tpl .= <<<TPL
<input type="submit" class="button create-lang-btn" title="Create Vardefs File"
onclick="var form=document.forms['formDetailView'];
form.return_module.value='WFWorkflows';
form.return_action.value='DetailView';
form.return_id.value='{$this->bean->id}';
form.action.value='createLang';
form.module.value='WFWorkflows';
var langInput = document.createElement('input');
langInput.type = 'hidden';
langInput.name = 'lang';
langInput.value = '{$lang}';
form.appendChild(langInput);
form.submit()"
name="Create" value="Create"/>
TPL;
            }
            else {
                $status_settings_tpl .= 'Ok - <a href="index.php?module=Administration&action=repair">'
                    . translate('LBL_QUICK_REPAIR_AND_REBUILD', 'Administration') . '</a>';
                $status_settings_tpl .= <<<TPL
 <input type="submit" class="button remove-lang-btn" title="Remove Vardefs File"
onclick="var form=document.forms['formDetailView'];
form.return_module.value='WFWorkflows';
form.return_action.value='DetailView';
form.return_id.value='{$this->bean->id}';
form.action.value='removeLang';
form.module.value='WFWorkflows';
var langInput = document.createElement('input');
langInput.type = 'hidden';
langInput.name = 'lang';
langInput.value = '{$lang}';
form.appendChild(langInput);
form.submit()"
name="Remove" value="Remove"/>
TPL;
            }
            $status_settings_tpl .= '<br />';
        }
        $this->ss->assign('status_settings', $status_settings_tpl);

        parent::display();
    }
}
