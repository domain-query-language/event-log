<?php namespace EventSourced\EventLog;

use Ramsey\Uuid\Uuid;

class IDGenerator
{
    public function generate()
    {
        return Uuid::uuid4()->toString();
    }
}


