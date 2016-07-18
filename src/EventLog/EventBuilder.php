<?php namespace EventSourced\EventLog;

class EventBuilder
{
    private $id_generator;
    private $datetime_generator;
    
    private $event_id;
    private $occured_at;
    
    private $payload;
    private $aggregate_id;
    private $command_id;
    private $schema_event_id;
    private $schema_aggregate_id;
    
    public function __construct(IDGenerator $id_generator, DateTimeGenerator $datetime_generator)
    {
        $this->id_generator = $id_generator;
        $this->datetime_generator = $datetime_generator;
        
        $this->setup_fresh_event();
    }
    
    private function setup_fresh_event()
    {
        $this->setup_optional_properties();
        $this->setup_mandatory_properties();
    }
    
    private function setup_optional_properties()
    {
        $this->event_id = null;
        $this->occured_at = null;
    }
    
    private function setup_mandatory_properties()
    {
        $this->payload = null;        
        $this->aggregate_id = null;
        $this->command_id = null;
        $this->schema_event_id = null;
        $this->schema_aggregate_id = null;
    }
    
    public function set_event_id($id)
    {
        $this->event_id = $id;
        return $this;
    }
    
    public function set_aggregate_id($id)
    {
        $this->aggregate_id = $id;
        return $this;
    }
    
    public function set_command_id($id)
    {
        $this->command_id = $id;
        return $this;
    }
    
    public function set_payload($payload)
    {
        $this->payload = $payload;
        return $this;
    }
    
    public function set_occured_at($datetime)
    {
        $this->occured_at = $datetime;
        return $this;
    }
  
    public function set_schema_event_id($id)
    {
        $this->schema_event_id = $id;
        return $this;
    }
    
    public function set_schema_aggregate_id($id)
    {
        $this->schema_aggregate_id = $id;
        return $this;
    }
    
    /** @return Event */
    public function build()
    {
        $this->assert_all_inputs_exist();
        $this->generate_defaults();
        
        $event = $this->build_event();
        
        $this->setup_fresh_event();
        
        return $event;
    }
    
    private function generate_defaults()
    {
        $this->event_id = $this->event_id ?: $this->id_generator->generate();
        $this->occured_at = $this->occured_at ?: $this->datetime_generator->generate();
    }
    
    private function assert_all_inputs_exist()
    {
        if (is_null($this->payload)) {
            throw new EventBuilderException("Payload is missing, cannot build event");
        }
        if (is_null($this->aggregate_id)) {
            throw new EventBuilderException("AggregateID is missing, cannot build event");
        }
        if (is_null($this->command_id)) {
            throw new EventBuilderException("CommandID is missing, cannot build event");
        }
        if (is_null($this->schema_event_id)) {
            throw new EventBuilderException("Schema EventID is missing, cannot build event");
        }
        if (is_null($this->schema_aggregate_id)) {
            throw new EventBuilderException("Schema AggregateID is missing, cannot build event");
        }
    }
    
    private function build_event()
    {
        $schema = new Schema($this->schema_event_id, $this->schema_aggregate_id);
        return new Event(
            $this->event_id, 
            $this->aggregate_id, 
            $this->command_id, 
            $this->payload, 
            $schema, 
            $this->occured_at
        );
    }
}
