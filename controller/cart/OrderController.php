<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/12/23
 * Time: 14:23
 */
include_once __DIR__ . '/../Controller.php';
include_once __DIR__ . '/../../service/impl/OrderServiceImpl.php';
include_once __DIR__ . '/../../service/impl/SessionServiceImpl.php';


class OrderController extends Controller{
    private $orderServiceImpl;
    private $sessionServiceImpl;
    public function __construct(){
        $this->orderServiceImpl = new OrderServiceImpl();
        $this->sessionServiceImpl = new SessionServiceImpl();
    }
    public function actionGetCustomerInfo(){
        $customerInfo = $this->orderServiceImpl->getCustomerInfo();
        return $customerInfo;
    }
    public function actionDeleteCustomerInfo(){
        $id = $_POST['id'];
        $this->orderServiceImpl->deleteCustomerInfo($id);
        echo json_encode('');
        exit;
    }
    public function actionEditCustomerInfo(){
        if(!empty($_POST['changedName']) && !empty($_POST['changedPhone']) && !empty($_POST['changedAddr'])){
            if(preg_match('/^([1]){1}([0-9]){10}$/', $_POST['changedPhone'])){
                $id = $_POST['id'];
                $name = $_POST['changedName'];
                $phone = $_POST['changedPhone'];
                $address = $_POST['changedAddr'];
                $this->orderServiceImpl->editCustomerInfo($id,$name,$phone,$address);
                echo json_encode(['successful'=>true]);
                exit;
            }
        }else{
            try{
                throw new Exception(ERROR_INFO_IS_NOT_FULL_CODE, ERROR_INFO_IS_NOT_FULL_MESSAGE);
            }catch (Exception $e){
                echo json_encode(['code' => $e->getCode(), 'message' => $e->getMessage()]);
                exit;
            }
        }

    }
    public function actionAddCustomerInfo(){
        if(!empty($_POST['name']) && !empty($_POST['phone']) && !empty($_POST['address'])){
            if(preg_match('/^([1]){1}([0-9]){10}$/', $_POST['phone'])){
                $name = $_POST['name'];
                $phone = $_POST['phone'];
                $address = $_POST['address'];
                $id = $this->orderServiceImpl->addCustomerInfo($name,$phone,$address);
                echo json_encode(['id'=>$id]);
                exit;
            }
        }else{
            try{
                throw new Exception(ERROR_INFO_IS_NOT_FULL_CODE, ERROR_INFO_IS_NOT_FULL_MESSAGE);
            }catch (Exception $e){
                echo json_encode(['code' => $e->getCode(), 'message' => $e->getMessage()]);
                exit;
            }
        }

    }
    public function setCheckedGoods(){
        if(!empty($_POST['checkedGoods'])){
            $checkedGoods = $_POST['checkedGoods'];
            $this->sessionServiceImpl->setSession(['checkedGoods' => $checkedGoods]);
            echo json_encode(['checkedNothing' => false]);
            exit;
        }else{
            echo json_encode(['checkedNothing' => true]);
            exit;
        }

    }
    public function actionShowGoodsBills(){
        return $this->orderServiceImpl->showGoodsBills();
    }
    public function totalPrice(){
        $goodsList = $this->actionShowGoodsBills();
        $total = 0;
        foreach($goodsList as $buyGoods){
            $total += $buyGoods->getPrice() * $buyGoods->getNumberInCart();
        }
        return $total;
    }
    public function actionSubmitOrder(){
        if(!empty($_POST['customerInfoId']) && !empty($_POST['bills'])){
            $customerInfoId = $_POST['customerInfoId'];
            $bills = $_POST['bills'];
            $totalPrice = $this->orderServiceImpl->submitOrder($customerInfoId,$bills);
            echo json_encode(['totalPrice'=>$totalPrice]);
            exit;
        }
    }

}
$orderController = new OrderController();
$orderController->checkIsLogin();
$customerInfo = $orderController->actionGetCustomerInfo();
$action = $orderController->getAction();
switch($action){
    case 'delete' :
        $orderController->actionDeleteCustomerInfo();
        break;
    case 'edit' :
        $orderController->actionEditCustomerInfo();
        break;
    case 'add' :
        $orderController->actionAddCustomerInfo();
        break;
    case 'submitOrder' :
        $orderController->actionSubmitOrder();
        break;
    case 'goToPay' :
        $orderController->setCheckedGoods();
        break;

}
$goodsBills = $orderController->actionShowGoodsBills();
?>

<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>订单结算</title>
    <?php include_once __DIR__ . '/../../views/header.php';?>
    <link href="../../css/order.css" rel="stylesheet" type="text/css" />
    <link href="../../css/goods.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../../js/order.js"></script>
</head>
<body>
<div style="font-size: 40px;">填写并核对订单信息</div>
<div class="customer-info" id="">
    <span style="font-size: 30px;">收货人信息</span>
    <span class="change">【修改】</span><br/>
    <span class="customerName"></span>&nbsp;&nbsp;&nbsp;
    <span class="customerPhone"></span>&nbsp;&nbsp;&nbsp;
    <span class="customerAddr"></span>
</div>
<div class="modify-customer-info" style="display: none;">
    <span style="font-size: 30px;">修改收货人信息</span><span class="confirm-change">【确认修改】</span><br/>
    <ul>
    <?php
    foreach($customerInfo as $eachCustomer){
    ?>
        <li id="<?php echo $eachCustomer->id; ?>" class="customer-list">
            <span>
                <input type="radio" name="customer_info_radio" class="customer-info-radio"
                    <?php if($eachCustomer->id == $customerInfo[0]->id){echo 'checked';} ?>
                />
            </span>
            <span class="customerName"><?php echo $eachCustomer->customerName; ?></span>&nbsp;&nbsp;&nbsp;
            <span class="customerPhone"><?php echo $eachCustomer->customerPhone; ?></span>&nbsp;&nbsp;&nbsp;
            <span class="customerAddr"><?php echo $eachCustomer->customerAddr; ?></span>
            <span class="edit">编辑</span>
            <span class="delete">删除</span>
        </li>
    <?php
    }
    ?>
    </ul>
    <span class="add-customer-info">添加收货人信息</span><br/>
    <div customer-id="" style="display: none;" class="add-or-edit">
        <span>收货人姓名：<input type="text" value="" class="customerName"></span>
        <span class="nameError">请填写收货人姓名</span><br/>
        <span>联系电话：<input type="text" value="" class="customerPhone"></span>
        <span class="phoneError">请填写收货人电话</span><br/>
        <span>收货地址：<input type="text" value="" class="customerAddr"></span>
        <span class="addressError">请填写收货人地址</span><br/>
        <span class="save">保存</span>
    </div>
</div>
<div id="bill">
    <span style="font-size: 30px;">商品清单</span><span class="modify-mycart">返回修改购物车</span>
    <ul>
        <?php
        foreach($goodsBills as $buyGoods){
        ?>
            <li class="goodsBills" goods-id="<?php echo $buyGoods->getId(); ?>" >
                <span><img class="img" src="<?php echo $buyGoods->getSrc(); ?>" alt="" /></span>
                <span class="common-bordr name"><?php echo $buyGoods->getName(); ?></span>
                <span class="common-bordr price"><?php echo $buyGoods->getPrice(); ?></span>
                <span class="common-bordr number"><?php echo $buyGoods->getNumberInCart(); ?></span>
                <span class="common-bordr storeNumber">
                    <?php
                        if($buyGoods->getStoreNumber() >= $buyGoods->getNumberInCart()){
                            echo '有货';
                        }else{
                            echo '<span style="color: gray;">库存不足</span>';
                        }
                    ?>
                </span>
        </li>
        <?php
        }
        ?>
    </ul>
</div>
<br/>
<div>
    <span>应付总额：</span>
    <span style="color: red;"><?php echo $orderController->totalPrice(); ?></span>元
    <span class="submit-order">提交订单</span>
</div>
<?php include_once __DIR__ . '/../../views/footer.php' ?>
</body>
</html>