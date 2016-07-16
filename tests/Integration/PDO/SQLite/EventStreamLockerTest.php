<?php namespace Tests\Integration\EventLog\PDO\SQLite;

use Tests\Integration\EventLog\AbstractEventStreamLockerTest;
use EventSourced\EventLog\Adapter\PDO\SQLite\EventStreamLocker;
use EventSourced\EventLog\DateTimeGenerator;
use EventSourced\EventLog\Adapter\PDO\SQLite\Migration\CreateEventStreamLockerTable;

class EventStreamLockerTest extends AbstractEventStreamLockerTest
{    
    private $pdo;
    private $migration;
    
    public function setUp()
    {
        $this->pdo = new \PDO('sqlite::memory:');
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