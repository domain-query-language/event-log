<?php namespace Tests\EventLog;

use Tests\TestCase;
use EventSourced\EventLog\EventBuilder;
use EventSourced\EventLog\DateTimeGenerator;
use EventSourced\EventLog\IDGenerator;
use EventSourced\EventLog\StreamID;
use EventSourced\EventLog\EventRepository;

abstract class AbstractEventRepositoryTest extends TestCase
{
    protected $event_builder;
    protected $event1;
    protected $event2;
    protected $event3;
    protected $repo;
    protected $stream_id;
    
    public function setUp()
    {
        parent::setUp();

        $id_generator = new IDGenerator();
        $datetime_generator = new DateTimeGenerator();
        $this->event_builder = new EventBuilder($id_generator, $datetime_generator);
        
        $this->repo = $this->build_event_repository();
        
        $this->stream_event1 = $this->make_event("c91942f1-3c94-4900-a3b0-4497311e3503", "a955d32b-0130-463f-b3ef-23adec9af469");
        $this->stream_event2 = $this->make_event("b2527176-edcc-4db9-818a-9a4e5767f350", "a955d32b-0130-463f-b3ef-23adec9af469");
        $this->other_event = $this->make_event("11386b92-690e-4e15-8418-ff3c1688bad8", "343d56cf-6c4d-4d5d-9040-c7dd74ab65b9");
        
        $this->repo->store([$this->stream_event1, $this->stream_event2, $this->other_event]);
        
        $this->stream_id = new StreamID(
            "b5c4aca8-95c7-4b2b-8674-ef7c0e3fd16f",
            "a955d32b-0130-463f-b3ef-23adec9af469"  
        );
    }
    
    private function make_event($event_id, $aggregate_id)
    {
        $this->event_builder->set_event_id($event_id)
            ->set_aggregate_id($aggregate_id)
            ->set_command_id("88f2ecaa-81dd-467f-851d-cdd214f3f3bb")
            ->set_schema_event_id("14c3896d-092e-4370-bf72-2093facc9792")
            ->set_schema_aggregate_id("b5c4aca8-95c7-4b2b-8674-ef7c0e3fd16f")
            ->set_occured_at("2014-10-10 12:12:12")
            ->set_payload((object)['value'=>true]);
        
        return $this->event_builder->build();
    }
    
    /** @return EventRepository */
    abstract protected function build_event_repository();
    
    public function test_fetch()
    {
        $results = $this->repo->fetch($this->stream_id, 0, 10);
        
        $this->assertEquals([$this->stream_event1, $this->stream_event2], $results);
    }
    
    public function test_returns_empty_if_no_results()
    {
        $stream_id = new StreamID(
            "b5c4aca8-95c7-4b2b-8674-ef7c0e3fd16f",
            "03ad4280-01f3-450b-8e0a-55c1365e40ee"  
        );
                
        $results = $this->repo->fetch($stream_id, 0, 10);
        
        $this->assertEquals([], $results);
    }
    
    public function test_fetch_all()
    {
        $results = $this->repo->fetch_all(0, 10);
        
        $this->assertEquals([$this->stream_event1, $this->stream_event2, $this->other_event], $results);
    }
}