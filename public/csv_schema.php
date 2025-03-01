<?php
require_once './vendor/autoload.php';

$padrao_parenteses = '/\((.*?)\)/';
$padrao_varchar    = '/character varying/';
$variavel          = ['character', 'double', 'timestamp', 'time', 'interval'];

if (!isset($_POST['page']) || !isset($html)) {
  echo 'You are doing it wrong!';
  exit;
}

$schema   = $_POST['subpage'] ?? '';
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
  $table = $table['table_name'];

  $query = "SELECT pg_get_tabledef('$schema', '$table', False) AS create_table;";
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

  $create_table_index = $stmt->fetch(PDO::FETCH_ASSOC)['create_table'];
  $create_table_index = preg_replace('/\n\)/', ' ', $create_table_index);
  $create_table_index = explode(';', $create_table_index);
  $create_list        = $create_table_index[0];
  unset($create_table_index[0]);

  if (preg_match('/PARTITION OF/', $create_list)) {
    $t                          = explode("{$schema}.", $create_list);
    $child_table                = rtrim($t[1], ' PARTITION OF');
    $parent_table               = explode(' ', $t[2])[0];
    $reg_table[$parent_table][] = $child_table;
  } else {
    $create_list = explode("{$schema}.{$table} (", $create_list);
    unset($create_list[0]);
    $create_list = explode(',', $create_list[1]);
    $create_list = array_map('trim', $create_list);
    $create_list = preg_replace('/\r/', ' ', $create_list);

    $str_columns = '';
    foreach ($create_list as $key => $value) {
      $v_list = explode(' ', $value);
      if (preg_match($padrao_varchar, $value)) {
        $v_list    = explode(' ', $value);
        $v_list[2] = preg_replace('/varying/', 'varchar', $v_list[2]);

        $str_columns .= "{$v_list[0]}:{$v_list[2]};";
      } else if (preg_match('/CONSTRAINT/', $value)) {
        $v_list          = explode(' ', $value);
        $column_key      = preg_grep($padrao_parenteses, $v_list);
        $keys_constraint = array_slice($column_key, 0)[0];

        if (preg_match('/PRIMARY/', $value)) {
          $str_columns .= "PRIMARY KEY:$keys_constraint;";
        } else if (preg_match('/UNIQUE/', $value)) {
          $str_columns .= "UNIQUE:$keys_constraint;";
        } else if (preg_match('/FOREIGN/', $value)) {
          $str_columns .= "FOREIGN KEY:$keys_constraint;";
        }
      } else if (preg_match('/\n\)/', $v_list[0])) {
        continue;
      } else {
        $str_columns .= "{$v_list[0]}:{$v_list[1]};";
      }
    }

    $str_indexes = '';
    if (!empty(trim($create_table_index[1]))) {
      foreach ($create_table_index as $value) {
        if (empty(trim($value))) {
          continue;
        }
        $value        = explode(' USING ', $value);
        $value        = explode(' ', $value[1]);
        $index_type   = $value[0];
        $index_values = $value[1];

        $str_indexes .= "$index_type:$index_values;";
      }
      $str_indexes = rtrim($str_indexes, ';');
      // $str_columns .= $str_indexes;
    }

    $file_tables = fopen($tables_file, 'a');
    $str_columns = rtrim($str_columns, ';');
    fwrite($file_tables, "$schema,$table,$str_columns,$str_indexes" . PHP_EOL);
    fclose($file_tables);
  }
}

$partitions = fopen($partitions_file, 'a');
foreach ($reg_table as $key => $value) {
  $n_partitions = count($value);
  if ($n_partitions > 0) {
    fwrite($partitions, "$schema,$key,$n_partitions" . PHP_EOL);
  }
}
fclose($partitions);

echo $html->h_o_container('m-auto mt-3 mb-3');
echo $html->h_span('END', 'fs-4 text-center m-1 text-danger');
echo $html->h_c_container();
?>