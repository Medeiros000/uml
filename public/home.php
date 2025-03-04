<?php
require_once __DIR__ . '/../vendor/autoload.php';

echo h_o_container('m-auto mt-3 mb-3');
echo h_span('Table Definition', 'fs-4 text-center');
echo h_c_container();
$h = 'Hello, inside the root of the project there is a sql folder that contains an important file that must be run in the database. 
This file is called tabledef.sql. It is responsible for mounting something like a show create table in the database PostgreSQL.';
echo h_o_container('m-auto mt-3 p-2');
echo h_span($h, 'fs-6');
echo h_c_container();
?>