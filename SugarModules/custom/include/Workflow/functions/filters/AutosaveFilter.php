<?php
/**
 * Переход возможен при автоматическом переходе, т.е. когда в бине установлено
 * свойство workflowData['autosave'] в true
 */
class AutosaveFilter
{
    public function checkBean($bean)
    {
        return isset($bean->workflowData['autosave']) && $bean->workflowData['autosave'];
    }
}
