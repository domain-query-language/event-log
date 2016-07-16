<?php namespace Tests\EventLog\PDO;

use EventSourced\EventLog\Adapter\PDO\SQLite\EventRepository;
use EventSourced\EventLog\Adapter\PDO\SQLite\Migration\CreateEventLogTable;
use Tests\EventLog\AbstractEventRepositoryTest;

class EventRepositoryTest extends AbstractEventRepositoryTest
{    
    private $pdo;
    private $migration;
    
    public function setUp()
    {
        $this->pdo = new \PDO('sqlite::memory:');
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->migration = new CreateEventLogTable($this->pdo);
        $this->migration->up();
        parent::setUp();
    }
    
    protected function build_event_repository()
    {
        return new EventRepository($this->pdo, $this->event_builder);
    }
    
    public function tearDown()
    {
        parent::tearDown();
        $this->migration->down();
    }
}