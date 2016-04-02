<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/12/9
 * Time: 22:26
 */
include_once __DIR__ . '/../service/impl/SessionServiceImpl.php';
include_once __DIR__ . '/../service/impl/UserServiceImpl.php';
include_once __DIR__ . '/../config/LANGUAGE.php';
$sessionServiceImpl = new SessionServiceImpl();
$id = $sessionServiceImpl->getSession('id');
$isLogin = $sessionServiceImpl->getSession('isLogin');
$userServiceImpl = new UserServiceImpl();
$user = $userServiceImpl->getUser($id);
$nickname = $user->getNickname();
$lang = $sessionServiceImpl->getSession('lang');
if(empty($lang) || !isset($lang)){
    $lang = 'cn';
}
$strings = include_once __DIR__ . '/../i18n/' . $lang . '/lang.php';
?>
<link href="/shop/css/bootstrap.min.css" rel="stylesheet" />
<script type="text/javascript" src="/shop/js/libs/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/shop/js/libs/bootstrap.min.js"></script>
<div><a href="/shop/controller/cart/CartController.php"><?php echo $strings['menu_index']; ?></a></div>
<div><a href="/shop/controller/user/UserCenterController.php"><?php echo $strings['menu_user_center']; ?></a></div>
<?php
if($isLogin === true){
    echo '欢迎<b>' . $nickname . '</b><br/>';
    echo '<a href="/shop/controller/user/LogoutController.php">' . $strings['menu_logout'] . '</a>';
}else{
    echo '<a href="/shop/controller/user/LoginController.php">' . $strings['menu_login'] . '</a>';

}
?>
