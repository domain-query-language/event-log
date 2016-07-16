<?php namespace EventSourced\EventLog\Adapter\PDO\SQLite\Migration;

use EventSourced\EventLog\Adapter\PDO\AbstractMigration;

class CreateEventStreamLockerTable extends AbstractMigration
{
    protected function up_statement()
    {
        return "
            CREATE TABLE `event_stream_lock` (
            `stream_id` TEXT UNIQUE,
            `datetime` TEXT
          );        
        ";
    } 
    
    protected function down_statement()
    {
        return "DROP TABLE event_stream_lock;";
    }
}

