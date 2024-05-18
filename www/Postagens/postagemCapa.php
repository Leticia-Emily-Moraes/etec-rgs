<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

include_once('../assets/bd/conexao.php');

// Check if user is logged in
if (!isset($_SESSION['userName'])) {
    header("Location: LoginPost.php");
    exit();
}

// Proceed if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $titulo_original = $_POST['Titulo'] ?? null;
    $resumo = $_POST['Resumo'] ?? null;
    $temas = $_POST['Temas'] ?? null;
    $imagem_nome_original = $_FILES["img"]["name"] ?? null;
    $imagem_temp = $_FILES["img"]["tmp_name"] ?? null;

    // Check if all required fields are provided
    if (!$titulo_original || !$resumo || !$temas || !$imagem_nome_original || !$imagem_temp) {
        echo "Todos os campos são obrigatórios.";
        exit();
    }

    // Retrieve user ID
    $userName = $_SESSION['userName'];
    $query = "SELECT IdUsuario FROM usuarios WHERE NomeUser = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "s", $userName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result || mysqli_num_rows($result) === 0) {
        echo "Erro ao obter o ID do usuário.";
        exit();
    }

    $row = mysqli_fetch_assoc($result);
    $autorID = (int)$row['IdUsuario'];
    mysqli_stmt_close($stmt);

    // Count existing news articles
    $query = "SELECT COUNT(IdNoticia) FROM noticias";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $NumeroDePostagens);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Create folder for news article images
    $target_dir = "../assets/img/Noticias/";
    $titulo_formatado = toCamelCase($titulo_original);
    $titulo_formatado = str_replace(' ', '-', $titulo_formatado);
    $caminho_pasta = $target_dir . $titulo_formatado . '/';
    $imagem_nome_modificado = $titulo_formatado . '-capa' . '.' . pathinfo($imagem_nome_original, PATHINFO_EXTENSION);
    $caminho_imagem = $caminho_pasta . $imagem_nome_modificado;

    // Create folder if it doesn't exist
    if (!file_exists($caminho_pasta) && !mkdir($caminho_pasta, 0777, true)) {
        echo "Erro ao criar a pasta da notícia.";
        exit();
    }

    // Move uploaded image to destination folder
    if (!move_uploaded_file($imagem_temp, $caminho_imagem)) {
        echo "Erro ao enviar a imagem de capa. Debugging info: " . var_dump($_FILES['imagem_temp']['error']) . "<br>";
        exit();
    }

    // Store data in session for later use
    $_SESSION['dados'] = [
        'titulo_original' => $titulo_original,
        'resumo' => $resumo,
        'temas' => $temas,
        'imagem_nome_original' => $imagem_nome_original,
        'imagem_temp' => $imagem_temp,
        'autorID' => $autorID,
        'NumeroDePostagens' => $NumeroDePostagens,
        'caminho_pasta' => $caminho_pasta,
        'imagem_nome_modificado' => $imagem_nome_modificado,
        'caminho_imagem' => $caminho_imagem // New addition
    ];

    exit();
}

// Function to remove accents from a string
function removeAcentos($str)
{
    $acentos = array('á', 'à', 'ã', 'â', 'é', 'ê', 'í', 'ó', 'ô', 'õ', 'ú', 'ç', 'ü', 'ñ');
    $semAcentos = array('a', 'a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'o', 'u', 'c', 'u', 'n');
    return str_replace($acentos, $semAcentos, $str);
}

// Function to convert a string to camel case
function toCamelCase($str)
{
    $str = removeAcentos($str);
    $words = explode(' ', strtolower($str));
    return lcfirst(implode('', array_map('ucfirst', $words)));
}
?>
