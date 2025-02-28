<?php
namespace App;

use Dotenv\Dotenv;
use PDO;
use PDOException;

class Connection
{
  private $host;
  private $port;
  private $database;
  private $username;
  private $password;
  private $connection;

  public function __construct($alt_database = null)
  {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();

    $this->host     = $_ENV['DB_HOST'];
    $this->port     = $_ENV['DB_PORT'];
    $this->database = $alt_database != null ? $alt_database : $_ENV['DB_DATABASE'];
    $this->username = $_ENV['DB_USERNAME'];
    $this->password = $_ENV['DB_PASSWORD'];

    $this->connect();
  }

  private function connect()
  {
    $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->database};";
    try {
      $this->connection = new PDO($dsn, $this->username, $this->password);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo 'Connection error.' . $e->getMessage();
    }
  }

  public function getConnection()
  {
    return $this->connection;
  }

  public function getDatabase()
  {
    return $this->database;
  }

  public function getHost()
  {
    return $this->host;
  }

  public function getPort()
  {
    return $this->port;
  }

  public function getUsername()
  {
    return $this->username;
  }

  public function getPassword()
  {
    return $this->password;
  }
}
