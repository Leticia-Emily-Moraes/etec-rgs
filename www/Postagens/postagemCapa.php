<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');

// Inclua a verificação de sessão/cookie como a primeira coisa a ser feita
include_once('Verifica.php');

// Inclua a conexão com o banco de dados
include_once('../assets/bd/conexao.php');

// Verifica se o usuário está logado
if (!isset($_SESSION['userName'])) {
    header("Location: LoginPost.php");
    exit();
}

// Função para remover acentos de uma string
function removeAcentos($str)
{
    $acentos = array('á', 'à', 'ã', 'â', 'é', 'ê', 'í', 'ó', 'ô', 'õ', 'ú', 'ç', 'ü', 'ñ');
    $semAcentos = array('a', 'a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'o', 'u', 'c', 'u', 'n');
    return str_replace($acentos, $semAcentos, $str);
}

// Função para converter uma string para camel case
function toCamelCase($str)
{
    $str = removeAcentos($str);
    $words = explode(' ', strtolower($str));
    return lcfirst(implode('', array_map('ucfirst', $words)));
}

// Prosseguir se for uma requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar dados do formulário
    $titulo_original = $_POST['Titulo'] ?? null;
    $resumo = $_POST['Resumo'] ?? null;
    $temas = $_POST['Temas'] ?? null;
    $imagem_nome_original = $_FILES["img"]["name"] ?? null;
    $imagem_temp = $_FILES["img"]["tmp_name"] ?? null;

    // Verificar se todos os campos obrigatórios são fornecidos
    if (!$titulo_original || !$resumo || !$temas || !$imagem_nome_original || !$imagem_temp) {
        echo "Todos os campos são obrigatórios.";
        exit();
    }

    // Validação do tipo de imagem
    $imagem_tipo = $_FILES["img"]["type"];
    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($imagem_tipo, $tipos_permitidos)) {
        echo "Tipo de imagem não permitido. Apenas JPEG, PNG e GIF são aceitos.";
        exit();
    }

    // Recuperar o ID do usuário
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

    // Contar artigos de notícias existentes
    $query = "SELECT COUNT(IdNoticia) FROM noticias";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $NumeroDePostagens);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Criar pasta para imagens do artigo de notícias
    $target_dir = "../assets/img/Noticias/";
    $titulo_formatado = toCamelCase($titulo_original);
    $titulo_formatado = str_replace(' ', '-', $titulo_formatado);
    $caminho_pasta = $target_dir . $titulo_formatado . '/';
    $imagem_nome_modificado = $titulo_formatado . '-capa' . '.' . pathinfo($imagem_nome_original, PATHINFO_EXTENSION);
    $caminho_imagem = $caminho_pasta . $imagem_nome_modificado;

    // Criar pasta se não existir
    if (!file_exists($caminho_pasta) && !mkdir($caminho_pasta, 0777, true)) {
        echo "Erro ao criar a pasta da notícia.";
        exit();
    }

    // Mover imagem carregada para a pasta de destino
    if (!move_uploaded_file($imagem_temp, $caminho_imagem)) {
        echo "Erro ao enviar a imagem de capa. Debugging info: " . var_dump($_FILES['img']['error']) . "<br>";
        exit();
    }

    // Armazenar dados na sessão para uso posterior
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
        'caminho_imagem' => $caminho_imagem
    ];

    $sucesso = true;
    exit();
}
?>
