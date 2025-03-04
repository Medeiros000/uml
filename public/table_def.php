<?php
require_once __DIR__ . '/../vendor/autoload.php';

$database = $conn->getDatabase();
$host     = $conn->getHost();
$user     = $conn->getUsername();
$pass     = $conn->getPassword();

$connection = pg_connect("host=$host dbname=$database user=$user password=$pass");
if (!$connection) {
  echo "An error occurred.\n";
  exit;
}

$sql = file_get_contents(__DIR__ . '/../sql/tabledef.sql');
$r   = pg_query($connection, $sql);
if (!$r) {
  echo "An error occurred.\n";
} else {
  echo $html->h_o_container('m-auto mt-3 mb-3');
  echo $html->h_span('Table definition now was in PostgreSQl', 'fs-4 text-center m-1');
  echo $html->h_c_container();
}
