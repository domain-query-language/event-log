<?php namespace EventSourced\EventLog\Adapter\PDO\MySQL\Migration;

use EventSourced\EventLog\Adapter\PDO\AbstractMigration;

class CreateEventLogTable extends AbstractMigration
{
    protected function up_statement()
    {        
        return "
          CREATE TABLE `event_log` (
            `event_id` binary(16) NOT NULL DEFAULT '0000000000000000',
            `command_id` binary(16) NOT NULL DEFAULT '0000000000000000',
            `aggregate_id` binary(16) NOT NULL DEFAULT '0000000000000000',
            `schema_event_id` binary(16) NOT NULL DEFAULT '0000000000000000',
            `schema_aggregate_id` binary(16) NOT NULL DEFAULT '0000000000000000',
            `occured_at` datetime(6) NOT NULL,
            `order` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
            `payload` mediumtext COLLATE utf8_unicode_ci NOT NULL,
            PRIMARY KEY (`order`),
            KEY `id` (`event_id`),
            KEY `aggregate_id` (`aggregate_id`,`schema_aggregate_id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=364 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;        
          ";
    } 
    
    protected function down_statement()
    {
        return "DROP TABLE event_log;";
    }
}

