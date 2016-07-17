<?php namespace Tests\Integration\EventLog\PDO\MySQL;

use Tests\Integration\EventLog\AbstractEventStreamLockerTest;
use EventSourced\EventLog\Adapter\PDO\MySQL\EventStreamLocker;
use EventSourced\EventLog\Adapter\PDO\MySQL\Migration\CreateEventStreamLockerTable;
use EventSourced\EventLog\DateTimeGenerator;

class EventStreamLockerTest extends AbstractEventStreamLockerTest
{    
    private $pdo;
    private $migration;
    
    public function setUp()
    {
        $this->pdo = new \PDO('mysql:host=192.168.10.11;dbname=homestead;charset=utf8mb4', 'homestead', 'secret');
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->migration = new CreateEventStreamLockerTable($this->pdo);
        $this->migration->up();
        parent::setUp();
    }
    
    protected function make_locker(DateTimeGenerator $stub_datetime_generator)
    {
        return new EventStreamLocker($this->pdo, $stub_datetime_generator);
    }
    
    public function tearDown()
    {
        parent::tearDown();
        $this->migration->down();
    }
}