<?php namespace Tests\Integration\EventLog\PDO\MySQL;

class PDOFactory implements \Tests\Integration\EventLog\PDO\PDOFactory
{
    public function make()
    {
        $pdo = new \PDO('mysql:host=192.168.10.11;dbname=homestead;charset=utf8mb4', 'homestead', 'secret');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}

