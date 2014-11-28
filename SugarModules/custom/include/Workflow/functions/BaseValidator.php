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

    /**
     * Проверяет бин.
     * Возвращает массив ошибок.
     */
    public abstract function validate($bean);

    public function getName($bean) {
        return get_class($this);
    }
}
