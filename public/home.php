<?php 
echo $html->h_o_container('m-auto mt-3 mb-3');
echo $html->h_span('Table Definition', 'fs-4 text-center');
echo $html->h_c_container();
$h = "Hello, inside the root of the project there is a sql folder that contains an important file that must be run in the database. 
This file is called tabledef.sql. It is responsible for mounting something like a show create table in the database PostgreSQL.";
echo $html->h_o_container('m-auto mt-3 p-2');
echo $html->h_span($h, 'fs-6');
echo $html->h_c_container();
?>