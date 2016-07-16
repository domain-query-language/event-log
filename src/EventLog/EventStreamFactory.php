<?php namespace EventSourced\EventLog;

class EventStreamFactory
{
    private $event_repo;
    
    public function __construct(EventRepository $event_repo)
    {
        $this->event_repo = $event_repo;
    }
    
    public function aggregate_id(StreamID $aggregate_id)
    {
        return new AggregateEventStream($this->event_repo, $aggregate_id);
    }
    
    public function all()
    {
        return new FullEventStream($this->event_repo);
    }
}


