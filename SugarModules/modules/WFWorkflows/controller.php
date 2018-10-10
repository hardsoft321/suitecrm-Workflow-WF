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
}
