<?php
/**
 * Проверка обязательных для перехода полей.
 * Поля сохранены как FormField в списке FormFieldsList, список привязан к переходу и имеет тип required_fields.
 */
class SFFormFieldsRequired extends BaseValidator {

    public function validate($bean) {
        require_once 'custom/include/Workflow/utils.php';
        $errors = array();
        $list = BeanFactory::newBean('FormFieldsLists');
        $list = $list->retrieve_by_string_fields(array('parent_id' => $this->event_id, 'parent_type' => 'WFEvents', 'list_type' => 'required_fields'));
        if($list && $list->load_relationship('fields')) {
            foreach($list->fields->getBeans() as $fieldBean) {
                $field = $fieldBean->name;
                $link = "";
                $linkfield = "";
                list ($link, $linkfield) = explode ("/", $field);

                if (strpos ($field, "/")) {
                  $fdef = $bean->getFieldDefinition($link);
                } else {
                  $fdef = $bean->getFieldDefinition($field);
                }

                if ($fdef['type'] == 'link') {
                  if (empty($links[$link]['required'])) $links[$link]['required'] = empty($linkfield);
                  else $links[$link]['required'] = $links[$link]['required'] || empty($linkfield);
                  if (!empty($linkfield)) $linkfields[$link][] = $linkfield;
                } else {
                  // assert: empty($link) == TRUE
                  $val = $bean->$field;
                  if(empty($val)) {
                      $errors[] = wf_translate('ERR_FIELD_REQUIRED')." '".$this->translateField($bean, $field)."'";
                  }
                }
            }
//            $errors[] = 'links: ' . print_r($links, true);
//            $errors[] = 'linkfields: ' . print_r($linkfields, true);
            foreach ($links as $k => $v) {
              $bean->load_relationship($k);
              $bs = $bean->$k->getBeans();
              if ($v['required'] && count($bs) == 0) {
                  $errors[] = wf_translate('ERR_LINK_REQUIRED')." '".$this->translateField($bean, $k)."'";
              } else {
                foreach ($bs as $b) {
                  foreach ($linkfields[$k] as $f) {
                    $val = $b->$f;
                    if(empty($val)) {
                        $errors[] = wf_translate('ERR_FIELD_REQUIRED')." '".$this->translateField($bean, $k).".".$b->get_summary_text().".".$this->translateField($b, $f)."'";
                    }
                  }
                }
              }
            }
        }
        return $errors;
    }

    protected function translateField($bean, $field) {
        global $current_language;
        $fieldDefs = $bean->getFieldDefinitions();
        if(isset($fieldDefs[$field]['vname'])) {
            $mod_strings = return_module_language($current_language, $bean->module_name);
            if(isset($mod_strings[$fieldDefs[$field]['vname']])) {
                return $mod_strings[$fieldDefs[$field]['vname']];
            }
            return $fieldDefs[$field]['vname'];
        }
        return $field;
    }
}
?>
