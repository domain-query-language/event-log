<?php namespace EventSourced\EventLog\Adapter\Laravel\MySQL;

use EventSourced\EventLog\DateTimeGenerator;
use EventSourced\EventLog\Adapter\PDO\MySQL;
use DB;

class EventStreamLocker extends MySQL\EventStreamLocker
{
    public function __construct(DateTimeGenerator $datetime_generator)
    {
        parent::__construct(DB::connection()->getPdo(), $datetime_generator);
    }
}