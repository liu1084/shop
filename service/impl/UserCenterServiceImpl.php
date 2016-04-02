<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/12/3
 * Time: 18:16
 */
include_once __DIR__ . '/../Service.php';
include_once __DIR__ . '/../UserCenterService.php';
include_once __DIR__ . '/SessionServiceImpl.php';
include_once __DIR__ . '/../../exception/PassworException.php';

class UserCenterServiceImpl extends Service implements UserCenterService {
    private $sessionServiceImpl = null;
    private $userId = '';
    private $isLogin = false;

    public function __construct() {
        parent::__construct();
        $this->sessionServiceImpl = new SessionServiceImpl();
        $this->userId = $this->sessionServiceImpl->getSession('id');
        $this->isLogin = $this->sessionServiceImpl->getSession('isLogin');
    }

    public function changePersonalInfo($newNickname) {
        $where = 'id="' . $this->userId . '"';
        $records = ['nickname' => $newNickname];
        $isPersonalInfoChanged = $this->getDb()->update('user', $records, $where);
        return $isPersonalInfoChanged;
    }

    public function changePassword($newPassword) {
        $where = 'id="' . $this->userId . '"';
        $records = ['password' => $newPassword];
        $isPasswordChanged = $this->getDb()->update('user', $records, $where);
        return $isPasswordChanged;
    }

    public function isLogin() {
        return $this->isLogin;
    }
}
