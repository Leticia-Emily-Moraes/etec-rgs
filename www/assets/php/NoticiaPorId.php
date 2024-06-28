<?php
require_once('../bd/conexao.php');

$idNoticia = $_GET['id'];

// Consulta principal para obter dados da notícia
$queryNoticia = "SELECT n.IdNoticia,
           n.Titulo, 
           n.Resumo, 
           n.Categoria,
           n.ImagemCapa,
           DATE_FORMAT(n.HoraPublicado, '%d/%m/%Y') AS DataPublicacao,
           u.NomeCompleto AS Autor
    FROM noticias n
    JOIN usuarios u ON n.Autor = u.IdUsuario
    WHERE n.IdNoticia = ?
    ORDER BY n.HoraPublicado DESC";

$stmtNoticia = mysqli_prepare($conexao, $queryNoticia);
mysqli_stmt_bind_param($stmtNoticia, 'i', $idNoticia);
mysqli_stmt_execute($stmtNoticia);

if ($stmtNoticia) {
    mysqli_stmt_bind_result($stmtNoticia, $ID, $titulo, $resumo, $categoria, $imagemCapa, $dataPublicacao, $autor);
    mysqli_stmt_fetch($stmtNoticia);
    $noticia = array(
        'ID' => $ID,
        'titulo' => $titulo,
        'Resumo' => $resumo,
        'Categoria' => $categoria,
        'ImagemCapa' => $imagemCapa,
        'DataPublicacao' => $dataPublicacao,
        'Autor' => $autor
    );
    mysqli_stmt_close($stmtNoticia);
} else {
    $noticia = array(); // Retorna um array vazio se não encontrar a notícia
}

// Consulta para obter conteúdo da notícia
$queryConteudo = "SELECT IdConteudo, 
           Text1, Imagem1, 
           Text2, Imagem2, 
           Text3, Imagem3, 
           Text4, Imagem4 
    FROM noticiasConteudo 
    WHERE IdNoticiaConteudo = ?";

$stmtConteudo = mysqli_prepare($conexao, $queryConteudo);
mysqli_stmt_bind_param($stmtConteudo, 'i', $idNoticia);
mysqli_stmt_execute($stmtConteudo);

$conteudos = array();
if ($stmtConteudo) {
    mysqli_stmt_bind_result($stmtConteudo, $idConteudo, $text1, $imagem1, $text2, $imagem2, $text3, $imagem3, $text4, $imagem4);
    while (mysqli_stmt_fetch($stmtConteudo)) {
        $conteudos[] = array(
            'IdConteudo' => $idConteudo,
            'Text1' => $text1,
            'Imagem1' => $imagem1,
            'Text2' => $text2,
            'Imagem2' => $imagem2,
            'Text3' => $text3,
            'Imagem3' => $imagem3,
            'Text4' => $text4,
            'Imagem4' => $imagem4
        );
    }
    mysqli_stmt_close($stmtConteudo);
}

// Combina as informações da notícia e dos conteúdos
$response = array(
    'Noticia' => $noticia,
    'Conteudos' => $conteudos
);

echo json_encode($response);

$conexao->close();
?>
