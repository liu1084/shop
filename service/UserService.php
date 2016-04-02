<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/11/29
 * Time: 23:44
 */

interface UserService {
    public function register($user);
    public function getUser($userId);
    public function login($user);                         //新用户注册后自动登陆
    public function logout();
    public function actionLogin($email, $password);       //老用户登录
}