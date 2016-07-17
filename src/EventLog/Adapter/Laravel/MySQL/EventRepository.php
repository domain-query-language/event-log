<?php namespace EventSourced\EventLog\Adapter\Laravel\MySQL;

use EventSourced\EventLog\EventBuilder;
use EventSourced\EventLog\Adapter\PDO\MySQL;
use DB;

class EventRepository extends MySQL\EventRepository
{
    public function __construct(EventBuilder $event_builder)
    {
        parent::__construct(DB::connection()->getPdo(), $event_builder);
    }
}