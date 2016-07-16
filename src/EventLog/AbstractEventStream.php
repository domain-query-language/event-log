<?php namespace EventSourced\EventLog;

abstract class AbstractEventStream implements \Iterator
{
    protected $event_repo;
    
    private $chunk_size = 100;
    private $streamed_count = 0;
        
    private $events;
       
    public function __construct(
        EventRepository $event_repo
    ){
        $this->event_repo = $event_repo;
        
        $this->reset();
        $this->fetch();
    }
    
    protected function reset()
    {
        $this->streamed_count = 0;
        $this->events = [];
    }

    protected function fetch()
    {
        $this->events = $this->get_next_chunk($this->streamed_count, $this->chunk_size);
    }
        
    abstract protected function get_next_chunk($offset, $limit);
    
    protected function has_more_chunks()
    {
        return (count($this->events) == $this->chunk_size);
    }
    
    public function current()
    {
        return current($this->events);
    }
    
    public function next()
    {
        $event = next($this->events);
        $this->streamed_count++;
        if(! $event && $this->has_more_chunks()) {
            $this->fetch();
        }
        
        return $event;
    }
    
    public function key()
    {
        return key($this->events);
    }
    
    public function valid()
    {
        return current($this->events) !== false;
    }
    
    public function rewind()
    {
     
    }
}