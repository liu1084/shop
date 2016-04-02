<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/12/1
 * Time: 22:23
 */
include_once __DIR__ . '/UserException.php';
class PassworException extends UserException {
    public function __construct($code, $message){
        return parent::__construct($code, $message);
    }
}