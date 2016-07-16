<?php namespace EventSourced\EventLog\Adapter\PDO;

use PDO;

abstract class AbstractMigration
{
    private $pdo;
    
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    abstract protected function up_statement();
    
    public function up()
    {
        $statement = $this->up_statement();
        $this->run_statement($statement);
    }
    
    abstract protected function down_statement();
    
    public function down()
    {
        $statement = $this->down_statement();
        $this->run_statement($statement);
    }
    
    private function run_statement($statement)
    {
        $this->pdo->prepare($statement)->execute();
    }
}

