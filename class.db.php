<?php

class Db
{

    private static $instance;
    private $pdo;
    private $log;

    private function __construct()
    {

    }

    private function __clone()
    {

    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function connect()
    {
        if (!$this->pdo) {
            $this->pdo = new PDO("mysql:host=localhost;dbname=orders", 'root', '');
        }

        return $this->pdo;
    }

    public function error()
    {

    }

    public function exec(string $query, array $params = [])
    {
        $this->connect();
        $query = $this->pdo->prepare($query);
        $ret = $query->execute($params);

        if (!$ret) {
            if ($query->errorCode()) {
                trigger_error(json_encode($query->errorInfo()));
            }
            return false;
        }


        return $query->rowCount();
    }

    public function fetchAll(string $query)
    {
        $this->connect();
        $query = $this->pdo->prepare($query);
        $ret = $query->execute();

        if (!$ret) {
            if ($query->errorCode()) {
                trigger_error(json_encode($query->errorInfo()));
            }
            return false;
        }

        return $query->fetchAll($this->pdo::FETCH_ASSOC);
    }

    public function fetchOne(string $query, array $params = [])
    {
        $this->connect();
        $query = $this->pdo->prepare($query);
        $ret = $query->execute($params);

        if (!$ret) {
            if ($query->errorCode()) {
                trigger_error(json_encode($query->errorInfo()));
            }
            return false;
        }

        $result = $query->fetchAll($this->pdo::FETCH_ASSOC);
        return reset($result);
    }

    public function lastInsertID()
    {
        $this->connect();
        return $this->pdo->lastInsertId();
    }


}
