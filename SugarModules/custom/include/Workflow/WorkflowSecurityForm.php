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
        parent::setBean($bean);
        $statusId = $this->getStatusId($bean);
        if($statusId) {
            $this->setupFieldsByStatusId($statusId);
            $this->setupRelationshipsByStatusId($statusId);
            return;
        }
        $this->setDefaultFieldsMode(SecurityForm::MODE_DEFAULT_ENABLED);
        $this->setDisabledFields(array());
    }

    protected function setupFieldsByStatusId($statusId) {
        $list = BeanFactory::newBean('FormFieldsLists');
        $list = $list->retrieve_by_string_fields(array('parent_id' => $statusId, 'parent_type' => 'WFStatuses', 'list_type' => 'enabled_fields'));
        if($list) {
            $this->setDefaultFieldsMode(SecurityForm::MODE_DEFAULT_DISABLED);
            $fields = array();
            if($list->load_relationship('fields')) {
                foreach($list->fields->getBeans() as $fieldBean) {
                    $fields[] = $fieldBean->name;
                }
            }
            $this->setEnabledFields($fields);
        }
    }

    protected function setupRelationshipsByStatusId($statusId) {
        $list = BeanFactory::newBean('FormFieldsLists');
        $list = $list->retrieve_by_string_fields(array('parent_id' => $statusId, 'parent_type' => 'WFStatuses', 'list_type' => 'disabled_rels'));
        if($list) {
            $this->setDefaultRelationshipsMode(SecurityForm::MODE_DEFAULT_ENABLED);
            $fields = array();
            if($list->load_relationship('fields')) {
                foreach($list->fields->getBeans() as $fieldBean) {
                    $fields[] = $fieldBean->name;
                }
            }
            $this->setDisabledRelationships($fields);
        }
    }

    protected function getDataChangesToUnset($bean) {
        require_once 'custom/include/Workflow/WFManager.php';
        $diff = parent::getDataChangesToUnset($bean);
        $statusField = WFManager::getBeanStatusField($bean);
        foreach($diff as $field => $changes) {
            if($field == $statusField) {
                unset($diff[$field]);
            }
        }
        return $diff;
    }

    protected function getStatusId($bean) {
        require_once 'custom/include/Workflow/WFManager.php';
        if(WFManager::isBeanInWorkflow($bean)) {
            $statusField = WFManager::getBeanStatusField($bean);
            if($statusField && $bean->$statusField) {
                return WFManager::getStatusIdByName($bean->$statusField, $bean->module_name);
            }
        }
        return false;
    }
}
