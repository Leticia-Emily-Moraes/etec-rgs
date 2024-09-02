<?php

session_start();

date_default_timezone_set('America/Sao_Paulo');

include_once('Verifica.php');

include_once('../bd/conexao.php');


if (!isset($_SESSION['userName'])) {
    header("Location: ../../LoginPost.php");
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

// Função para exibir mensagens de erro
function displayError($message) {
    echo $message;
    exit();
}

// Prossegue se for uma requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera dados do formulário
    $titulo_original = $_POST['Titulo'] ?? null;
    $resumo = $_POST['Resumo'] ?? null;
    $temas = $_POST['Temas'] ?? null;
    $imagem_nome_original = $_FILES["img"]["name"] ?? null;
    $imagem_temp = $_FILES["img"]["tmp_name"] ?? null;

    // Verifica se todos os campos obrigatórios são fornecidos
    if (!$titulo_original || !$resumo || !$temas || !$imagem_nome_original || !$imagem_temp) {
        displayError("Todos os campos são obrigatórios.");
    }

    // Validação do tipo de imagem
    $imagem_tipo = $_FILES["img"]["type"];
    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($imagem_tipo, $tipos_permitidos)) {
        displayError("Tipo de imagem não permitido. Apenas JPEG, PNG e GIF são aceitos.");
    }

    // Recupera o ID do usuário
    $userName = $_SESSION['userName'];
    $query = "SELECT IdUsuario FROM usuarios WHERE NomeUser = ?";
    $stmt = mysqli_prepare($conexao, $query);
    if (!$stmt) {
        displayError("Erro ao preparar a consulta: " . mysqli_error($conexao));
    }
    mysqli_stmt_bind_param($stmt, "s", $userName);
    if (!mysqli_stmt_execute($stmt)) {
        displayError("Erro ao executar a consulta: " . mysqli_stmt_error($stmt));
    }
    $result = mysqli_stmt_get_result($stmt);

    if (!$result || mysqli_num_rows($result) === 0) {
        displayError("Erro ao obter o ID do usuário.");
    }

    $row = mysqli_fetch_assoc($result);
    $autorID = (int)$row['IdUsuario'];
    mysqli_stmt_close($stmt);


    $query = "SELECT COUNT(IdNoticia) FROM noticias";
    $stmt = mysqli_prepare($conexao, $query);
    if (!mysqli_stmt_execute($stmt)) {
        displayError("Erro ao executar a consulta: " . mysqli_stmt_error($stmt));
    }
    mysqli_stmt_bind_result($stmt, $NumeroDePostagens);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Cria pasta para imagens do artigo de notícias
    $target_dir_Banco = "../assets/img/Noticias/";
    $target_dir = "../img/Noticias/";
    $titulo_formatado = str_replace(' ', '-', $titulo_original);
    $titulo_formatado_CamelCase = toCamelCase($titulo_original);
    

    $caminho_pasta = $target_dir . $titulo_formatado_CamelCase . '/';
    $caminho_pasta_Banco = $target_dir_Banco . $titulo_formatado_CamelCase . '/';
    $imagem_nome_modificado = $titulo_formatado . '-capa' . '.' . pathinfo($imagem_nome_original, PATHINFO_EXTENSION);
    $caminho_imagem = $caminho_pasta . $imagem_nome_modificado;
    $caminho_imagem_banco =  $caminho_pasta_Banco . $imagem_nome_modificado;

    // Cria pasta se não existir
    if (!file_exists($caminho_pasta) && !mkdir($caminho_pasta, 0777, true)) {
        displayError("Erro ao criar a pasta da notícia.");
    }

    // Move imagem carregada para a pasta de destino
    if (!move_uploaded_file($imagem_temp, $caminho_imagem)) {
        displayError("Erro ao enviar a imagem de capa. Debugging info: " . var_dump($_FILES['img']['error']) . "<br>");
    }

    // Armazena dados na sessão para uso posterior
    $_SESSION['dados'] = [
        'titulo_original' => $titulo_original,
        'resumo' => $resumo,
        'temas' => $temas,
        'imagem_nome_original' => $imagem_nome_original,
        'imagem_temp' => $imagem_temp,
        'titulo_formatado' => $titulo_formatado,
        'autorID' => $autorID,
        'NumeroDePostagens' => $NumeroDePostagens,
        'caminho_pasta' => $caminho_pasta,
        'imagem_nome_modificado' => $imagem_nome_modificado,
        'caminho_imagem' => $caminho_imagem,
        'target_dir_Banco' => $target_dir_Banco,
        'caminho_pasta_Banco' => $caminho_pasta_Banco,
        'caminho_imagem_banco' => $caminho_imagem_banco
    ];

    $sucesso = true;
    exit();
}
?>
