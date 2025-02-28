<?php

if (!isset($_POST['page']) || !isset($html)) {
  echo 'You are doing it wrong!';
  exit;
}
$schema   = $_POST['subpage'] ?? '';
// $database = $_SESSION['database'] ?? 'postgres';
$database = $conn->getDatabase();

if (!file_exists('files')) {
  @mkdir('files');
}

if (!file_exists($database)) {
  @mkdir("files/$database");
}

if (!file_exists("files/$database/tables")) {
  @mkdir("files/$database/tables");
}

if (!file_exists("files/$database/partitions")) {
  @mkdir("files/$database/partitions");
}

//  create .htaccess if not exists
if (!file_exists('files/.htaccess')) {
  $htaccess = fopen('files/.htaccess', 'w');
  fwrite($htaccess, 'deny from all');
  fclose($htaccess);
  if (!file_exists("files/$database/partitions")) {
    mkdir("files/$database/partitions");
  }
  if (!file_exists("files/$database/tables")) {
    mkdir("files/$database/tables");
  }
}

$tables_file     = "files/$database/tables/{$schema}_t.csv";
$partitions_file = "files/$database/partitions/{$schema}_p.csv";

if ($schema == '') {
  echo 'Schema not found or not informed';
  exit;
} else {
  echo $html->h_o_container('m-auto mt-3 mb-3');
  echo $html->h_span('Schema: ', 'fs-4 text-center m-1');
  echo $html->h_span($schema, 'fs-4 text-center m-1 text-danger');
  echo $html->h_c_container();
}

$query = "SELECT table_name
  FROM information_schema.tables
  WHERE table_schema ilike '$schema'
  AND table_type = 'BASE TABLE';";

$stmt = $db->prepare($query);
$stmt->execute();
$tables = $stmt->fetchAll(PDO::FETCH_ASSOC);

$padrao    = '/PARTITION OF/';
$reg_table = [];

if (count($tables) == 0) {
  echo $html->h_o_container('m-auto mt-3 mb-3');
  echo $html->h_span('Tables not found', 'fs-4 text-center m-1');
  echo $html->h_c_container();

  $t = fopen($tables_file, 'w');
  fclose($t);
  $p = fopen($partitions_file, 'w');
  fclose($p);
  exit;
}

if (file_exists($tables_file)) {
  $t         = fopen($tables_file, 'r');
  // Count the number of tables in the csv's files
  $n_tab_csv = 0;

  while (($line = fgetcsv($t)) !== false) {
    if (!empty(array_filter($line))) {
      $n_tab_csv++;
    }
  }
  fclose($t);
  if (file_exists($partitions_file)) {
    $p = fopen($partitions_file, 'r');
    while (($line = fgetcsv($p)) !== false) {
      if (!empty(array_filter($line))) {
        $n_tab_csv += $line[2];
      }
    }
    fclose($p);
  }

  if ($n_tab_csv == count($tables)) {
    echo $html->h_o_container('m-auto mt-3 mb-3');
    echo $html->h_span('Tables already saved', 'fs-4 text-center m-1');
    echo $html->h_c_container();
    exit;
  } else {
    $t = fopen($tables_file, 'w');
    fclose($t);
    $p = fopen($partitions_file, 'w');
    fclose($p);
  }
}

foreach ($tables as $table) {
  $t = $table['table_name'];

  $query = "SELECT pg_get_tabledef('$schema', '$t', False) AS create_table;";
  $stmt  = $db->prepare($query);
  try {
    $stmt->execute();
  } catch (PDOException $e) {
    $erro_function = '/pg_get_tabledef/';
    $erro          = 'Database error. Please contact the administrator.';

    if (preg_match($erro_function, $e->getMessage())) {
      $link = $html->h_a_link('tabledef', 'table_def');
      $erro = "Function pg_get_$link not found. Please contact the administrator.";
    }

    echo $html->h_o_container('m-auto mt-3 mb-3');
    echo $html->h_span($erro, 'fs-4 text-center m-1');
    echo $html->h_c_container();
    exit;
  }

  $create = $stmt->fetch(PDO::FETCH_ASSOC)['create_table'];

  if (preg_match($padrao, $create)) {
    $t                          = explode("{$schema}.", $create);
    $child_table                = rtrim($t[1], ' PARTITION OF');
    $parent_table               = explode(' ', $t[2])[0];
    $reg_table[$parent_table][] = $child_table;
  } else {
    echo $t . '<br>';
    $colunas       = explode('(', $create);
    $colunas       = explode(')', $colunas[1])[0];
    $colunas       = explode(',', $colunas);
    $total_colunas = '';
    foreach ($colunas as $coluna) {
      $coluna         = explode(' ', trim($coluna));
      $total_colunas .= $coluna[0] . ':' . $coluna[1] . ';';
    }
    $file_tables   = fopen($tables_file, 'a');
    $total_colunas = rtrim($total_colunas, ';');
    fwrite($file_tables, "$schema,$t,$total_colunas" . PHP_EOL);

    $reg_table[$t] = [];
    fclose($file_tables);
  }
}

$partitions = fopen($partitions_file, 'a');
$contador   = 1;
foreach ($reg_table as $key => $value) {
  $n_partitions = count($value);
  if ($n_partitions > 0) {
    fwrite($partitions, "$schema,$key,$n_partitions" . PHP_EOL);
  }
}
fclose($partitions);
$html->h_span('END', 'fs-4 text-center m-1');
?>