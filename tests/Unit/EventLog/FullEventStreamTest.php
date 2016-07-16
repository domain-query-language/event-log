<?php namespace Tests\Unit\EventLog;

use EventSourced\EventLog\FullEventStream;

class FullEventStreamTest extends \Tests\TestCase
{
    private $event_repository;
    
    public function setUp()
    {
        $this->event_repository = new EventRepository();
    }
    
    public function test_iterates_through_list()
    {
        $this->assert_row_count(10);
    }

    private function assert_row_count($expected_count)
    {
        $this->event_repository->set_row_count($expected_count);
        $event_stream = new FullEventStream($this->event_repository);
        
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