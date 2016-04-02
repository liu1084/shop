<?php

include_once __DIR__ . '/../Controller.php';
include_once __DIR__ . '/../../service/impl/MyCartServiceImpl.php';
include_once __DIR__ . '/../../model/CartGoods.php';

class MyCartController extends Controller{
    private $myCartServiceImpl;

    public function __construct(){
        $this->myCartServiceImpl = new MyCartServiceImpl();
    }
    public function actionShow(){
       return $this->myCartServiceImpl->showMyCart();
    }
    public function actionUpdateGoodsNumber(){
        $goodsNumber = $_POST['number'];
        $goodsId = $_POST['goodsId'];
        $this->myCartServiceImpl->updateGoodsNumber($goodsId,$goodsNumber);
    }
    public function actionTotalChecked(){
        $checkedGoods = $_POST['checkedGoods'];
        return $this->myCartServiceImpl->totalChecked($checkedGoods);
    }

}

$myCartController = new MyCartController();
$myCartController->checkIsLogin();
$allMyCartGoods = $myCartController->actionShow();

$action = $myCartController->getAction();
switch ($action) {
    case 'updateNumber':
        if(!empty($_POST['number']) && !empty($_POST['goodsId'])){
            $myCartController->actionUpdateGoodsNumber();
        }
        break;
    case 'totalPrice':
        if(!empty($_POST['checkedGoods'])){
            $totalChecked = $myCartController->actionTotalChecked();
            echo json_encode(['j' => $totalChecked]);
            exit;
        }
        break;
}

?>


<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>我的购物车</title>
    <?php include_once __DIR__ . '/../../views/header.php';?>
    <link href="../../css/cart.css" rel="stylesheet" type="text/css" />
    <link href="../../css/goods.css" rel="stylesheet" type="text/css" />
    <link href="../../css/myCart.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../../js/myCart.js"></script>

</head>
<body>
<div id="my_cart_goods">
    <span><input type="checkbox" class="checkAll" checked/>全选</span>
    <ul>
        <?php
            foreach($allMyCartGoods as $myCartGoods){              //$allMyCartGoods为当前用户购物车中所有商品，是CartGoods对象类型的数组
        ?>
                <li class="my-goods" goods-id="<?php echo $myCartGoods->getId(); ?>">
                    <span><input type="checkbox" name="cart_goods[]" value="" class="goods-checked" checked/></span>
                    <span><img class="img-middle" src="<?php echo $myCartGoods->getSrc(); ?>" alt="" /></span>
                    <span class="common-bordr name"><?php echo $myCartGoods->getName(); ?></span>
                    <span class="common-bordr price"><?php echo $myCartGoods->getPrice(); ?></span>
                    <span class="common-bordr plus">+</span>
                    <span>
                        <input type="text" value="<?php echo $myCartGoods->getNumberInCart(); ?>"  class="common-bordr number" />
                    </span>
                    <span class="common-bordr minus">-</span>
                    <span class="delete-goods" style="color: red;background: #e38d13;">X</span>
                </li>
        <?php
            }
        ?>
    </ul>
</div>
<div class="amount">
    <span>总计：</span>
    <span class="total"></span>
    <span class="go-to-pay">去结算></span>

</div>
<?php include_once __DIR__ . '/../../views/footer.php' ?>
</body>
</html>
