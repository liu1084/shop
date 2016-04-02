<?php

/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/11/28
 * Time: 23:48
 */
class Sessions {
    private $userId;
    private $isLogin;
    private $redirectUrl;

    /**
     * @return mixed
     */
    public function getIsLogin() {
        return $this->isLogin;
    }

    /**
     * @param mixed $isLogin
     */
    public function setIsLogin($isLogin) {
        $this->isLogin = $isLogin;
    }

    /**
     * @return mixed
     */
    public function getRedirectUrl() {
        return $this->redirectUrl;
    }

    /**
     * @param mixed $redirectUrl
     */
    public function setRedirectUrl($redirectUrl) {
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * @return mixed
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId) {
        $this->userId = $userId;
    }


} 