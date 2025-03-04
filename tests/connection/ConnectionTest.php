<?php
namespace tests;

use App\Connection;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
  private readonly Connection $connection;

  public function setup(): void
  {
    $this->connection = new Connection();
  }

  public function testGetConnectionNotNull()
  {
    $this->assertNotNull($this->connection->getConnection());
  }

  #[Depends('testGetConnectionNotNull')]
  public function testGetConnection()
  {
    $this->assertIsObject($this->connection->getConnection());
  }

  #[Depends('testGetConnection')]
  public function testGetDatabase()
  {
    $this->assertIsString($this->connection->getDatabase());
  }
}
?>