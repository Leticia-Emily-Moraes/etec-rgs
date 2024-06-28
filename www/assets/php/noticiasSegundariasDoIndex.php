<?php

session_start();

include_once('../bd/conexao.php');

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
    LIMIT 2 OFFSET 3";

$stmt = mysqli_prepare($conexao, $query);
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
