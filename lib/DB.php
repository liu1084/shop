<?php

/**
 * Created by IntelliJ IDEA.
 * User: Administrator
 * Date: 2014/11/18
 * Time: 23:47
 */
include_once __DIR__ . '/../config/db-config.php';

class DB {
    private $conn;
    private $host;
    private $port;
    private $password;
    private $username;
    private $databaseName;

    /**
     * @return mixed
     */
    public function getDatabaseName() {
        return $this->databaseName;
    }

    /**
     * @param mixed $databaseName
     */
    public function setDatabaseName($databaseName) {
        $this->databaseName = $databaseName;
    }

    /**
     * @return mixed
     */
    public function getConn() {
        return $this->conn;
    }

    /**
     * @param mixed $conn
     */
    public function setConn($conn) {
        $this->conn = $conn;
    }

    /**
     * @return mixed
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host) {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * @param mixed $port
     */
    public function setPort($port) {
        $this->port = $port;
    }

    /**
     * @return mixed
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    public function query($sql) {
        try {
            return mysql_query($sql);
        } catch(Exception $e) {
            echo json_encode($e);
        }
    }

    public function connectToDb() {
        $this->conn = mysql_connect($this->getHost() . ':' . $this->getPort(),
            $this->getUsername(), $this->getPassword()) or die(CONN_ERROR);
        mysql_select_db($this->getDatabaseName(), $this->conn);
        mysql_query("set names utf8");
        return $this->conn;
    }

    private function closeDb() {
        return mysql_close();
    }

    public function insert($tableName, $records) {
        $fields = array_keys($records);
        $fieldVlues = array_values($records);

        $sql = ' INSERT INTO `' . $tableName . '` (`';
        $sql .= implode('`,`', $fields);
        $sql .= '`) VALUES("';

        $sql .= implode('","', $fieldVlues);
        $sql .= '")';

        return $this->query($sql);
    }

    public function getInsertId(){
        return mysql_insert_id($this->conn);
    }

    public function getAffectedRows(){
        return mysql_affected_rows($this->conn);
    }

    public function update($tableName, $records, $wheres) {

        $sql = ' UPDATE `' . $tableName . '`SET ';
        foreach ($records as $key => $value) {
            $sql .= '`' . $key . '` = "' . $value . '",';
        }
        $sql = mb_substr($sql, 0, mb_strlen($sql) - 1);
        $sql .= ' WHERE ' . $wheres;
        //echo $sql;exit;
        return $this->query($sql);
    }

    public function read($tableName, $wheres, $fields = [], $isAllFields = false) {
        $sql = ' SELECT ';
        //用户想取得所有字段
        if (count($fields) == 0 && $isAllFields === true) {
            $fields = ['*'];
        }

        foreach ($fields as $field) {
            $sql .= $field . ',';
        }

        $sql = mb_substr($sql, 0, mb_strlen($sql) - 1);
        $sql .= ' FROM ' . $tableName;
        $sql .= ' WHERE ' . $wheres;

        return $this->query($sql);

    }

    public function delete($tableName, $wheres) {
        $sql = ' DELETE FROM `' . $tableName . '` WHERE ';
        $sql .= $wheres;
        //echo $sql;exit;
        return $this->query($sql);
    }

    public function count($tableName, $wheres, $alias = 'count') {
        $sql = ' SELECT COUNT(1) ' . $alias . ' FROM `' . $tableName . '` WHERE ';
        $sql .= $wheres;
        return $this->query($sql);
    }

    public function DB() {
        $this->host = HOST;
        $this->port = PORT;
        $this->username = USERNAME;
        $this->password = PASSWORD;
        $this->databaseName = DBNAME;
    }
}