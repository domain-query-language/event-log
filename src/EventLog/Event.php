<?php namespace EventSourced\EventLog;

class Event
{
    private $event_id;
    private $aggregate_id;
    private $command_id;
    private $payload;
    private $schema;
    private $occured_at;
    
    public function __construct($event_id, $aggregate_id, $command_id, $payload, Schema $schema, $occured_at)
    {
        $this->event_id = $event_id;
        $this->aggregate_id = $aggregate_id;
        $this->command_id = $command_id;
        $this->payload = $payload;
        $this->schema = $schema;
        $this->occured_at = $occured_at;
    }
    
    function event_id()
    {
        return $this->event_id;
    }

    function aggregate_id()
    {
        return $this->aggregate_id;
    }

    function command_id()
    {
        return $this->command_id;
    }

    function payload()
    {
        return $this->payload;
    }

    function schema()
    {
        return $this->schema;
    }

    function occured_at()
    {
        return $this->occured_at;
    }


}
