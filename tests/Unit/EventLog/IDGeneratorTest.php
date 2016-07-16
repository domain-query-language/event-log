<?php namespace Tests\Unit\EventLog;

class IDGeneratorTest extends \Tests\TestCase
{    
    public function test_generates_uuid()
    {
        $id = (new \EventSourced\EventLog\IDGenerator())->generate();
        
        $this->assertRegExp("/([a-f\\d]{8}(-[a-f\\d]{4}){3}-[a-f\\d]{12}?)/i", $id);
    }
}