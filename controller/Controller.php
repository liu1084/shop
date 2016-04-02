<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/11/26
 * Time: 20:08
 */
include_once __DIR__ . '/../service/impl/SessionServiceImpl.php';
include_once __DIR__ . '/../config/errors-config.php';
include_once __DIR__ . '/../exception/UserNotLoginException.php';
class Controller {
    private $action;
    private $baseUrl;

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return '/shop';
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $_POST['action'];
    }

    public function isAjaxRequest(){
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) ===  'xmlhttprequest'){
            return true;
        }
        return false;
    }

    public function checkIsLogin(){

        $sessionServiceImpl = new SessionServiceImpl();
        $isLogin = $sessionServiceImpl->getSession('isLogin');
        $userId = $sessionServiceImpl->getSession('id');

        if($isLogin !== true){
            if($this->isAjaxRequest() === true){
                try{
                    throw new UserNotLoginException(ERROR_USER_NOT_LOGIN_CODE, ERROR_USER_NOT_LOGIN_MESSAGE);
                }catch (UserNotLoginException $ue){
                    echo json_encode(['code' => $ue->getCode(), 'message' => $ue->getMessage()]);
                    exit;
                }
            }
            $redirectUrl = $_SERVER['PHP_SELF'];
            header('Location: /shop/controller/user/LoginController.php?redirectUrl=' . $redirectUrl);
        }
        return $userId;
    }

} 