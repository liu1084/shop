<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/12/10
 * Time: 21:45
 */
include_once __DIR__ . '/../../service/impl/UserServiceImpl.php';
include_once __DIR__ . '/../Controller.php';

class LogoutController extends Controller{
    public function actionLogout(){
        $userServiceImpl = new UserServiceImpl();
        $userServiceImpl->logout();
    }
}
$logoutController = new LogoutController();
$logoutController->actionLogout();