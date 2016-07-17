<?php namespace EventSourced\EventLog;

class EventBuilder
{
    private $id_generator;
    private $datetime_generator;
    private $event;
    
    public function __construct(IDGenerator $id_generator, DateTimeGenerator $datetime_generator)
    {
        $this->id_generator = $id_generator;
        $this->datetime_generator = $datetime_generator;
        
        $this->setup_fresh_event();
    }
    
    private function setup_fresh_event()
    {
        $this->event = new Event(); 
        $this->setup_optional_properties();
        $this->setup_mandatory_properties();
    }
    
    private function setup_optional_properties()
    {
        $this->event->event_id = null;
        $this->occured_at = null;
    }
    
    private function setup_mandatory_properties()
    {
        $this->event->payload = null;        
        $this->event->aggregate_id = null;
        $this->event->command_id = null;
        $this->event->schema = new Schema();
    }
    
    public function set_event_id($id)
    {
        $this->event->event_id = $id;
        return $this;
    }
    
    public function set_aggregate_id($id)
    {
        $this->event->aggregate_id = $id;
        return $this;
    }
    
    public function set_command_id($id)
    {
        $this->event->command_id = $id;
        return $this;
    }
    
    public function set_payload($payload)
    {
        $this->event->payload = $payload;
        return $this;
    }
    
    public function set_occured_at($datetime)
    {
        $this->event->occured_at = $datetime;
        return $this;
    }
  
    public function set_schema_event_id($id)
    {
        $this->event->schema->event_id = $id;
        return $this;
    }
    
    public function set_schema_aggregate_id($id)
    {
        $this->event->schema->aggregate_id = $id;
        return $this;
    }
    
    public function build()
    {
        $event = $this->event;
        $event->event_id = $event->event_id ?: $this->id_generator->generate();
        $event->occured_at = $event->occured_at ?: $this->datetime_generator->generate();
        
        if (is_null($event->payload)) {
            throw new EventBuilderException("Payload is missing, cannot build event");
        }
        if (is_null($event->aggregate_id)) {
            throw new EventBuilderException("AggregateID is missing, cannot build event");
        }
        if (is_null($event->command_id)) {
            throw new EventBuilderException("CommandID is missing, cannot build event");
        }
        if (is_null($event->schema->event_id)) {
            throw new EventBuilderException("Schema EventID is missing, cannot build event");
        }
        if (is_null($event->schema->aggregate_id)) {
            throw new EventBuilderException("Schema AggregateID is missing, cannot build event");
        }
        
        $this->setup_fresh_event();
        
        return $event;
    }
}
