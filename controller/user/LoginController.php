<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/11/28
 * Time: 23:10
 */
include_once __DIR__ . '/../Controller.php';
include_once __DIR__ . '/../../service/impl/UserServiceImpl.php';
include_once __DIR__ . '/../../service/impl/SessionServiceImpl.php';
include_once __DIR__ . '/../../config/errors-config.php';
include_once __DIR__ . '/../../exception/EmailInvalidException.php';
include_once __DIR__ . '/../../exception/PassworException.php';

class LoginController extends Controller {
    private $redirectUrl;
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

    public function actionLogin($email, $password){
        /**
         * 登录
         * 1:有两种方式实现，或者配合使用
         * 2：第一种：session方式
         *    第二种：cookie方式
         *    第三种：session + cookie
         *
         */
        $userServiceImpl = new UserServiceImpl();
        $userId = $userServiceImpl->actionLogin($email, $password);

        //跳转到用户最初想要去的地方（要去的这个地方是需要登录才可以的）
        if (isset($userId) && !empty($userId)){
            header('Location: ' . $this->getRedirectUrl());
        }
    }

    public function actionLogout(){
        $userServiceImpl = new UserServiceImpl();
        $userId = $userServiceImpl->logout();
    }
}

if(!empty($_POST['submit'])){
    try{
        $email = $_POST['email'];
        $password = $_POST['password'];

        //客官，您要去哪里？
        $redirectUrl = '../cart/CartController.php';
        if (!empty($_GET['redirectUrl']) && isset($_GET['redirectUrl'])){
            $redirectUrl = $_GET['redirectUrl'];
        }

        if (empty($email) || !preg_match('/^([0-9]|[a-zA-Z]|_)+\@([a-zA-Z]|_|[0-9])+\.([a-zA-Z]){2,3}(\.)*([a-zA-Z]){0,3}$/', $email)){
            throw new EmailInvalidException(ERROR_EMAIL_INVALID_CODE, ERROR_EMAIL_INVALID_MESSAGE);
        }

        if (empty($password)){
            //请实现一个密码强度的检查
            throw new PassworException(ERROR_PASSWORD_IS_WRONG_CODE, ERROR_PASSWORD_IS_WRONG_MESSAGE);
        }
        //检查提交数据后，做逻辑处理
        $loginController = new LoginController();
        $loginController->setRedirectUrl($redirectUrl);
        $loginController->actionLogin($email, $password);
    }catch (EmailInvalidException $ee){
        echo '<div class="warning">'. $ee->getMessage() . '</div>';
    }catch (PassworException $pe){
        echo '<div class="warning">'. $pe->getMessage() . '</div>';
    }
    /**
     * 1：用户提交的数据， 在没有验证之前，不要做逻辑处理
     * 2：敏感信息尽量不要存放在cookie和session中
     * 3：用户在登录之前，需要知道用户到底要去哪里？
     */
}

?>

<?php
    $sessionServiceImpl = new SessionServiceImpl();
    $lang = $sessionServiceImpl->getSession('lang');
    if(empty($lang) && !isset($lang)){
        $lang = 'cn';
    }
    $strings = include_once __DIR__ . '/../../i18n/' . $lang . '/lang.php';
?>

<html>
<head lang="en">
    <meta charset="UTF-8">
    <title><?php echo  $strings['title_sign_in'];?></title>
    <link href="/shop/css/bootstrap.min.css" rel="stylesheet" />
    <script type="text/javascript" src="/shop/js/libs/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="/shop/js/libs/bootstrap.min.js"></script>

</head>
<body>

<div class="container-fluid" id="container">
    <h1 class="">Sign in</h1>
    <p class="">
    <form class="form-horizontal col-sm-8" role="form" action="" method="post">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-3">
                <input type="email" class="form-control" id="inputEmail3" placeholder="Email" name="email">
            </div>
        </div>
        <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
            <div class="col-sm-3">
                <input type="password" class="form-control" id="inputPassword3" placeholder="Password" name="password">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-3">
                <div class="checkbox">
                    <label>
                        <input type="checkbox"> Remember me
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-3">
                <div class="">
                    <label><a href="RegisterController.php">Register a new user</a></label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-3">
                <div class="">
                    <label><a href="ForgotPasswordController.php" target="_blank">Forgot Password?</a></label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-3">
                <button type="submit" class="btn btn-default" name="submit" value="登录">Sign in</button>
            </div>
        </div>
    </form>
    </p>
</div>
<?php include_once __DIR__ . '/../../views/footer.php'?>
</body>
</html>