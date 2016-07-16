<?php namespace EventSourced\EventLog\Adapter\Laravel;

use EventSourced\EventLog\DateTimeGenerator;
use EventSourced\EventLog\PDO;
use DB;

class EventStreamLocker extends PDO\EventStreamLocker
{
    public function __construct(DateTimeGenerator $datetime_generator)
    {
        parent::__construct(DB::connection()->getPdo(), $datetime_generator);
    }
}