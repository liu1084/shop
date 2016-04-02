<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/12/3
 * Time: 22:30
 */

class SessionException extends Exception {
    private $errorMessage;
    public function __construct($code, $message){
        $this->code = $code;
        $this->message = $message;
        $this->errorMessage = ['line' => $this->line, 'code' => $code, 'message' => $message];
        return $this->errorMessage;
    }
} 