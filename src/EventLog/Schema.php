<?php namespace EventSourced\EventLog;

class Schema
{
    private $event_id; // ID of this event type
    private $aggregate_id; // ID of the this aggregate type
    
    public function __construct($event_id, $aggregate_id)
    {
        $this->event_id = $event_id;
        $this->aggregate_id = $aggregate_id;
    }
    
    public function event_id()
    {
        return $this->event_id;
    }
    
    public function aggregate_id()
    {
        return $this->aggregate_id;
    }
}
