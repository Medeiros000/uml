<?php
require_once './vendor/autoload.php';

$database = $conn->getDatabase();
$host     = $conn->getHost();
$user     = $conn->getUsername();
$pass     = $conn->getPassword();

$connection = pg_connect("host=$host dbname=$database user=$user password=$pass");
if (!$connection) {
  echo "An error occurred.\n";
  exit;
}

$sql = file_get_contents('./sql/tabledef.sql');
$r   = pg_query($connection, $sql);
if (!$r) {
  echo "An error occurred.\n";
}
