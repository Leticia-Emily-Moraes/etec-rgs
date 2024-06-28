<?php

session_start();

include_once('../bd/conexao.php');

// Número de notícias por página
$noticias_por_pagina = 4;

// Obtém o número da página a partir da solicitação GET
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $noticias_por_pagina;

$query = "SELECT n.IdNoticia,
    n.Titulo, 
    n.Resumo, 
    n.Categoria,
    n.ImagemCapa,
    DATE_FORMAT(n.HoraPublicado, '%d/%m/%Y') AS DataPublicacao,
    u.NomeCompleto AS Autor
    FROM noticias n
    JOIN usuarios u ON n.Autor = u.IdUsuario
    ORDER BY n.HoraPublicado DESC
    LIMIT ? OFFSET ?";

$stmt = mysqli_prepare($conexao, $query);
mysqli_stmt_bind_param($stmt, 'ii', $noticias_por_pagina, $offset);
mysqli_stmt_execute($stmt);

if ($stmt) {
    $ultimasNoticias = array();
    mysqli_stmt_bind_result($stmt, $ID, $titulo, $resumo, $categoria, $imagemCapa, $dataPublicacao, $autor);
    while (mysqli_stmt_fetch($stmt)) {
        $ultimasNoticias[] = array(
            'ID' => $ID,
            'Titulo' => $titulo,
            'Resumo' => $resumo,
            'Categoria' => $categoria,
            'ImagemCapa' => $imagemCapa,
            'DataPublicacao' => $dataPublicacao,
            'Autor' => $autor
        );
    }
    mysqli_stmt_close($stmt);
} else {
    $ultimasNoticias = array();
}

mysqli_close($conexao);

header('Content-Type: application/json');
echo json_encode($ultimasNoticias);
exit();
?>
