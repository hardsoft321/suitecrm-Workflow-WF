<?php
class SFFormFieldsRequired extends BaseValidator {

    public function validate($bean) {
        require_once 'modules/FormFieldsScenarios/FormFieldsScenario.php';
        $errors = array();
        $scenario = $this->status1_data['uniq_name'].'-'.$this->status2_data['uniq_name'].'-rqr-wf';
        $fields = FormFieldsScenario::getScenarioFieldsNames($scenario, $bean->module_name);
        foreach($fields as $field) {
            $val = $bean->$field;
            if(empty($val)) {
                $errors[] = "Не заполнено поле '".$this->translateField($bean, $field)."'";
            }
        }
        return $errors;
    }

    public function getName() {
        return 'Проверка обязательных для статуса полей';
    }

    protected function translateField($bean, $field) {
        global $current_language;
        $fieldDefs = $bean->getFieldDefinitions();
        if(isset($fieldDefs[$field]['vname'])) {
            $mod_strings = return_module_language($current_language, $bean->module_name);
            if(isset($mod_strings[$fieldDefs[$field]['vname']])) {
                return $mod_strings[$fieldDefs[$field]['vname']];
            }
        }
        return $field;
    }
}
?>
