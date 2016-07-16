<?php namespace EventSourced\EventLog;

class AggregateEventStream extends AbstractEventStream
{
    private $aggregate_id;
       
    public function __construct(
        EventRepository $event_repo,
        StreamID $aggregate_id
    ){
        $this->aggregate_id = $aggregate_id;
        parent::__construct($event_repo);
    }
    
    protected function get_next_chunk($offset, $limit)
    {
        return $this->event_repo->fetch($this->aggregate_id, $offset, $limit);
    }
}