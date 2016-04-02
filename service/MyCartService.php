<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/11/26
 * Time: 20:00
 */
interface MyCartService {
    public function showMyCart();
    public function updateGoodsNumber($goodsId,$goodsNumber);
    public function totalChecked($checkedGoods);
}