<?php

namespace ProgWeb\TodoWeb\System;

class DbConnector {

    private $dbConnection = null;

    public function __construct()
    {
        $host = Config::DB_HOST;
        $port = Config::DB_PORT;
        $db   = Config::DB_DATABASE;
        $user = Config::DB_USERNAME;
        $pass = Config::DB_PASSWORD;

        try {
            $this->dbConnection = new \PDO(
                "mysql:host=$host;port=$port;charset=utf8mb4;dbname=$db",
                $user,
                $pass
            );
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->dbConnection;
    }
}
