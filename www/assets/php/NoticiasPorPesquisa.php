<?php

session_start();

include_once('../bd/conexao.php');

// Obtém o termo de pesquisa e a página a partir da solicitação GET
$termoPesquisa = isset($_GET['termo']) ? '%' . $_GET['termo'] . '%' : '';

$query = "SELECT n.IdNoticia,
n.Titulo, 
n.Resumo, 
n.Categoria,
n.ImagemCapa,
DATE_FORMAT(n.HoraPublicado, '%d/%m/%Y') AS DataPublicacao,
u.NomeCompleto AS Autor
FROM noticias n
JOIN usuarios u ON n.Autor = u.IdUsuario
WHERE n.Titulo LIKE ? OR n.Resumo LIKE ?
ORDER BY n.HoraPublicado DESC";

$stmt = mysqli_prepare($conexao, $query);
mysqli_stmt_bind_param($stmt, 'ss', $termoPesquisa, $termoPesquisa);
mysqli_stmt_execute($stmt);

if ($stmt) {
    $noticiasPorPesquisa = array();
    mysqli_stmt_bind_result($stmt, $ID, $titulo, $resumo, $categoria, $imagemCapa, $dataPublicacao, $autor);
    while (mysqli_stmt_fetch($stmt)) {
        $noticiasPorPesquisa[] = array(
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
    $noticiasPorPesquisa = array();
}

mysqli_close($conexao);

header('Content-Type: application/json');
echo json_encode($noticiasPorPesquisa);
exit();
?>
