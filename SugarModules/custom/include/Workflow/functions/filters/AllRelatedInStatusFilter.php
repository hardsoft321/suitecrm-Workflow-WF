<?php
require_once 'custom/include/Workflow/functions/BaseValidator.php';
require_once 'custom/include/Workflow/functions/validators/AllRelatedInStatus.php';

/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author  Evgeny Pervushin <pea@lab321.ru>
 * @package Workflow-WF
 * @since version 0.9.2
 *
 * См. валидатор AllRelatedInStatus.
 */
class AllRelatedInStatusFilter
{
    public function checkBean($bean)
    {
        $validator = new AllRelatedInStatus();
        $validator->func_params = $this->func_params;
        $errors = $validator->validate($bean);
        return empty($errors);
    }
}
