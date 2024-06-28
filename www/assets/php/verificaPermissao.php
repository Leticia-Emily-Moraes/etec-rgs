<?php
session_start();
include_once('../bd/conexao.php');

if (!isset($_SESSION['userName'])) {
    echo json_encode(array("erro" => "Usuário não logado"));
    exit;
}

$userName = $_SESSION['userName'];
$idAutor = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$response = array();

// Consulta para obter o ID e Cargo do usuário logado
$query = "SELECT IdUsuario, Cargo FROM usuarios WHERE NomeUser = ?";
$stmt = mysqli_prepare($conexao, $query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $userName);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $idUsuarioLogado, $cargoUsuarioLogado);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $response['cargoUsuarioLogado'] = $cargoUsuarioLogado;
} else {
    echo json_encode(array("erro" => "Falha ao verificar cargo do usuário logado"));
    exit;
}

// Consulta para obter o Cargo do autor a ser excluído
$query = "SELECT Cargo FROM usuarios WHERE IdUsuario = ?";
$stmt = mysqli_prepare($conexao, $query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $idAutor);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $cargoAutor);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    $response['cargoAutor'] = $cargoAutor;
} else {
    echo json_encode(array("erro" => "Falha ao verificar cargo do autor"));
    exit;
}

echo json_encode($response);

mysqli_close($conexao);
?>
