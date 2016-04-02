<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/11/26
 * Time: 20:02
 */

include_once __DIR__ . '/../CartService.php';
include_once __DIR__ . '/../../model/Cart.php';
include_once __DIR__ . '/../Service.php';
include_once __DIR__ . '/SessionServiceImpl.php';

class CartServiceImpl extends Service implements CartService {
    private $cart;
    private $sessionServiceImpl;
    private $userId;
    private $isLogin;
    public function CartServiceImpl(){
        parent::__construct();//执行父类的构造方法，手动的，不是自动哦。
        $this->cart = new Cart();
        $this->sessionServiceImpl = new SessionServiceImpl();
        $this->userId = $this->sessionServiceImpl->getSession('id');
        $this->isLogin = $this->sessionServiceImpl->getSession('isLogin');
    }

    public function addGoodsToCart(CartGoods $cartGoods) {
        if($this->isLogin === true){
            //要添加到购物车里面的商品是否已经存在
            $goodsAddId = $cartGoods->getId();
            $goodsAddNumber = $cartGoods->getNumber();
            $wheres = 'goodsId = "' . $goodsAddId . '" ';
            $wheres .= 'AND userId = "' . $this->userId . '"';
            $cartsGoods = $this->getDb()->read('cart', $wheres, ['goodsNumber']);
            if (!empty($cartsGoods) && isset($cartsGoods)){ //查询正确
                $row = mysql_fetch_object($cartsGoods); //资源里面有值
                if (!empty($row) && isset($row)){
                    return $this->getDb()->update('cart', ['goodsNumber' => $row->goodsNumber + $goodsAddNumber], $wheres);
                }
            }
            return $this->getDb()->insert('cart',array('goodsId' => $goodsAddId,'userId' => $this->userId,'goodsNumber' => $goodsAddNumber));

        }

    }

    public function deleteGoodsFromCart($cartGoodsId) {
        if($this->isLogin === true){
            $where = 'id = ' . $cartGoodsId;
            $this->getDb()->delete('cart',$where);
        }

    }
    public function getGoodsFromCart(){
        if($this->isLogin === true){
            $goodsInCart = [];
            $where = 'userId = "' . $this->userId . '"';
            $query = $this->getDb()->read('cart',$where,[],true);
            while($row = mysql_fetch_object($query)){
                $goodsInCart[] = $row;
            }
            return $goodsInCart;
        }

    }
}