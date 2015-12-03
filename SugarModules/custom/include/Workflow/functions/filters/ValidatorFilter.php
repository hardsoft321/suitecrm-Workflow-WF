<?php
require_once 'custom/include/Workflow/functions/BaseValidator.php';

/**
 * @license http://hardsoft321.org/license/ GPLv3
 * @author  Evgeny Pervushin <pea@lab321.ru>
 * @package Workflow-WF
 * @since version 0.9.2
 *
 * Запускает указанный валидатор. Переход будет разрешен, если валидатор не вернет ошибок.
 * Функция валидатора ищется в следующей последовательности:
 *   - параметр validator_filter в func_params
 *   - поле "Функция валидации перехода" на данном переходе
 *   - если имя не найдено, переход запрещен
 */
class ValidatorFilter
{
    public function checkBean($bean)
    {
        if(!empty($this->func_params['validator_filter'])) {
            $validatorName = $this->func_params['validator_filter'];
        }
        else if(!empty($this->event_data['validate_function'])) {
            $validatorName = $this->event_data['validate_function'];
        }
        else {
            $GLOBALS['log']->error("ValidatorFilter: validator name not found");
            return false;
        }

        foreach(explode('^,^', trim($validatorName, '^')) as $functionName) {
            if(!file_exists('custom/include/Workflow/functions/validators/'.$functionName.'.php')) {
                $GLOBALS['log']->error("ValidatorFilter: validate function $functionName not found");
                return false;
            }
            require_once 'custom/include/Workflow/functions/validators/'.$functionName.'.php';
            $validator = new $functionName;
            //TODO: event_id, status1_data, status2_data
            $validator->func_params = $this->func_params;
            $errors = $validator->validate($bean);
            if(!empty($errors)) {
                return false;
            }
        }
        return true;
    }
}
