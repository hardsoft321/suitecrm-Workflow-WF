<?php
class SFFormFieldsRequired extends BaseValidator {

    public function validate($bean) {
        $errors = array();
        $list = BeanFactory::newBean('FormFieldsLists');
        $list = $list->retrieve_by_string_fields(array('parent_id' => $this->event_id, 'parent_type' => 'WFEvents', 'list_type' => 'required_fields'));
        if($list && $list->load_relationship('fields')) {
            foreach($list->fields->getBeans() as $fieldBean) {
                $field = $fieldBean->name;
                $val = $bean->$field;
                if(empty($val)) {
                    $errors[] = "Не заполнено поле '".$this->translateField($bean, $field)."'";
                }
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
