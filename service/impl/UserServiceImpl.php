<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/11/29
 * Time: 23:52
 */

include_once __DIR__ . '/../../lib/DB.php';
include_once __DIR__ . '/../Service.php';
include_once __DIR__ . '/../UserService.php';
include_once __DIR__ . '/../../config/errors-config.php';
include_once __DIR__ . '/../../model/User.php';
include_once __DIR__ . '/SessionServiceImpl.php';

include_once __DIR__ . '/../../exception/UserException.php';
include_once __DIR__ . '/../../exception/UserExistException.php';
include_once __DIR__ . '/../../exception/SessionException.php';

class UserServiceImpl extends Service implements UserService {
    private $isLogin = false;
    private $userId = '';
    private $sessionServiceImpl;
    public function __construct(){
        parent::__construct();
        $this->sessionServiceImpl = new SessionServiceImpl();
        $this->isLogin = $this->sessionServiceImpl->getSession('isLogin');
        $this->userId = $this->sessionServiceImpl->getSession('id');
    }
    public function register($user) {
        try {
            //检查是否已经登录
            if ($this->isLogin === true) {
                $this->logout();
            }
            //检查此email是否已经存在
            $wheres = 'email = "' . $user->getEmail() . '"';
            $count = $this->getDb()->count('user', $wheres);
            $row = mysql_fetch_object($count);
            if ($row->count > 0) {
                throw new UserExistException(ERROR_USER_EXIST_CODE, ERROR_USER_EXIST__MESSAGE);
            }
            $registerInfo = ['email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'nickname' => $user->getNickname()];
            $this->getDb()->insert('user', $registerInfo);
            $user->setId($this->getDb()->getInsertId());
            $affectedRows = $this->getDb()->getAffectedRows();
            return ['affectedRows' => $affectedRows, 'user' => $user];
        } catch (UserExistException $userExist) {
            echo '<pre>';
            print_r($userExist->getMessage());
        }
    }

    public function login($user) {                              //注册后自动登陆的login
        $userId = $user->getId();
        $session = ['id' => $userId];
        $sessionServiceImpl = new SessionServiceImpl();
        if ($sessionServiceImpl->setSession($session) !== true) {
            throw new SessionException(ERROR_SESSION_CAN_NOT_SET_CODE, ERROR_SESSION_CAN_NOT_SET_MESSAGE);
        }
        $this->isLogin = true;
        $session = ['isLogin' => true];
        $sessionServiceImpl->setSession($session);
        $this->userId = $userId;
        echo '<script>location.href="/shop/controller/user/UserCenterController.php";</script>';
        //$path = 'Location: http://localhost/shop//';
        //header($path);
    }

    public function logout() {
        if ($this->isLogin === true) {
            $this->sessionServiceImpl->removeSession(['id','isLogin','lang']);
        }
        header('Location: /shop/controller/user/LoginController.php');
    }

    public function getUser($userId) {
        $wheres = ' `id` = "' . $userId . '" ';
        $query = $this->getDb()->read('user', $wheres, [], true);
        $row = mysql_fetch_object($query);
        $user = new User();
        $user->setId($row->id);
        $user->setPassword($row->password);
        $user->setNickname($row->nickname);
        $user->setEmail($row->email);
        return $user;
    }

    public function actionLogin($email, $password) {                  //老用户登录
        try {
            $where = ' email = "' . $email . '"';
            $query = $this->getDb()->read('user', $where, [], true);
            $row = mysql_fetch_object($query);
            //判断email这个用户是否存在
            if(empty($row) || !isset($row)){
                    throw new UserException(ERROR_USERNAME_OR_PASSWORD_IS_WRONG_CODE, ERROR_USERNAME_OR_PASSWORD_IS_WRONG_MESSAGE);
            }else{
                //判断用户的密码是否跟数据库中的密码一致
                if ($row->password !== $password) {
                    throw new UserException(ERROR_USERNAME_OR_PASSWORD_IS_WRONG_CODE, ERROR_USERNAME_OR_PASSWORD_IS_WRONG_MESSAGE);
                }
            }
            $id = $row->id;
            $where = ' `user_id` =  "' . $id . '" ';
            $query = $this->getDb()->read('user_info', $where, ['language']);
            $row = mysql_fetch_object($query);
            $lang = $row->language;
            $sessionServiceImpl = new SessionServiceImpl();
            $sessionServiceImpl->setSession(['id' => $id, 'isLogin' => true, 'lang' => $lang]);
            return $id;
        } catch (UserException $ue) {
            echo '<div class = "warning">' . $ue->getMessage() . '</div>';
        }
    }
}