<?php
session_start();
include_once('../bd/conexao.php');
include_once('Verifica.php');

if (isset($_SESSION['userName'])) {
    $userName = $_SESSION['userName'];

    // Informações do Perfil Logado
    $query = "SELECT IdUsuario, NomeCompleto, NomeUser, ImagemAutor FROM usuarios WHERE NomeUser = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "s", $userName);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $idUser, $nomePessoal, $nomeUsuario, $ImagemUsuario);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_free_result($stmt);

    // Número de postagens do autor
    $query = "SELECT COUNT(IdNoticia) FROM noticias WHERE Autor = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "i", $idUser);  // Ajustado para inteiro
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nPostagensAutor);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_free_result($stmt);

    // Última postagem do autor
    $query = "SELECT Titulo FROM noticias WHERE Autor = ? ORDER BY HoraPublicado DESC LIMIT 1";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "i", $idUser);  // Ajustado para inteiro
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $uPostagemAutor);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_free_result($stmt);

    // Informações gerais

    // Número de postagens
    $query = "SELECT COUNT(IdNoticia) FROM noticias";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $NumeroDePostagens);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_free_result($stmt);

    // Número de autores
    $query = "SELECT COUNT(IdUsuario) FROM usuarios";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $NumeroDeAutores);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_free_result($stmt);

    // Autor com mais postagens
    $query = "SELECT u.NomeCompleto AS Autor
              FROM usuarios u
              JOIN noticias n ON u.IdUsuario = n.Autor
              GROUP BY u.IdUsuario
              ORDER BY COUNT(n.IdNoticia) DESC
              LIMIT 1";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $MaisPostou);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_free_result($stmt);

    // Categoria mais postada
    $query = "SELECT n.Categoria AS Categoria
              FROM noticias n
              GROUP BY n.Categoria
              ORDER BY COUNT(n.IdNoticia) DESC
              LIMIT 1";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $CategoriaMaisPostada);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_free_result($stmt);

    // Montando array associativo com os dados
    $dados = array(
        "nomePessoal" => $nomePessoal,
        "nomeUsuario" => $nomeUsuario,
        "ImagemUsuario" => $ImagemUsuario,
        "nPostagensAutor" => $nPostagensAutor,
        "uPostagemAutor" => $uPostagemAutor,
        "NumeroDePostagens" => $NumeroDePostagens,
        "NumeroDeAutores" => $NumeroDeAutores,
        "MaisPostou" => $MaisPostou,
        "CategoriaMaisPostada" => $CategoriaMaisPostada
    );

    // Convertendo array associativo para JSON
    echo json_encode($dados);

    mysqli_stmt_close($stmt);
    mysqli_close($conexao);
} else {
    echo json_encode(array("error" => "Usuário não autenticado."));
}
?>
