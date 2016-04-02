<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/11/28
 * Time: 23:51
 */
interface SessionService  {
    public function setSession($session);
    public function getSession($key);
    public function removeSession($key);
    public function setCookie($cookie);
    public function getCookie($key);
    public function removeCookie($key);
} 