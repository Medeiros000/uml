<?php
require_once __DIR__ . '/../vendor/autoload.php';

$database = $conn->getDatabase();

if (!isset($_POST['page'])) {
  echo 'You are doing it wrong!';
  exit;
}

echo h_o_container('m-auto mt-3 mb-3');
echo h_span('Database Option', 'fs-4 text-center');
echo h_c_container();

$query = 'SELECT datname FROM pg_database WHERE datistemplate = false ORDER BY datname';
$stmt  = $db->prepare($query);
$stmt->execute();
$dbs = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo h_o_container('m-auto mt-3');
?>
<form class="d-flex column" id="form2" method="post" action="./">
  <input type="hidden" name="page" value="database">

  <select name="database" id="database" class="form-select form-select-sm" style="min-width: 180px;">
    <?php
    foreach ($dbs as $key => $value) {
      $db = $value['datname'];
      echo "<option value='$db' " . ($database == $db ? 'selected disabled' : '') . ">$db</option>";
    }
    ?>
  </select>
  <button class='btn btn-dark ms-2' type="submit">Enviar</button>
</form>
<?php

echo h_c_container();
