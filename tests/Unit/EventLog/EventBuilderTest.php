<?php namespace Tests\Unit\EventLog;

use EventSourced\EventLog\EventBuilder;
use EventSourced\EventLog\Event;
use EventSourced\EventLog\IDGenerator;
use EventSourced\EventLog\DateTimeGenerator;
use EventSourced\EventLog\Schema;

class EventBuilderTest extends \Tests\TestCase
{
    private $event_builder;
    private $event;
    
    public function setUp()
    {
        $stub_id_generator = $this->stub(IDGenerator::class);
        $stub_id_generator->generate()->willReturn("87484542-4a35-417e-8e95-5713b8f55c8e");
        
        $stub_datetime_generator = $this->stub(DateTimeGenerator::class);
        $stub_datetime_generator->generate()->willReturn('2014-10-10 12:12:12');
        
        $this->event_builder = new EventBuilder(
            $stub_id_generator->reveal(), 
            $stub_datetime_generator->reveal()
        );
        $this->event_builder->set_aggregate_id("a955d32b-0130-463f-b3ef-23adec9af469")
            ->set_payload((object)['value'=>true])     
            ->set_command_id("88f2ecaa-81dd-467f-851d-cdd214f3f3bb")
            ->set_schema_event_id("14c3896d-092e-4370-bf72-2093facc9792")
            ->set_schema_aggregate_id("b5c4aca8-95c7-4b2b-8674-ef7c0e3fd16f");
        
        $this->event = $this->event_builder->build();
    }
    
    public function test_builds_class()
    {
        $this->assertInstanceOf(Event::class, $this->event);
    }
    
    public function test_gives_event_id()
    {
        $this->assertEquals("87484542-4a35-417e-8e95-5713b8f55c8e", $this->event->event_id);
    }
    
    public function test_gives_occured_at()
    {
        $this->assertEquals("2014-10-10 12:12:12", $this->event->occured_at);
    }
   
    public function test_populates_schema()
    {
        $expected = new Schema();
        $expected->event_id = "14c3896d-092e-4370-bf72-2093facc9792";
        $expected->aggregate_id = "b5c4aca8-95c7-4b2b-8674-ef7c0e3fd16f";
      
        $this->assertEquals($expected, $this->event->schema);
    }
    
    public function test_populates_domain()
    {
        $aggregate_id = 'a955d32b-0130-463f-b3ef-23adec9af469';
        $payload = (object)['value'=>true];
        $command_id = "88f2ecaa-81dd-467f-851d-cdd214f3f3bb";
        
        $this->assertEquals($aggregate_id, $this->event->aggregate_id);
        $this->assertEquals($payload, $this->event->payload);
        $this->assertEquals($command_id, $this->event->command_id);
    }
    
    public function test_resets_after_build()
    {
        $event = $this->event_builder->build();
        
        $this->assertNull($event->aggregate_id);
        $this->assertNull($event->payload);
        $this->assertEquals(new Schema(), $event->schema);
    }
    
    public function test_can_set_id()
    {
        $id = 'a7285082-a50c-4593-8b13-06a0fd75ba71';
        $this->event_builder->set_event_id($id);
        
        $event = $this->event_builder->build();
        $this->assertEquals($id, $event->event_id);
    }
    
    public function test_can_set_occurred_at()
    {
        $datetime = "2014-10-10 12:12:12";
        $this->event_builder->set_occured_at($datetime);
        
        $event = $this->event_builder->build();
        $this->assertEquals($datetime, $event->occured_at);
    }
}