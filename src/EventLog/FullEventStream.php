<?php namespace EventSourced\EventLog;

class FullEventStream extends AbstractEventStream
{
    protected function get_next_chunk($offset, $limit)
    {
        return $this->event_repo->fetch_all($offset, $limit);
    }
}