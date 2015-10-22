<?php
/**
 * Базовый класс с функцией, проверяющий готовность записи к переходу.
 * Функция вызывается, когда переход уже выбран.
 * Например, проверяется заполненность полей в записи.
 */
abstract class BaseValidator {
    public $event_id;
    public $status1_data;
    public $status2_data;
    public $func_params;

    /**
     * Проверяет бин.
     * Возвращает массив ошибок.
     */
    public abstract function validate($bean);

    public function getName() {
        require_once 'custom/include/Workflow/utils.php';
        return wf_translate(get_class($this));
    }
}
