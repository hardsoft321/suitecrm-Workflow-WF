<?php
require_once 'custom/include/Workflow/WFManager.php';
require_once 'custom/include/Workflow/utils.php';

/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author  Evgeny Pervushin <pea@lab321.ru>
 * @package Workflow-WF
 * @since version 0.7.21
 *
 * При переходе записи связанные записи пытаются перейти в новый статус.
 * Запись может не перейти на новый статус из-за валидации.
 * Резолюция копируется в родительскую запись.
 *
 * Параметры:
 * related_relationship - связь с записями
 * where - опционально, ограничение на выбор записей
 * related_to_status - новый статус
 */
class RelatedToStatus extends BaseProcedure
{
    public function doWork($bean)
    {
        if(empty($this->func_params['related_to_status'])) {
            $GLOBALS['log']->fatal("RelatedToStatus: no related_to_status param");
            return;
        }
        foreach($this->getRelatedBeans($bean) as $relBean) {
            $this->changeStatus($relBean, $this->func_params['related_to_status'], $bean);
        }
    }

    protected function getRelatedBeans($bean)
    {
        $relationship = null;
        if(empty($this->func_params['related_relationship'])) {
            $GLOBALS['log']->fatal("RelatedToStatus: No related_relationship param");
            return array();
        }
        $relationship = $this->func_params['related_relationship'];
        if(!$bean->load_relationship($relationship)) {
            $GLOBALS['log']->fatal("RelatedToStatus: Relationship $relationship not loaded");
            return array();
        }
        return $bean->$relationship->getBeans(array(
            'where' => isset($this->func_params['where']) ? $this->func_params['where'] : '',
        ));
    }

    protected function changeStatus($relBean, $status, $mainBean)
    {
        $statusField = WFManager::getBeanStatusField($relBean);
        if(!$statusField) {
            $GLOBALS['log']->fatal("RelatedToStatus: can't find status field for {$relBean->parent_type} {$relBean->parent_id}");
            return;
        }

        WFManager::copyLastLog($mainBean, $relBean);

        if(!isset($relBean->workflowData)) {
            $relBean->workflowData = array();
        }
        $relBean->workflowData['autosave'] = true;
        $relBean->$statusField = $status;
        $assignedList = WFManager::getFrontAssignedUserList($relBean, $relBean->$statusField);
        if(!empty($assignedList)) {
            reset($assignedList);
            $relBean->assigned_user_id = key($assignedList);
        }
        wf_set_mod_strings($relBean->module_name);
        try {
            $relBean->save(true);
        }
        catch(WFEventValidationException $ex) {
            $GLOBALS['log']->info("RelatedToStatus: ".$ex->getMessage());
        }
    }
}
