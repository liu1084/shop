<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/11/26
 * Time: 20:00
 */
interface CartService{
    //添加商品到购物车
    public function addGoodsToCart(CartGoods $goods);
    //从购物车中删除商品
    public function deleteGoodsFromCart($cartGoodsId);
    public function getGoodsFromCart();
}