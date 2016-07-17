<?php namespace EventSourced\EventLog\Adapter\PDO\MySQL;

class UuidToBinaryTransformer 
{
    public function from_binary($binary_id)
    {
        $hex = bin2hex($binary_id);
        return implode("-", [
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12),
        ]);
    }
    
    public function to_binary($uuid)
    {
        $hex = str_replace("-", "", $uuid);
        return hex2bin($hex);
    }
}