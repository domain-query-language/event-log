<?php namespace Tests\Unit\EventLog;

use EventSourced\EventLog\EventStreamFactory;
use EventSourced\EventLog\StreamID;

class EventStreamFactoryTest extends \Tests\TestCase 
{
    private $event_stream_factory;
    
    public function setUp()
    {
        parent::setUp();
        $event_repository = new EventRepository();
        $this->event_stream_factory = new EventStreamFactory($event_repository);
    }
    
    public function test_get_aggregate_stream()
    {
        $stream_id = new StreamID(
            "b5c4aca8-95c7-4b2b-8674-ef7c0e3fd16f",
            "a955d32b-0130-463f-b3ef-23adec9af469"  
        );
        $stream = $this->event_stream_factory->aggregate_id($stream_id);
        
        $this->assertInstanceOf(\EventSourced\EventLog\AggregateEventStream::class, $stream);
    }

    public function test_get_full_stream()
    {
        $stream = $this->event_stream_factory->all();
        $this->assertInstanceOf(\EventSourced\EventLog\FullEventStream::class, $stream);
    }
}