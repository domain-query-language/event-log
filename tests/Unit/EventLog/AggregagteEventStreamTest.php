<?php namespace Tests\Unit\EventLog;

use EventSourced\EventLog\StreamID;
use EventSourced\EventLog\AggregateEventStream;

class AggregagteEventStreamTest extends \Tests\TestCase
{
    private $aggregate_id;
    private $event_repository;
    
    public function setUp()
    {
        $this->aggregate_id = new StreamID(
            "b5c4aca8-95c7-4b2b-8674-ef7c0e3fd16f",
            "a955d32b-0130-463f-b3ef-23adec9af469"  
        );
        $this->event_repository = new EventRepository();
    }
    
    public function test_iterates_through_list()
    {
        $this->assert_row_count(10);
    }

    private function assert_row_count($expected_count)
    {
        $this->event_repository->set_row_count($expected_count);
        $event_stream = new AggregateEventStream($this->event_repository, $this->aggregate_id);
        
        $count = 0;
        foreach ($event_stream as $event) {
            $count++;
        } 
        $this->assertEquals($expected_count, $count);
    }

    public function test_empty_results()
    {
        $this->assert_row_count(0);
    }
    
    public function test_loads_next_chunk_when_reaches_end()
    {
        $this->assert_row_count(150);
    }
    
    public function test_stops_if_next_chunk_has_no_results()
    {
        $this->assert_row_count(100);
    }
}