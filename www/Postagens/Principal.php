<?php

session_start();
include_once('../assets/bd/conexao.php');
include_once('Verifica.php');

if (isset($_SESSION['userName'])) {
    $userName = $_SESSION['userName'];
    //informações do Perfil Logado
    $query = "SELECT IdUsuario, NomeCompleto, NomeUser FROM usuarios WHERE NomeUser = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "s", $userName);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $idUser, $nomePessoal, $nomeUsuario);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_free_result($stmt);

    $query = "SELECT COUNT(IdNoticia) FROM noticias WHERE Autor = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "s", $idUser);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nPostagensAutor);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_free_result($stmt);

    $query = "SELECT Titulo
    FROM noticias
    WHERE Autor = ?
    ORDER BY HoraPublicado DESC
    LIMIT 1";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "s", $idUser);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $uPostagemAutor);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_free_result($stmt);

    //informações Gerais

    $query = "SELECT COUNT(IdNoticia) FROM noticias";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $NumeroDePostagens);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_free_result($stmt);

    $query = "SELECT COUNT(IdUsuario) FROM usuarios";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $NumeroDeAutores);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_free_result($stmt);

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
}
?>