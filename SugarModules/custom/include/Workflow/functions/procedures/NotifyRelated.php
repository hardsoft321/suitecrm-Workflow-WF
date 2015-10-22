<?php
require_once 'custom/include/Workflow/utils.php';

/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author  Evgeny Pervushin <pea@lab321.ru>
 * @package Workflow-WF
 * @since version 0.7.21
 *
 * Рассылает уведомление связанным записям.
 * Параметры:
 * related_relationship - связь с записями
 * where - опционально, ограничение на выбор записей
 */
class NotifyRelated extends BaseProcedure
{
    public function doWork($bean)
    {
        $relationship = null;
        if(empty($this->func_params['related_relationship'])) {
            $GLOGALS['log']->fatal("NotifyRelated: No related_relationship param");
            return;
        }
        $relationship = $this->func_params['related_relationship'];
        if(!$bean->load_relationship($relationship)) {
            $GLOGALS['log']->fatal("NotifyRelated: Relationship $relationship not loaded");
            return;
        }
        $relBeans = $bean->$relationship->getBeans(array(
            'where' => isset($this->func_params['where']) ? $this->func_params['where'] : '',
        ));
        foreach($relBeans as $relBean) {
            wf_set_mod_strings($relBean->module_name);
            $notify_on_save = $relBean->assigned_user_id != $GLOBALS['current_user']->id && empty($GLOBALS['sugar_config']['exclude_notifications'][$relBean->module_dir]);
            if($notify_on_save) {
                if(!isset($relBean->workflowData)) {
                    $relBean->workflowData = array();
                }
                $relBean->workflowData['autosave'] = true;
                $relBean->save($notify_on_save);
            }
        }
    }
}
