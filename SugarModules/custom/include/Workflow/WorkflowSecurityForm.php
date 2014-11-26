<?php
require_once 'custom/include/SecurityForms/SecurityForm.php';

class WorkflowSecurityForm extends SecurityForm {

    public function __construct($bean = null) {
        parent::__construct();
        if($bean) {
            $this->setBean($bean);
        }
    }

    public function setBean($bean) {
        $status = $this->getStatus($bean);
        if($status) {
            require_once 'modules/FormFieldsScenarios/FormFieldsScenario.php';
            $scenario = $status.'-upd-wf';
            if(FormFieldsScenario::hasScenario($scenario, $bean->module_name)) {
                $this->setDefaultDisabledMode();
                $this->setEnabledFields(FormFieldsScenario::getScenarioFieldsNames($scenario, $bean->module_name));
                return;
            }
        }
        $this->setDefaultEnabledMode();
        $this->setDisabledFields(array());
    }

    public function beforeSave($bean, $event) {
        $this->setBean($bean);
        parent::beforeSave($bean, $event);
    }

    public function getAfterEditView() {
        if(isset($_REQUEST['module']) && isset($_REQUEST['record'])) {
            $bean = BeanFactory::getBean($_REQUEST['module'], $_REQUEST['record']);
            if($bean) {
                $this->setBean($bean);
                return parent::getAfterEditView();
            }
        }
        return '';
    }

    protected function getStatus($bean) {   
        require_once 'custom/include/Workflow/WFManager.php';
        if(!WFManager::isBeanInWorkflow($bean)) {
            return false;
        }
        $statusField = WFManager::getBeanStatusField($bean);
        return $statusField ? $bean->$statusField : false;
    }
}
