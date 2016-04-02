<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/11/28
 * Time: 23:54
 */
include_once __DIR__ . '/../SessionService.php';
session_start();
class SessionServiceImpl implements SessionService {
    private $path;
    private $domain;
    private $expireTime;
    public function __construct(){
        $this->path = '/';
        $this->domain = 'localhost';
        $this->expireTime = 3600;
    }

    /**
     * @return mixed
     */
    public function getDomain() {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     */
    public function setDomain($domain) {
        $this->domain = $domain;
    }

    /**
     * @return mixed
     */
    public function getExpireTime() {
        return $this->expireTime;
    }

    /**
     * @param mixed $expireTime
     */
    public function setExpireTime($expireTime) {
        $this->expireTime = $expireTime;
    }

    /**
     * @return mixed
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path) {
        $this->path = $path;
    }

    //$session = ['key1' => $value1, 'key2' => $value2];
    public function setSession($session) {
        foreach ($session as $key => $value){
            $_SESSION [$key] = $value;
        }
        return true;
    }

    public function getSession($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }
    //$session = ['key1','key2'];
    public function removeSession($session) {
        foreach($session as $key){
            if (isset($_SESSION[$key])){
                unset($_SESSION[$key]);
            }
        }
        return true;
    }

    /**
     * $cookie = [
     *              ['name' => $sid, 'value' => $value, 'domain' => $domain, 'path' => $path, 'expired' => $expiredTime],
     *              ['name' => $sid2, 'value' => $value2, 'domain' => $domain2, 'path' => $path2, 'expired' => $expiredTime2]
     *          ]
     *
     */

    public function setCookie($cookies) {
        foreach($cookies as $cookie){
            $cookie['expired'] = isset($cookie['expired']) ? $cookie['expired'] : $this->expireTime;
            $cookie['path'] = isset($cookie['path']) ? $cookie['path'] : $this->path;
            $cookie['domain'] = isset($cookie['domain']) ? $cookie['domain'] : $this->domain;
            setcookie($cookie['name'], $cookie['value'], $cookie['expired'], $cookie['path'], $cookie['domain']);
        }
    }

    public function getCookie($key) {
        return $_COOKIE[$key];
    }

    public function removeCookie($key) {
        if (isset($_COOKIE[$key])){
            unset($_COOKIE[$key]);
        }
    }
}