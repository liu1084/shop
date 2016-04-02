<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/11/26
 * Time: 20:08
 */

include_once __DIR__ . '/../Controller.php';
include_once __DIR__ . '/../../service/impl/CartServiceImpl.php';
include_once __DIR__ . '/../../model/CartGoods.php';
include_once __DIR__ . '/../../service/impl/GoodsServiceImpl.php';

class CartController extends Controller {
    private $cartServiceImpl;
    public function __construct() {
        $this->cartServiceImpl = new CartServiceImpl();
    }

    public function actionAddGoods() {
        parent::checkIsLogin();
        $goodsAddId = $_POST['goodsAddId'];
        $goodsAddNumber = $_POST['goodsAddNumber'];
        $cartGoods = new CartGoods();
        $cartGoods->setId($goodsAddId);
        $cartGoods->setNumber($goodsAddNumber);
        $result = $this->cartServiceImpl->addGoodsToCart($cartGoods);
        //ajax 提交
        if ($this->isAjaxRequest() === true) {
            echo json_encode(array('result' => $result));
            exit(0);
        }
        //非ajax提交
        return $result;
    }

    public function actionDeleteGoods() {
        $cartGoodsId = $_POST['cartGoodsId'];
        $goodsId = $_POST['goodsId'];
        if($cartGoodsId == ""){                              //新增的cart记录中id属性是空的，数据库中自增，要从数据库里面取得
            $goodsServiceImpl = new GoodsServiceImpl();
            $cartGoodsId = $goodsServiceImpl->getCartGoodsId($goodsId);
        }
        $this->cartServiceImpl->deleteGoodsFromCart($cartGoodsId);
    }

    public function actionIndex() {
        //渲染
        //renderView(url, array());
        //$this->renderView(__DIR__ . '/../../views/cart.php', array(...));
        $goodsInCart = $this->cartServiceImpl->getGoodsFromCart();
        $goodsServiceImpl = new GoodsServiceImpl();
        $allGoods = $goodsServiceImpl->getAllGoods();
        include_once __DIR__ . '/../../views/cart/index.php';
    }

}

$cartController = new CartController();
$action = $cartController->getAction();

switch ($action) {
    case 'AddGoods':
        $cartController->actionAddGoods();
        break;
    case 'DeleteGoods':
        $cartController->actionDeleteGoods();
        break;
    default:
        $cartController->actionIndex();
        break;
}