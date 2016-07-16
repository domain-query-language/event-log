<?php namespace EventSourced\EventLog;

interface EventRepository
{
    public function fetch(StreamID $stream_id, $offset, $limit);
    
    public function fetch_all($offset, $limit);
    
    public function store(array $events);
}
