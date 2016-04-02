<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/12/10
 * Time: 18:37
 */
include_once __DIR__ . '/../../model/User.php';
include_once __DIR__ . '/../../service/impl/UserServiceImpl.php';
include_once __DIR__ . '/../../service/impl/UserCenterServiceImpl.php';
include_once __DIR__ . '/../../exception/UserException.php';
include_once __DIR__ . '/../../service/impl/SessionServiceImpl.php';
include_once __DIR__ . '/../Controller.php';
class EditPersonalInfoController extends Controller{
    private $userCenterServiceImpl;
    public function __construct(){
        //parent::checkIsLogin();
        $this->userCenterServiceImpl = new UserCenterServiceImpl();
    }
    public function changePersonalInfo($newNickname){
        return $this->userCenterServiceImpl->changePersonalInfo($newNickname);
    }
    public function changePassword($newPassword){
        return $this->userCenterServiceImpl->changePassword($newPassword);
    }
}
/**
 * 1: 判断用户是否已经登录：如果没有登录，跳转到登录页面，登录成功后再跳转回来。
 *
 */
$editPersonalInfoController = new EditPersonalInfoController();
$userId = $editPersonalInfoController->checkIsLogin();
$userServiceImpl = new UserServiceImpl();
$user = $userServiceImpl->getUser($userId);
$email = $user->getEmail();
$password = $user->getPassword();
$nickname = $user->getNickname();
if(!empty($_POST['submit'])){
    $editPersonalInfoController->checkIsLogin();
    try{
        if(empty($_POST['nickname'])){
            throw new UserException(ERROR_INFO_IS_NOT_FULL_CODE,ERROR_INFO_IS_NOT_FULL_MESSAGE);
        }
        $newNickname = $_POST['nickname'];
        $isPersonalInfoChanged = $editPersonalInfoController->changePersonalInfo($newNickname);
        if($isPersonalInfoChanged !== true){
            throw new PassworException(ERROR_CHANGE_INFO_FAILED_CODE, ERROR_CHANGE_INFO_FAILED_MESSAGE);
        }
        echo '<div class="success">' . '资料修改成功！' . '</div>';
    }catch (UserException $ue){
        echo $ue->getMessage();
    }
}
if(!empty($_POST['change-password'])){
    try{
        $newPassword = $_POST['new-password'];
        $reNewPassword = $_POST['re-new-password'];
        if($newPassword !== $reNewPassword){
            throw new PassworException(ERROR_PASSWORD_NOT_MATCH_CODE, ERROR_PASSWORD_NOT_MATCH_MESSAGE);
        }
        if($_POST['old-password'] !== $password){
            throw new PassworException(ERROR_OLD_PASSWORD_NOT_MATCH_CODE, ERROR_OLD_PASSWORD_NOT_MATCH_MESSAGE);
        }
        $isPasswordChanged = $editPersonalInfoController->changePassword($newPassword);
        if($isPasswordChanged !== true){
            throw new PassworException(ERROR_CHANGE_PASSWORD_FAILED_CODE, ERROR_CHANGE_PASSWORD_FAILED_MESSAGE);
        }
        echo '<div class="success">' . '密码修改成功！' . '</div>';
    }catch (PassworException $pe){
        $errorMessage = $pe->getErrorMessage();
        echo '<div class="warning">' . 'ErrorCode:' . $errorMessage['code'] . ", ErrorMessage:" . $errorMessage['message'] . '</div>';
    }
}
?>

<html>
<head><b>个人信息</b>
    <meta charset="UTF-8">
    <?php include_once __DIR__ . '/../../views/header.php';?>
    <script src="../../js/edit_personal_info.js"></script>
</head>
<body>
<form action="" method="post" name="personal-info">
    <div>
        <div>
            <label>用户邮箱：</label><?php echo $email; ?>
        </div>
        <div>
            <label>用户昵称：</label>
            <span><input type="text" name="nickname" value="<?php echo $nickname; ?>" id="nickname"></span>
            <span class="has-error" style="display: none;">用户昵称不能为空</span>
        </div>
        <div>
            <input type="submit" value="提交" name="submit"/>
        </div>
    </div>
</form>

<form action="" method="post" name="change-password">
    <div>
        <b>修改密码</b>
        <div>
            <label>原密码：</label>
            <span><input type="password" name="old-password" id="old-password"/></span>
            <span class="has-error" style="display: none;">原密码不能为空</span>
        </div>
        <div>
            <label>新密码：</label>
            <span><input type="password" name="new-password" id="new-password"/></span>
            <span class="has-error" style="display: none;">新密码不能为空</span>
        </div>
        <div>
            <label>确认密码：</label>
            <span><input type="password" name="re-new-password" id="re-new-password"/></span>
            <span class="has-error" style="display: none;">确认密码不能为空</span>
        </div>
        <div>
            <input type="submit" value="确认修改" name="change-password" id="change-password"/>
        </div>
    </div>
</form>
</body>


</html>
