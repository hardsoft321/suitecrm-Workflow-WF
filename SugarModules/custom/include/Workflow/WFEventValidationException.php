<?php
class WFEventValidationException extends Exception {

    private $errors;

    public function __construct($errors) {
        $this->errors = $errors;
        $message = "Для перехода в указанный статус необходимо исправить следующие ошибки: ".implode(', ', $errors);
        parent::__construct($message);
    }
    
    public function getErrors() {
        return $this->errors;
    }
}
