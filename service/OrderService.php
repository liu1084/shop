<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/12/23
 * Time: 14:24
 */

interface OrderService {
    public function getCustomerInfo();
    public function deleteCustomerInfo($id);
    public function editCustomerInfo($id,$name,$phone,$address);
    public function addCustomerInfo($name,$phone,$address);
    public function showGoodsBills();
    public function submitOrder($customerInfoId,$bills);
}