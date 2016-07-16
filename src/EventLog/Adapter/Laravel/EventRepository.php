<?php namespace EventSourced\EventLog\Adapter\Laravel;

use EventSourced\EventLog\EventBuilder;
use EventSourced\EventLog\PDO;
use DB;

class EventRepository extends PDO\EventRepository
{
    public function __construct(EventBuilder $event_builder)
    {
        parent::__construct(DB::connection()->getPdo(), $event_builder);
    }
}