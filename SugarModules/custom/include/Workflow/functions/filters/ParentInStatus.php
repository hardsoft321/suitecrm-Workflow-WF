<?php
require_once 'custom/include/Workflow/WFManager.php';

/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author  Evgeny Pervushin <pea@lab321.ru>
 * @package Workflow-WF
 * @since version 0.7.21
 *
 * При переходе родительская запись должна находиться
 * в статусе, который указан в параметре parent_in_status в поле func_params.
 * Родительская запись ищется по полям parent_type и parent_id.
 */
class ParentInStatus
{
    public function checkBean($bean)
    {
        if(empty($this->func_params['parent_in_status'])) {
            $GLOBALS['log']->error("ParentInStatus: no parent_in_status param");
            return false;
        }
        $parentBean = BeanFactory::getBean($bean->parent_type, $bean->parent_id);
        if(!$parentBean) {
            return false;
        }
        $statusField = WFManager::getBeanStatusField($parentBean);
        if(!$statusField) {
            $GLOBALS['log']->error("ParentInStatus: can't find status field for {$bean->parent_type} {$bean->parent_id}");
            return false;
        }
        if(is_array($this->func_params['parent_in_status'])) {
            return in_array($parentBean->$statusField, $this->func_params['parent_in_status']);
        }
        return $parentBean->$statusField == $this->func_params['parent_in_status'];
    }
}
