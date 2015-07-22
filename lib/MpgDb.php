<?php
/**
 * Created by PhpStorm.
 * User: Jeff
 * Date: 6/26/2015
 * Time: 9:18 AM
 */

class MpgDb {
    /** @var mysqli $connection **/
    private $connection;
    /** @var mysqli_result $result **/
    private $result;

    function __construct() {
        $this->connection = $this->getConn();
    }

    function __destruct() {
        $this->connection->close();
    }

    private function getConn()
    {
        $mysqli = new mysqli(Config::getDbHost(), Config::getDbUser(), Config::getDbPass(), Config::getDbName());
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
        return $mysqli;
    }

    public function runQuery($query) {
        $this->result = $this->connection->query($query);
        if (strlen($this->connection->error) > 0) {
            printf('Error running query: %s', $this->connection->error);
        }
    }

    public function runInsertQuery($query) {
        $this->runQuery($query);
        $this->connection->commit();
        return $this->getRowCount();
    }

    public function getRowCount() {
        return $this->connection->affected_rows;
    }

    public function getRow() {
        return $this->result->fetch_array();
    }

    public function escapeString($string) {
        return $this->connection->real_escape_string($string);
    }
}
