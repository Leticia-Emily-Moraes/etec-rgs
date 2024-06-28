<?php

session_start();

include_once('../bd/conexao.php');

$query = "SELECT IdNoticia,
    Titulo, 
    Resumo,
    ImagemCapa,
    DATE_FORMAT(HoraPublicado, '%d/%m/%Y') AS DataPublicacao
    FROM noticias
    ORDER BY HoraPublicado DESC
    LIMIT 3";

$stmt = mysqli_prepare($conexao, $query);
mysqli_stmt_execute($stmt);

if ($stmt) {
    $ultimasNoticias = array();
    mysqli_stmt_bind_result($stmt, $ID, $titulo, $resumo, $imagemCapa, $dataPublicacao);
    while (mysqli_stmt_fetch($stmt)) {
        $ultimasNoticias[] = array(
            'ID' => $ID,
            'Titulo' => $titulo,
            'Resumo' => $resumo,
            'ImagemCapa' => $imagemCapa,
            'DataPublicacao' => $dataPublicacao
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
