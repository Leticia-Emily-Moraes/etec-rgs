<?php

session_start();

include_once('../bd/conexao.php');

// Número de notícias por página
$noticiasPorPagina = 4;

// Obtém a categoria e a página a partir da solicitação GET
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $noticiasPorPagina;

$query = "SELECT n.Titulo, 
n.Resumo, 
n.Categoria,
n.ImagemCapa,
DATE_FORMAT(n.HoraPublicado, '%d/%m/%Y') AS DataPublicacao,
u.NomeCompleto AS Autor
FROM noticias n
JOIN usuarios u ON n.Autor = u.IdUsuario
WHERE n.Categoria = ?
ORDER BY n.HoraPublicado DESC
LIMIT ? OFFSET ?";

$stmt = mysqli_prepare($conexao, $query);
mysqli_stmt_bind_param($stmt, 'sii', $categoria, $noticiasPorPagina, $offset);
mysqli_stmt_execute($stmt);

if ($stmt) {
    $noticiasPorTema = array();
    mysqli_stmt_bind_result($stmt, $titulo, $resumo, $categoria, $imagemCapa, $dataPublicacao, $autor);
    while (mysqli_stmt_fetch($stmt)) {
        $noticiasPorTema[] = array(
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
    $noticiasPorTema = array();
}

mysqli_close($conexao);

header('Content-Type: application/json');
echo json_encode($noticiasPorTema);
exit();
?>
