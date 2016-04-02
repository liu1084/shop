<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/12/2
 * Time: 0:14
 */
include_once __DIR__ . '/UserException.php';
class RegisterException extends UserException {
    public function __construct($code, $message){
        return parent::__construct($code, $message);
    }
} 