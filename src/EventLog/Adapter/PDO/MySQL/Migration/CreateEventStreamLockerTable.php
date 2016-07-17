<?php namespace EventSourced\EventLog\Adapter\PDO\MySQL\Migration;

use EventSourced\EventLog\Adapter\PDO\AbstractMigration;

class CreateEventStreamLockerTable extends AbstractMigration
{
    protected function up_statement()
    {
        return "
          CREATE TABLE `event_stream_lock` (
            `stream_id` binary(32) NOT NULL DEFAULT '00000000000000000000000000000000' UNIQUE,
            `datetime` datetime(6) NOT NULL
          );        
        ";
    } 
    
    protected function down_statement()
    {
        return "DROP TABLE event_stream_lock;";
    }
}

