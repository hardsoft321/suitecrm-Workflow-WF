<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 */

require_once('modules/WFWorkflows/wfworkflow_fields.php');

class WFWorkflowsController extends SugarController
{
    protected function action_CheckWorkflows() {
        $this->view = 'checkworkflows';
    }

    protected function action_FunctionsDoc() {
        $this->view = 'functionsdoc';
    }

    public function pre_save() {
        parent::pre_save();
        if (!empty($this->bean->bean_type)) {
            $this->bean->bean_type = str_replace('^'.WF_EMPTY_BEANTYPE_VALUE.'^', '^^', $this->bean->bean_type);
        }
    }

    public function action_createVardefs()
    {
        $this->bean->createUtilityVardefsFile();
    }

    public function post_createVardefs()
    {
        parent::post_save();
    }

    public function action_removeVardefs()
    {
        $this->bean->removeUtilityVardefsFile();
    }

    public function post_removeVardefs()
    {
        parent::post_save();
    }

    public function action_createLang()
    {
        if (!empty($_POST['lang'])) {
            $this->bean->createLangVardefsFile($_POST['lang']);
        }
    }

    public function post_createLang()
    {
        parent::post_save();
    }

    public function action_removeLang()
    {
        if (!empty($_POST['lang'])) {
            $this->bean->removeLangVardefsFile($_POST['lang']);
        }
    }

    public function post_removeLang()
    {
        parent::post_save();
    }
}
