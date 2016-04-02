<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/12/15
 * Time: 21:21
 */
include_once __DIR__ . '/../Service.php';
include_once __DIR__ . '/../GoodsService.php';
include_once __DIR__ . '/../../model/Goods.php';
include_once __DIR__ . '/SessionServiceImpl.php';


class GoodsServiceImpl extends Service implements GoodsService{
    private $goods;
    public function __construct(){
        parent::__construct();
        $this->goods = new Goods();
    }
    public function getGoods($id){
        $where = 'id = ' . $id;
        $query = $this->getDb()->read('goods',$where,[],true);
        if (!empty($query) && isset($query)){
            $row = mysql_fetch_object($query);
            $this->goods->setName($row->goodsName);
            $this->goods->setPrice($row->goodsPrice);
            $this->goods->setSrc($row->goodsSrc);
        }
        return $this->goods;
    }
    public function getAllGoods(){
        $allGoods = [];
        $where = '1=1';
        $query = $this->getDb()->read('goods',$where,[],true);
        while($row = mysql_fetch_object($query)){
            $allGoods[] = $row;
        }
        return $allGoods;

    }
    //通过当前登陆的用户的userId和某一商品的goodsId可以唯一确定一条cart表中记录
    public function getCartGoodsId($goodsId){
        $sessionServiceImpl = new SessionServiceImpl();
        $userId = $sessionServiceImpl->getSession('id');
        $where = 'userId = "' . $userId . '" ';
        $where .= 'AND goodsId = "' . $goodsId . '"';
        $query = $this->getDb()->read('cart',$where,['id']);
        $row = mysql_fetch_object($query);
        return $row->id;
    }
} 