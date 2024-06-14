<?php

session_start();
include_once('../bd/conexao.php');
include_once('Verifica.php');

$autores = array();

// Consulta para obter todos os usuários
$query = "SELECT IdUsuario, NomeCompleto, Cargo, ImagemAutor FROM usuarios";
$stmt = mysqli_prepare($conexao, $query);
if ($stmt) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $idUser, $nomePessoal, $cargoUser, $ImagemUser);

    while (mysqli_stmt_fetch($stmt)) {
        // Consulta para obter o número de postagens do usuário
        $subQuery1 = "SELECT COUNT(IdNoticia) FROM noticias WHERE Autor = ?";
        $subStmt1 = mysqli_prepare($conexao, $subQuery1);
        if ($subStmt1) {
            mysqli_stmt_bind_param($subStmt1, "i", $idUser);
            mysqli_stmt_execute($subStmt1);
            mysqli_stmt_bind_result($subStmt1, $nPostagensAutor);
            mysqli_stmt_fetch($subStmt1);
            mysqli_stmt_close($subStmt1);
        } else {
            $nPostagensAutor = 0;
        }

        // Consulta para obter a última postagem do usuário
        $subQuery2 = "SELECT Titulo FROM noticias WHERE Autor = ? ORDER BY HoraPublicado DESC LIMIT 1";
        $subStmt2 = mysqli_prepare($conexao, $subQuery2);
        if ($subStmt2) {
            mysqli_stmt_bind_param($subStmt2, "i", $idUser);
            mysqli_stmt_execute($subStmt2);
            mysqli_stmt_bind_result($subStmt2, $uPostagemAutor);
            mysqli_stmt_fetch($subStmt2);
            mysqli_stmt_close($subStmt2);
        } else {
            $uPostagemAutor = 'Nenhuma postagem';
        }

        $autor = array(
            'Nome' => $nomePessoal,
            'Cargo' => $cargoUser,
            'Postagens' => $nPostagensAutor,
            'UltimaPostagem' => $uPostagemAutor ? $uPostagemAutor : 'Nenhuma postagem',
            'Imagem' => $ImagemUser ? $ImagemUser : 'assets/img/equipe/UserTemporario.png'
        );

        $autores[] = $autor; // Adiciona o autor ao array
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(array("erro" => "Falha ao preparar a consulta principal"));
    exit;
}

mysqli_close($conexao);

echo json_encode($autores); // Retorna o array de autores
?>
