<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/12/1
 * Time: 21:17
 */
include_once __DIR__ . '/UserException.php';
class UserExistException extends UserException {
    public function __construct($code, $message){
        return parent::__construct($code, $message);
    }
}