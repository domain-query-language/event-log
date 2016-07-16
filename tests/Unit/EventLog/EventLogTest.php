<?php namespace Tests\Unit\EventLog;

use EventSourced\EventLog\EventLog;
use EventSourced\EventLog\EventRepository;
use EventSourced\EventLog\EventStreamFactory;
use EventSourced\EventLog\AggregateEventStream;
use EventSourced\EventLog\StreamID;

class EventLogTest extends \Tests\TestCase 
{
    private $stub_event_repo;
    private $stub_event_factory;
    private $event_log;
    
    public function setUp()
    {
        $this->stub_event_repo = $this->mock(EventRepository::class);
        $this->stub_event_factory = $this->stub(EventStreamFactory::class);
        $this->event_log = new EventLog(
            $this->stub_event_repo->reveal(), 
            $this->stub_event_factory->reveal()
        );
    }

    public function test_takes_in_data()
    {
        $data = ['data'];
        
        $this->stub_event_repo->store($data)->shouldBeCalled();

        $this->event_log->store($data);
    }
    
    public function test_returns_stream()
    {   
        $aggregate_id = $this->dummy(StreamID::class);
        
        $stream = new AggregateEventStream($this->stub_event_repo->reveal(), $aggregate_id);
        $this->stub_event_factory->aggregate_id($aggregate_id)
             ->willReturn($stream);
        
        $this->assertEquals($stream, $this->event_log->fetch($aggregate_id));
    }
    
    public function test_can_fetch_full_stream()
    {
        $stream = 'stream';
        $this->stub_event_factory->all()
             ->willReturn($stream);
        
        $this->assertEquals($stream, $this->event_log->all());
    }
}