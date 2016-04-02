<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/11/28
 * Time: 23:34
 */
include_once __DIR__ . '/../../service/impl/UserServiceImpl.php';
include_once __DIR__ . '/../../model/User.php';
include_once __DIR__ . '/../../exception/EmailInvalidException.php';
include_once __DIR__ . '/../../exception/PassworException.php';
include_once __DIR__ . '/../../exception/RegisterException.php';
include_once __DIR__ . '/../../exception/UserExistException.php';
include_once __DIR__ . '/../Controller.php';
include_once __DIR__ . '/../../service/impl/SessionServiceImpl.php';

class RegisterController extends Controller{

    public function actionRegister($user){
        $userService = new UserServiceImpl();
        return $userService->register($user);
    }

    public function actionLogin($user){
        $userServiceImpl = new UserServiceImpl();
        $userServiceImpl->login($user);
        return true;
    }
}

if (!empty($_POST['submit']) && isset($_POST['submit'])){
    try{
        //任何注册信息不能为空
        if(empty($_POST['email']) || empty($_POST['password']) || empty($_POST['nickname'])){
            throw new RegisterException(ERROR_REGISTER_INFO_IS_NOT_FULL_CODE,ERROR_REGISTER_INFO_IS_NOT_FULL_MESSAGE);
        }
        $register = new RegisterController();
        $email = $_POST['email'];

        $password = $_POST['password'];
        $rePassword = $_POST['re-password'];
        $nickname = $_POST['nickname'];

        //check password & re-password
        if ($password !== $rePassword){
            //请实现一个密码强度的检查
            //FIXME!
            throw new PassworException(ERROR_PASSWORD_NOT_MATCH_CODE, ERROR_PASSWORD_NOT_MATCH__MESSAGE);
        }

        //check email
        if (!preg_match('/^([0-9]|[a-zA-Z]|_)+\@([a-zA-Z]|_|[0-9])+\.([a-zA-Z]){2,3}(\.)*([a-zA-Z]){0,3}$/', $email)){
            throw new EmailInvalidException(ERROR_EMAIL_INVALID_CODE, ERROR_EMAIL_INVALID_MESSAGE);
        }
        //^[\u4E00-\u9FA5]+$ 汉字

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setNickname($nickname);

        //['affectedRows' => $affectedRows, 'user' => $user];
        $isRegister = $register->actionRegister($user);

        if ($isRegister['affectedRows'] !== 1){
            throw new RegisterException(ERROR_REGISTER_CODE, ERROR_REGISTER__MESSAGE);
        }

        $register->actionLogin($isRegister['user']);

        //FIXME~_~!
        //注册新用户成功以后，
        //0：为该用户自动登录
        //1：引导用户进入用户中心
        //用户中心包括：1：显示用户资料；2：修改密码



    }catch (PassworException $pe){
        echo $pe->getMessage();
    }catch (EmailInvalidException $ee){
        echo $ee->getMessage();
    }catch (RegisterException $re){
        echo $re->getMessage();
    }
}else{
?>
<html>
<head>
    <meta charset="UTF-8">
    <link href="/shop/css/bootstrap.min.css" rel="stylesheet" />
    <script type="text/javascript" src="/shop/js/libs/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="/shop/js/libs/bootstrap.min.js"></script>
    <script src="../../js/register.js"></script>
</head>
<p><b>Register a new user</b></p>
<form action="" method="POST">
    <div>
        Email:
        <span><input type="text" value="" name="email" id="email"/></span>
        <span class="has-error" style="display: none;color: red;"></span>
    </div>
    <div>
        Password:
        <span><input type="password" name="password" id="password" /></span>
        <span class="strength"  style="display: none;color: red;"></span>
        <span class="has-error" style="display: none;color: red;">密码不能为空</span>
    </div>
    <div>
        Re-password:
        <span><input type="password" value="" name="re-password" id="re-password"/></span>
        <span class="has-error" style="display: none;color: red;"></span>
    </div>
    <div>
        Nickname:
        <span><input type="text" value="" name="nickname" id="nickname"/></span>
        <span class="has-error" style="display: none;color: red;">昵称不能为空</span>
    </div>
    <div>
        <span><input type="submit" value="submit" name="submit" /></span>
    </div>
</form>
</html>

<?php
}
?>
