<?php
session_start();

include_once('../bd/conexao.php');

$query = "SELECT n.Titulo, 
    n.Resumo, 
    n.Categoria,
    n.ImagemCapa,
    DATE_FORMAT(n.HoraPublicado, '%d/%m/%Y') AS DataPublicacao,
    u.NomeCompleto AS Autor
    FROM noticias n
    JOIN usuarios u ON n.Autor = u.IdUsuario
    ORDER BY n.HoraPublicado DESC
    LIMIT 2";
$stmt = mysqli_prepare($conexao, $query);
mysqli_stmt_execute($stmt);

// Verificar se a execução da consulta foi bem-sucedida
if ($stmt) {
    // Inicializar um array para armazenar os resultados
    $resultados = array();

    // Associar os resultados da consulta ao statement
    mysqli_stmt_bind_result($stmt, $titulo, $resumo, $categoria, $imagemCapa, $dataPublicacao, $autor);

    // Percorrer os resultados e alimentar o array
    while (mysqli_stmt_fetch($stmt)) {
        $resultados[] = array(
            'Titulo' => $titulo,
            'Resumo' => $resumo,
            'Categoria' => $categoria,
            'ImagemCapa' => $imagemCapa,
            'DataPublicacao' => $dataPublicacao,
            'Autor' => $autor
        );
    }

    // Fechar o statement
    mysqli_stmt_close($stmt);
} else {
    // Em caso de falha na execução da consulta
    $resultados = array();
}

// Fechar a conexão
mysqli_close($conexao);

// Enviar os resultados como JSON
echo json_encode($resultados);

?>
