<?php namespace Tests;

use Mockery;

class TestCase extends \PHPUnit_Framework_TestCase
{ 
    protected function tearDown()
    {
        if (class_exists('Mockery')) {
            Mockery::close();
        }
    }
    
    protected function dummy(string $classOrInterface)
    {
        return $this->prophesize($classOrInterface)->reveal();
    }
    
    protected function stub(string $classOrInterface)
    {
        return $this->prophesize($classOrInterface);
    }
     
    protected function mock(string $classOrInterface)
    {
        return $this->prophesize($classOrInterface);
    }
}
