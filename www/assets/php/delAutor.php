<?php
session_start();
include_once('../bd/conexao.php');
include_once('Verifica.php');

if (isset($_GET['id'])) {
    $idAutor = $_GET['id'];

    // Consulta para obter a imagem do autor
    $query = "SELECT ImagemAutor FROM usuarios WHERE IdUsuario = ?";
    $stmt = mysqli_prepare($conexao, $query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $idAutor);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $imagemAutor);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Deleta a imagem do autor
        $diretorioImagem = "../../" . $imagemAutor;
        if (file_exists($diretorioImagem)) {
            unlink($diretorioImagem);
        }

        // Consulta para deletar o autor
        $query = "DELETE FROM usuarios WHERE IdUsuario = ?";
        $stmt = mysqli_prepare($conexao, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $idAutor);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            echo json_encode(array("mensagem" => "Autor deletado com sucesso!"));
        } else {
            echo json_encode(array("erro" => "Falha ao deletar autor"));
        }
    } else {
        echo json_encode(array("erro" => "Falha ao obter imagem do autor"));
    }
} else {
    echo json_encode(array("erro" => "ID do autor não informado"));
}

mysqli_close($conexao);
?>