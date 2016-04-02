<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/12/2
 * Time: 0:17
 */

class UserException extends Exception {
    private $errorMessage;

    /**
     * @return array
     */
    public function getErrorMessage() {
        return $this->errorMessage;
    }

    /**
     * @param array $errorMessage
     */
    public function setErrorMessage($errorMessage) {
        $this->errorMessage = $errorMessage;
    }

    public function __construct($code, $message){
        $this->code = $code;
        $this->message = $message;
        $this->errorMessage = ['line' => $this->line, 'code' => $code, 'message' => $message];
        return $this->errorMessage;
    }
} 