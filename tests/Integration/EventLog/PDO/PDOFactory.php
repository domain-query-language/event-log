<?php namespace Tests\Integration\EventLog\PDO;

interface PDOFactory
{
    /**
     * @return \PDO
     */
    public function make();
}

