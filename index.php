<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';

use App\Connection;
use App\Html;

$html     = new Html();
$conn     = new Connection($_SESSION['database'] ?? null);
$db       = $conn->getConnection();
$database = $conn->getDatabase();

echo $html->h_doctype();
echo $html->h_head('UML in html');
echo $html->h_o_body('overflow-scroll');

echo $html->h_o_container('text-center w-100 mt-5 mb-2');
$title = $html->h_h1('CSV to UML', 'fw-bolder');
echo "<a class='text-decoration-none text-dark' href='/uml' title='Home'>$title</a>";
echo $html->post();
echo $html->h_c_container();

require 'public/buttons.php';
echo $html->h_o_container('text-center w-100 mt-1');
echo $html->h_span('Database: ' . $html->h_span($database, 'text-danger'), 'fw-bolder fs-6');
echo $html->h_c_container();

if (!isset($_POST['page'])) {
  $_POST['page'] = '';
}

match ($_POST['page']) {
  'uml'             => require 'public/uml.php',
  'uml_option'      => require 'public/uml_option.php',
  'schema'          => require 'public/uml_schema.php',
  'csv_option'      => require 'public/csv_option.php',
  'csv'             => require 'public/csv_schema.php',
  'database'        => require 'public/database.php',
  'database_option' => require 'public/database_option.php',
  'tabledef'        => require 'public/table_def.php',
  '403'             => require 'public/error.php',
  '404'             => require 'public/error.php',
  '500'             => require 'public/error.php',
  default           => require 'public/home.php',
};
unset($_POST['page']);

echo $html->h_c_body();

echo $html->h_c_html();
