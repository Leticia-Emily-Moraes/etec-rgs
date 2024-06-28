<?php
session_start();
include_once('../bd/conexao.php');

if (!isset($_SESSION['userName'])) {
    echo json_encode(array("erro" => "Usuário não logado"));
    exit;
}

$userName = $_SESSION['userName'];

// Consulta para obter o cargo do usuário logado
$query = "SELECT Cargo FROM usuarios WHERE NomeUser = ?";
$stmt = mysqli_prepare($conexao, $query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $userName);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $cargoUsuarioLogado);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    echo json_encode(array("cargoUsuarioLogado" => $cargoUsuarioLogado));
} else {
    echo json_encode(array("erro" => "Falha ao verificar cargo do usuário"));
}

mysqli_close($conexao);
?>
