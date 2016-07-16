<?php namespace EventSourced\EventLog\Adapter\PDO\SQLite\Migration;

use EventSourced\EventLog\Adapter\PDO\AbstractMigration;

class CreateEventLogTable extends AbstractMigration
{
    protected function up_statement()
    {
        return "
            CREATE TABLE `event_log` (
            `event_id` TEXT NOT NULL,
            `command_id` TEXT NOT NULL,
            `aggregate_id` TEXT NOT NULL,
            `schema_event_id` TEXT NOT NULL,
            `schema_aggregate_id` TEXT NOT NULL,
            `occured_at` TEXT NOT NULL,
            `order` int unsigned AUTO_INCREMENT,
            `payload` mediumtext NOT NULL,
            PRIMARY KEY (`order`)
          );        
        ";
    } 
    
    protected function down_statement()
    {
        return "DROP TABLE event_log;";
    }
}

