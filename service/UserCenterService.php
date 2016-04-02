<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/12/3
 * Time: 18:22
 */
interface UserCenterService{
    public function changePassword($newPassword);
    public function changePersonalInfo($newNickname);
    public function isLogin();
}