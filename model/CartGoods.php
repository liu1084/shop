<?php

/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/11/25
 * Time: 22:51
 */
include_once __DIR__ . '/Goods.php';
class CartGoods extends Goods
{
    private $numberInCart = 0;//购物车里某个商品的数量
    private $cartGoodsId = '';
    private $userId = '';

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getCartGoodsId() {
        return $this->cartGoodsId;
    }

    public function setCartGoodsId($cartGoodsId) {
        $this->cartGoodsId = $cartGoodsId;
    }

    public function getNumberInCart()
    {
        return $this->numberInCart;
    }

    public function setNumberInCart($numberInCart)
    {
        $this->numberInCart = $numberInCart;
    }

} 