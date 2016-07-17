<?php namespace Tests\Integration\EventLog\PDO\SQLite;

class PDOFactory implements \Tests\Integration\EventLog\PDO\PDOFactory
{
    public function make()
    {
        $pdo = new \PDO('sqlite::memory:');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}

