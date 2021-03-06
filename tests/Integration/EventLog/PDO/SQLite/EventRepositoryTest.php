<?php namespace Tests\Integration\EventLog\PDO\SQLite;

use EventSourced\EventLog\Adapter\PDO\SQLite\EventRepository;
use EventSourced\EventLog\Adapter\PDO\SQLite\Migration\CreateEventLogTable;
use Tests\Integration\EventLog\AbstractEventRepositoryTest;

class EventRepositoryTest extends AbstractEventRepositoryTest
{    
    private $pdo;
    private $migration;
    
    public function setUp()
    {
        $this->pdo = (new PDOFactory())->make();
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