<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/11/27
 * Time: 17:30
 */
include_once __DIR__ . '/lib/DB.php';
class TestDB{
    public function TestDB(){
        $db = new DB();
        $db->connectToDb();
        echo $db->getConn();
    }
}
$test = new TestDB();