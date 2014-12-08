<?php
class WFEventValidationException extends Exception {

    private $errors;

    public function __construct($errors) {
        global $app_strings;
        $this->errors = $errors;
        $message = $app_strings['LBL_CONFIRM_ERRORS_TITLE'].implode(', ', $errors);
        parent::__construct($message);
    }
    
    public function getErrors() {
        return $this->errors;
    }
}
