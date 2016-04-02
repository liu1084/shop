<?php
/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/11/28
 * Time: 0:56
 */
include_once __DIR__ . '/../lib/DB.php';
include_once __DIR__ . '/impl/SessionServiceImpl.php';
class Service {
    private $conn;
    private $db;
    private $sessionServiceImpl;

    public function getSessionServiceImpl() {
        return $this->sessionServiceImpl;
    }

    public function getConn() {
        return $this->conn;
    }

    public function getDb() {
        return $this->db;
    }

    public function __construct(){
        $this->db = new DB();
        $this->conn = $this->db->connectToDb();
        $this->sessionServiceImpl = new SessionServiceImpl();
    }
} 