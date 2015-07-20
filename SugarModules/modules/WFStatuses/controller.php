<?php
/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author Evgeny Pervushin <pea@lab321.ru>
 */
class WFStatusesController extends SugarController
{
    protected function post_save()
    {
        global $current_language;
        require_once 'custom/include/Workflow/WFManager.php';
        $errors = WFManager::checkWorkflows();
        if(!empty($errors)) {
            $workflows_mod_strings = return_module_language($current_language, 'WFWorkflows');
            SugarApplication::appendErrorMessage($workflows_mod_strings['MSG_CONFLICT_FOUND_AFTER_SAVE']);
        }
        parent::post_save();
    }
}
