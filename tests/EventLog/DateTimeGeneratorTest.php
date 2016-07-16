<?php namespace Tests\EventLog;

class DateTimeGeneratorTest extends \Tests\TestCase
{    
    public function test_generates_datetime_with_microtime()
    {
        $datetime = (new \EventSourced\EventLog\DateTimeGenerator())->generate();
        $this->assertTrue(strtotime($datetime) !== false);
        $this->assertContains(".", $datetime);
    }
}