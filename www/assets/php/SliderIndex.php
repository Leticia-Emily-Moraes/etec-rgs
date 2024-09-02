<?php
session_start();

include_once ('../bd/conexao.php');

mysqli_set_charset($conexao, "utf8");


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

$ultimasNoticias = array();

if ($stmt) {
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
}

mysqli_close($conexao);

header("Content-Type: application/json");

$show_json = json_encode($ultimasNoticias, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR);
if ($show_json === false) {
    die("json_encode fail: " . json_last_error_msg());
} else {
    echo $show_json;
}

exit();
?>
