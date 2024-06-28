<?php

session_start();
include_once('../bd/conexao.php');
include_once('Verifica.php');

$autores = array();

// Consulta para obter todos os usuários com suas informações básicas e última notícia
$query = "SELECT u.IdUsuario, u.NomeCompleto, u.Cargo, u.ImagemAutor,
                 COUNT(n.IdNoticia) AS Postagens,
                 (SELECT Titulo FROM noticias WHERE Autor = u.IdUsuario ORDER BY HoraPublicado DESC LIMIT 1) AS UltimaPostagem
          FROM usuarios u
          LEFT JOIN noticias n ON u.IdUsuario = n.Autor
          GROUP BY u.IdUsuario, u.NomeCompleto, u.Cargo, u.ImagemAutor
          ORDER BY u.NomeCompleto";

$stmt = mysqli_prepare($conexao, $query);
if ($stmt) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $idUser, $nomePessoal, $cargoUser, $ImagemUser, $nPostagensAutor, $uPostagemAutor);

    while (mysqli_stmt_fetch($stmt)) {
        $autor = array(
            'IdUsuario' => $idUser,
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

echo json_encode($autores); // Retorna o array de autores com todas as informações
?>
