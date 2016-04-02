<?php
include_once __DIR__ . '/../Controller.php';
include_once __DIR__ . '/../../model/User.php';
include_once __DIR__ . '/../../lib/DB.php';
include_once __DIR__ . '/../../service/impl/SessionServiceImpl.php';
include_once __DIR__ . '/../../service/impl/UserServiceImpl.php';
include_once __DIR__ . '/../../service/impl/UserCenterServiceImpl.php';
class UserCenterController extends Controller{

}
$userCenterController = new UserCenterController();
$userId = $userCenterController->checkIsLogin();
$userServiceImpl = new UserServiceImpl();
$user = $userServiceImpl->getUser($userId);
$email = $user->getEmail();
$nickname = $user->getNickname();


?>
<html>
<head><b>用户中心</b>
    <meta charset="UTF-8">
    <?php include_once __DIR__ . '/../../views/header.php';?>
</head>
<body>
    <div>欢迎：<?php echo $nickname ?></div>
    <div>用户邮箱：<?php echo $email ?></div>
    <div>
        <a href="EditPersonalInfoController.php">编辑个人资料</a>
    </div>
</body>
</html>
