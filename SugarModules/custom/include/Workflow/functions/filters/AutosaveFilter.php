<?php
/**
 * Переход доступен только при автоматическом сохранении.
 * Пользователю будет недоступен этот переход.
 * Чтобы программно разрешить переход, нужно в бине установить свойство
 * workflowData['autosave'] в true.
 */
class AutosaveFilter
{
    public function checkBean($bean)
    {
        return isset($bean->workflowData['autosave']) && $bean->workflowData['autosave'];
    }
}
