<?php namespace EventSourced\EventLog\Adapter\PDO\MySQL;

use EventSourced\EventLog\StreamID;
use EventSourced\EventLog\DateTimeGenerator;
use DateTime;

class EventStreamLocker implements \EventSourced\EventLog\EventStreamLocker
{
    private $pdo;
    private $datetime_generator;
    private $id_transformer;
    
    public function __construct(\PDO $pdo, DateTimeGenerator $datetime_generator)
    {
        $this->pdo = $pdo;
        $this->datetime_generator = $datetime_generator;
        $this->id_transformer = new UuidToBinaryTransformer();
    }
    
    public function lock(StreamID $stream_id)
    {
        $now = $this->datetime_generator->generate();
        
        $key = $this->encode_stream_id($stream_id);

        $insert = "
            INSERT INTO event_stream_lock
                (stream_id, datetime)
            VALUES
                (?, ?)";
        
        try {
            $this->pdo->prepare($insert)->execute([$key, $now]);
        } catch (\PDOException $ex) {
            if ($this->is_timed_out($key, $now)) {
               $this->update_lock($key); 
            } else {
                throw new \EventSourced\EventLog\EventStreamLockerException();
            }        
        }
    }
    
    private function encode_stream_id(StreamID $stream_id)
    {
        return $this->id_transformer->to_binary($stream_id->domain_id)
            .$this->id_transformer->to_binary($stream_id->schema_id);
    }
    
    protected function is_timed_out($key, $now)
    {
        $query = "SELECT * FROM event_stream_lock WHERE stream_id = ?";
        
        $statement = $this->pdo->prepare($query);
        $statement->execute([$key]);
        
        $row = $statement->fetchObject();
        
        $datetime = new DateTime($row->datetime);
        
        $diff = $this->diff_in_seconds(new DateTime($now), $datetime);
        if ($diff < 0.5) {
            return false;
        }
        return true;
    }
    
    private function diff_in_seconds(DateTime $date1, DateTime $date2)
    {
        $diff = abs(strtotime($date1->format('d-m-Y H:i:s.u'))-strtotime($date2->format('d-m-Y H:i:s.u')));

        $micro1 = $date1->format("u");
        $micro2 = $date2->format("u");

        $micro = abs($micro1 - $micro2);

        $difference = $diff.".".$micro;

        return (float)$difference;
    }
    
    protected function update_lock($key) 
    {
        $update = "
            UPDATE event_stream_lock
               SET datetime = ?
            WHERE 
                stream_id = ?";
        
        $statement = $this->pdo->prepare($update);
        
        $now = $this->datetime_generator->generate();
        $statement->execute([$now, $key]);
    }
    
    public function unlock(StreamID $stream_id)
    {
        $key = $this->encode_stream_id($stream_id);
        
        $delete = "DELETE FROM event_stream_lock WHERE stream_id =?";

        $this->pdo->prepare($delete)->execute([$key]);
    }
}