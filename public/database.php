<?php

$database = '';
if (isset($_POST['database']) && $_POST['database'] != $conn->getDatabase()) {
  if (empty($_POST['database'])) {
    echo 'Database not selected';
    exit;
  }
  $database = $_POST['database'];
  echo 'Database selecionado: ' . $database;
}

$_SESSION['database'] = $database;

header('Location: /uml');
?>