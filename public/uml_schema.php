<?php
$schema   = $_POST['subpage'] ?? '';
$database = $conn->getDatabase();

if ($schema == '') {
  echo 'Schema not found or not informed';
  exit;
} else {
  echo $html->h_o_container('m-auto mt-3 mb-3');
  echo $html->h_span('Schema: ', 'fs-4 text-center m-1');
  echo $html->h_span($schema, 'fs-4 text-center m-1 text-danger');
  echo $html->h_c_container();
}

$tables_file     = "files/$database/tables/{$schema}_t.csv";
$partitions_file = "files/$database/partitions/{$schema}_p.csv";

if (!file_exists($tables_file) || !file_exists($partitions_file)) {
  echo '<h3 class="text-center m-3">Files not found</h3>';
  exit;
} else {
  $p_csv = fopen($partitions_file, 'r');
  $t_csv = fopen($tables_file, 'r');

  $partitions = fgetcsv($p_csv);
  $tables     = fgetcsv($t_csv);

  if (!$partitions && !$tables) {
    echo $html->h_o_container('m-auto mt-3 mb-3');
    echo $html->h_span('No tables and/or the file is empty.', 'fs-4 text-center m-1');
    echo $html->h_c_container();
    fclose($p_csv);
    fclose($t_csv);
    exit;
  }

  fclose($p_csv);
  fclose($t_csv);

  $p_csv      = fopen($partitions_file, 'r');
  $partitions = fgetcsv($p_csv);
  fclose($p_csv);

  if ($partitions) {
    $l_part                 = [];
    $l_part[$partitions[1]] = $partitions[2];
    $l_part[$partitions[0]] = $partitions[1];
  } else {
    $l_part = [];
  }

  $t_csv = fopen($tables_file, 'r');
  while ($tables = fgetcsv($t_csv)) {
    $l_tab[$tables[1]] = explode(';', $tables[2]);
    $l_indexes[$tables[1]] = explode(';', $tables[3]);
  }
  fclose($t_csv);
  ksort($l_tab);

  echo $html->h_o_container();
  foreach ($l_tab as $key => $value) {
    $head = array_key_exists($key, $l_part) ? "{$key} {$l_part[$key]} partitions" : $key;
    echo $html->h_card_t($head, $value, $l_indexes[$key]);
  }
  echo $html->h_c_container();
}
?>