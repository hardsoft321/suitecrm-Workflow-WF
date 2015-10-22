<?php
require_once 'custom/include/Workflow/WFManager.php';
require_once 'custom/include/Workflow/utils.php';

/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author  Evgeny Pervushin <pea@lab321.ru>
 * @package Workflow-WF
 * @since version 0.7.21
 *
 * При переходе все связанные записи должны находиться в статусе,
 * который указан в параметре related_in_status в поле func_params.
 * Параметр related_relationship определяет связь с записями.
 * Может быть указан параметр where для выбора записей.
 * Может быть указан параметр message для сообщения об отсутствии записей.
 */
class AllRelatedInStatus extends BaseValidator
{
    public function validate($bean)
    {
        $errors = array();
        $relationship = null;
        if(empty($this->func_params['related_relationship'])) {
            $errors[] = "No related_relationship param";
        }
        else {
            $relationship = $this->func_params['related_relationship'];
        }
        if(empty($this->func_params['related_in_status'])) {
            $errors[] = "No related_in_status param";
        }
        if($relationship && !$bean->load_relationship($relationship)) {
            $errors[] = "Relationship not loaded";
        }
        if(!empty($errors)) {
            return $errors;
        }
        $count = 0;
        $relBeans = $bean->$relationship->getBeans(array(
            'where' => isset($this->func_params['where']) ? $this->func_params['where'] : '',
        ));
        foreach($relBeans as $relBean) {
            $count++;
            $statusField = WFManager::getBeanStatusField($relBean);
            if(!$statusField) {
                $errors[] = "Can't find status field for {$relBean->module_name} {$relBean->id}";
                continue;
            }
            if($relBean->$statusField != $this->func_params['related_in_status']) {
                $statusName = $relBean->db->getOne("SELECT name FROM wf_statuses
                    WHERE uniq_name = '{$this->func_params['related_in_status']}' AND wf_module = '{$relBean->module_name}' AND deleted = 0");
                $errors[] = wf_translate('ERR_RECORD_NOT_IN_STATUS', array(
                    '#NAME#' => $relBean->name,
                    '#STATUS#' => $statusName,
                ));
            }
        }
        if(!$count) {
            $errors[] = isset($this->func_params['message'])
                ? $this->func_params['message']
                : translate('ERR_RECORD_NOT_FOUND', 'WFWorkflows').' - '.translate($bean->field_defs[$relationship]['vname'], $bean->module_name);
        }
        return $errors;
    }
}
