<?php
require_once __DIR__ . '/../vendor/autoload.php';

echo h_o_container('text-center');
echo button('uml_option', 'UML');
echo button('csv_option', 'CSV');
echo button('database_option', 'Database');
echo h_c_container();
