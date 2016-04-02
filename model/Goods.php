<?php

/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/11/19
 * Time: 0:37
 */
class Goods
{
    private $id = ''; //id,唯一的
    private $price = 0; //价格
    private $storeNumber = 0; //库存量
    private $number = 0; //一次性往购物车里面添加的数量
    private $src = '';
    private $name = '';
    //private $img;
    public function getNumber() {
        return $this->number;
    }


    public function setNumber($number) {
        $this->number = $number;
    }
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getSrc() {
        return $this->src;
    }

    //图片位置
    public function setSrc($src) {
        $this->src = $src;
    }

    public function getStoreNumber()
    {
        return $this->storeNumber;
    }


    public function setStoreNumber($storeNumber)
    {
        $this->storeNumber = $storeNumber;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }



} 