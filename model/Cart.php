<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/11/18
 * Time: 23:55
 */

class Cart
{
    private $goods = [];                    //一维数组，存放的是FIMME!
    private $totalPrice;
    private $isEmpty = true;
    /**
     * @return array
     */
    public function getGoods()
    {
        return $this->goods;
    }

    /**
     * @param array $goods
     */
    public function setGoods($goods)
    {
        $this->goods = $goods;
    }

    /**
     * @return mixed
     */
    public function getIsEmpty()
    {
        return $this->isEmpty;
    }

    /**
     * @param mixed $isEmpty
     */
    public function setIsEmpty($isEmpty)
    {
        $this->isEmpty = $isEmpty;
    }


    /**
     * @return mixed
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @param mixed $totalPrice
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

}