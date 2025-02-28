<?php
if (!isset($_POST['page']) || !isset($html)) {
  echo "You are doing it wrong!";
  exit;
}

echo $html->h_o_container('m-auto mt-3 mb-3');
echo $html->h_span('UML Option', 'fs-4 text-center');
echo $html->h_c_container();

echo $html->post();
$query_schemas = "SELECT schema_name FROM information_schema.schemata WHERE schema_name NOT LIKE 'pg_%' AND schema_name != 'information_schema' ORDER BY schema_name";
$stmt          = $db->prepare($query_schemas);
$stmt->execute();
$schemas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo $html->h_o_container('m-auto mt-3');
?>
<form class="d-flex column" id="form2" method="post" action="./">
  <input type="hidden" name="page" value="schema">

  <select name="subpage" id="subpage" class="form-select form-select-sm" style="min-width: 180px;">
    <?php
    echo '<option checked>Choose a schema</option>';
    foreach ($schemas as $schema) {
      echo "<option value='{$schema['schema_name']}'>{$schema['schema_name']}</option>";
    }
    ?>
  </select>
  <button class='btn btn-dark ms-2' type="submit">Enviar</button>
</form>
<?php

echo $html->h_c_container();

if (isset($_POST['subpage'])) {
  echo 'schema';
  $schema = $_POST['subpage'] ?? '';
  echo $schema;

  if ($schema == '') {
    echo 'Schema não encontrado ou não informado';
    exit;
  }

  $p_csv     = fopen("files/$schema_partitions.csv", 'r');
  $particoes = fgetcsv($p_csv);
  fclose($p_csv);
  $l_part                = [];
  $l_part[$particoes[1]] = $particoes[2];
  $l_part[$particoes[0]] = $particoes[1];

  $t_csv = fopen("files/$schema_tabelas.csv", 'r');
  while ($tabelas = fgetcsv($t_csv)) {
    $l_tab[$tabelas[1]] = explode(';', $tabelas[2]);
  }
  fclose($t_csv);

  echo $html->h_o_container();
  foreach ($l_tab as $key => $value) {
    echo $html->h_card_t($key, $value, $l_part);
  }
  echo $html->h_c_container();
}
?>