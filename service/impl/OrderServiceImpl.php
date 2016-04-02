<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/12/23
 * Time: 14:25
 */
include_once __DIR__ . '/../Service.php';
include_once __DIR__ . '/../OrderService.php';
include_once __DIR__ . '/../../model/CartGoods.php';
class OrderServiceImpl extends Service implements OrderService {
    private $userId;
    public function __construct(){
        parent::__construct();
        $this->userId = $this->getSessionServiceImpl()->getSession('id');
    }
    public function getCustomerInfo(){
        $customerInfo = [];
        $wheres = 'userId = ' . $this->userId;

        $query = $this->getDb()->read('user_customer_info',$wheres,['id','customerName','customerPhone','customerAddr']);
        while($row = mysql_fetch_object($query)){
            $customerInfo[] = $row;
        }

        return $customerInfo;
    }
    public function deleteCustomerInfo($id){
        $wheres = 'id = ' . $id;
        $this->getDb()->delete('user_customer_info',$wheres);
    }
    public function editCustomerInfo($id,$name,$phone,$address){
        $records = ['customerName' => $name , 'customerPhone' => $phone , 'customerAddr' => $address];
        $wheres = 'id = ' . $id;
        $this->getDb()->update('user_customer_info',$records,$wheres);
        //return true;
    }
    public function addCustomerInfo($name,$phone,$address){
        $records = ['userId' => $this->userId , 'customerName' => $name , 'customerPhone' => $phone , 'customerAddr' => $address];
        $this->getDb()->insert('user_customer_info',$records);
        $id = $this->getDb()->getInsertId();
        return $id;
    }
    public function showGoodsBills(){
        $goodsBills = [];
        $checkedGoods = $this->getSessionServiceImpl()->getSession('checkedGoods');
        foreach($checkedGoods as $eachGoods){
            $goodsId = $eachGoods['goodsId'];
            $number = $eachGoods['number'];
            $wheres = 'id = ' . $goodsId;
            $query = $this->getDb()->read('goods',$wheres,[],true);
            $row = mysql_fetch_object($query);
            $buyGoods = new CartGoods();
            $buyGoods->setId($goodsId);
            $buyGoods->setNumberInCart($number);
            $buyGoods->setName($row->goodsName);
            $buyGoods->setPrice($row->goodsPrice);
            $buyGoods->setSrc($row->goodsSrc);
            $buyGoods->setStoreNumber($row->goodsStoreNumber);

            $goodsBills[] = $buyGoods;
        }
        return $goodsBills;
    }

    public function submitOrder($customerInfoId,$bills){
        $totalPrice = 0;
        foreach($bills as $eachGoods){
            $goodsId = $eachGoods['goodsId'];
            $number = $eachGoods['number'];
            $wheres_for_goods = 'id = ' . $goodsId;
            //查询商品表
            $query = $this->getDb()->read('goods',$wheres_for_goods,['goodsPrice','goodsStoreNumber']);
            $row = mysql_fetch_object($query);
            $totalPrice += $row->goodsPrice * $number;
            $orderNumber = uniqid();
            $orderNumber .= time();
            //$orderNumber .= mt_rand();
            $orderNumber .= $_SERVER['REMOTE_ADDR'];
            //$orderNumber .= $_SERVER['REMOTE_HOST'];
            $orderNumber = md5($orderNumber);
            $records = ['orderNUmber'=>$orderNumber,'goodsId'=>$goodsId,'goodsNumber'=>$number,
                        'customerInfoId'=>$customerInfoId,'goodsPrice'=>$row->goodsPrice];
            //插入订单表
            $this->getDb()->insert('bills',$records);
            $newStoreNumber = $row->goodsStoreNumber - $number;
            //更新库存
            $this->getDb()->update('goods',['goodsStoreNumber'=>$newStoreNumber],$wheres_for_goods);
            $wheres_for_cart = 'goodsId = ' . $goodsId;
            $wheres_for_cart .= ' AND userId = ' . $this->userId;
            //删除购物车
            $this->getDb()->delete('cart',$wheres_for_cart);
        }
        return $totalPrice;

    }
}
