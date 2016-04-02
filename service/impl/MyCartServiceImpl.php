<?php
include_once __DIR__ . '/SessionServiceImpl.php';
include_once __DIR__ . '/GoodsServiceImpl.php';
include_once __DIR__ . '/../../model/Goods.php';
include_once __DIR__ . '/../MyCartService.php';
include_once __DIR__ . '/../../model/CartGoods.php';
class MyCartServiceImpl extends Service implements MyCartService {
    private $sessionServiceImpl;
    private $userId;
    public function __construct(){
        parent::__construct();
        $this->sessionServiceImpl = new SessionServiceImpl();
        $this->userId = $this->sessionServiceImpl->getSession('id');
    }
    public function showMyCart(){
        $allMyCartGoods = [];
        $wheres = 'c.goodsId = g.id ';
        $wheres .= ' AND userId = ' . $this->userId;
        $query = $this->getDb()->read('cart c,goods g',$wheres,['g.id','g.goodsName','g.goodsPrice','g.goodsSrc','c.goodsNumber']);
        while($row = mysql_fetch_object($query)){
            $cartGoods= new CartGoods();
            $cartGoods->setId($row->id);
            $cartGoods->setName($row->goodsName);
            $cartGoods->setPrice($row->goodsPrice);
            $cartGoods->setSrc($row->goodsSrc);
            $cartGoods->setNumberInCart($row->goodsNumber);

            $allMyCartGoods[] = $cartGoods;
        }

        return $allMyCartGoods;

    }
    public function updateGoodsNumber($goodsId,$goodsNumber){
        $wheres = 'userId = ' . $this->userId;
        $wheres .= ' AND goodsId = ' . $goodsId;
        $this->getDb()->update('cart',['goodsNumber'=>$goodsNumber],$wheres);
    }
    public function totalChecked($checkedGoods){
        $totalChecked = 0;
        foreach($checkedGoods as $eachChecked){

            $number = $eachChecked['number'];
            $goodsServiceImpl = new GoodsServiceImpl();
            $goods = $goodsServiceImpl->getGoods($eachChecked['goodsId']);
            $price = $goods->getPrice();
            $totalChecked += $number * $price;
        }
        return $totalChecked;
    }

}