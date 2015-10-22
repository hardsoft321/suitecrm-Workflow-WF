<?php
require_once 'custom/include/Workflow/functions/procedures/RelatedToStatus.php';

/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author  Evgeny Pervushin <pea@lab321.ru>
 * @package Workflow-WF
 * @since version 0.7.21
 *
 * При переходе записи родительская запись пытается перейти в новый статус.
 * Запись может не перейти на новый статус из-за валидации.
 * Родительская запись ищется по полям parent_type и parent_id.
 * Резолюция копируется в родительскую запись.
 *
 * Параметры:
 * parent_to_status - новый статус
 */
class ParentToStatus extends RelatedToStatus
{
    public function doWork($bean)
    {
        if(empty($this->func_params['parent_to_status'])) {
            $GLOBALS['log']->fatal("ParentToStatus: no parent_to_status param");
            return;
        }
        $this->func_params['related_to_status'] = $this->func_params['parent_to_status'];
        parent::doWork($bean);
    }

    protected function getRelatedBeans($bean)
    {
        $parent = BeanFactory::getBean($bean->parent_type, $bean->parent_id);
        if(!$parent) {
            $GLOBALS['log']->error("ParentToStatus: Parent {$bean->parent_type} {$bean->parent_id} for {$bean->module_name} {$bean->id} not found");
            return array();
        }
        return array($parent);
    }
}
