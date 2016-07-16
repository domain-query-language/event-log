<?php namespace EventSourced\EventLog;

interface EventStreamLocker
{
    public function lock(StreamID $id);
    
    public function unlock(StreamID $id);
    
}
