<?php namespace EventSourced\EventLog\Adapter\PDO\MySQL;

use PDO;
use EventSourced\EventLog\Event;
use EventSourced\EventLog\EventBuilder;
use EventSourced\EventLog\StreamID;

class EventRepository implements \EventSourced\EventLog\EventRepository
{
    private $pdo;
    private $stream_select_statement;
    private $all_select_statement;
    private $id_transformer;
    
    public function __construct(PDO $pdo, EventBuilder $event_builder)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
        $this->event_builder = $event_builder;
        $this->id_transformer = new UuidToBinaryTransformer();
        
        $stream_query = "
            SELECT 
                *
            FROM
                event_log
            WHERE
                aggregate_id = ?
                AND schema_aggregate_id = ?
            ORDER BY 
                `order`
            LIMIT ?, ?
            ;
         ";
          
        $this->stream_select_statement = $this->pdo->prepare($stream_query);
        
        $all_query = "
            SELECT 
                *
            FROM
                event_log
            ORDER BY 
                `order`
            LIMIT ?, ?
            ;
         ";
        
        $this->all_select_statement = $this->pdo->prepare($all_query);
    }
    
    public function fetch(StreamID $aggregate_id, $offset, $limit)
    {
        $data = [
            $this->uuid_to_binary($aggregate_id->domain_id), 
            $this->uuid_to_binary($aggregate_id->schema_id), 
            (int)$offset,
            (int)$limit
        ];
        
        $this->stream_select_statement->execute($data);
        $rows = $this->stream_select_statement->fetchAll(\PDO::FETCH_OBJ);

        return array_map(function($event_row){
            return $this->transform_row_to_event($event_row);
        }, $rows);
    }
    
    public function uuid_to_binary($uuid)
    {
        return $this->id_transformer->to_binary($uuid);
    }
    
    public function binary_to_uuid($binary_id)
    {
        return $this->id_transformer->from_binary($binary_id);
    }
    
    public function fetch_all($offset, $limit)
    {
        $data = [(int)$offset, (int)$limit];
        
        $this->all_select_statement->execute($data);
        $rows = $this->all_select_statement->fetchAll(\PDO::FETCH_OBJ);
        
        return array_map(function($event_row){
            return $this->transform_row_to_event($event_row);
        }, $rows);
    }
    
    private function transform_row_to_event($event_row)
    {
        $this->event_builder->set_event_id( $this->binary_to_uuid($event_row->event_id) )
            ->set_occured_at($event_row->occured_at)
            ->set_schema_event_id( $this->binary_to_uuid($event_row->schema_event_id) )
            ->set_schema_aggregate_id( $this->binary_to_uuid($event_row->schema_aggregate_id) )
            ->set_aggregate_id( $this->binary_to_uuid($event_row->aggregate_id) )
            ->set_command_id( $this->binary_to_uuid($event_row->command_id) )
            ->set_payload(json_decode($event_row->payload));
            
        return $this->event_builder->build();
    }
        
    public function store(array $events)
    {
        if (count($events) == 0) {
            return;
        }
        $insert = "
            INSERT INTO event_log
                (event_id, command_id, aggregate_id, schema_event_id, schema_aggregate_id, occured_at, payload)
            VALUES";
        
        $values = [];
        $data = [];
        foreach ($events as $event) {
            $values[] = "(?, ?, ?, ?, ?, ?, ?)";
            $data = array_merge($data, $this->tranform_event_to_row($event));
        }
           
        $insert .= implode(",", $values);
                
        $this->pdo->prepare($insert)->execute($data);
    }
    
    private function tranform_event_to_row(Event $event)
    {
        return [
            $this->uuid_to_binary($event->event_id),
            $this->uuid_to_binary($event->command_id),
            $this->uuid_to_binary($event->aggregate_id),
            $this->uuid_to_binary($event->schema->event_id),
            $this->uuid_to_binary($event->schema->aggregate_id),
            $event->occured_at,
            json_encode($event->payload)
        ];
    }
}