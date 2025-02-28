<?php
// Conexão com o banco de dados
$conn = pg_connect('host=localhost dbname=postgres user=postgres password=root');

if (!$conn) {
  die('Erro na conexão com o banco de dados');
}

// Exemplo de uma query com um array de inteiros
$query = "SELECT * FROM public.ceara WHERE cod_distrito = ANY('{1,2,3}'::int[])";

// Executando a query
$result = pg_query($conn, $query);

if (!$result) {
  die('Erro na execução da query: ' . pg_last_error($conn));
}

// Processando os resultados
while ($row = pg_fetch_assoc($result)) {
  echo $row['coluna'] . "\n";
}

// Fechando a conexão
pg_close($conn);
?>
