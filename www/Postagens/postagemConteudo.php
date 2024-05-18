<?php
session_start();

var_dump($_SESSION['dados']);

include_once('../assets/bd/conexao.php');
date_default_timezone_set('America/Sao_Paulo');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['userName'])) {
        header('Location: LoginPost.php');
        exit();
    }

    $dados = $_SESSION['dados'] ?? null;
    if (!$dados) {
        echo "Erro ao recuperar os dados da sessão.";
        exit();
    }
    
    $postData = json_decode($_POST['dados'], true);
    $texts = $postData['texts'] ?? [];


    $titulo_original = $dados['titulo_original'];
    $resumo = $dados['resumo'];
    $temas = $dados['temas'];
    $imagem_nome_original = $dados['imagem_nome_original'];
    $imagem_temp = $dados['imagem_temp'];
    $autorID = $dados['autorID'];
    $caminho_pasta = $dados['caminho_pasta'];
    $imagem_nome_modificado = $dados['imagem_nome_modificado'];
    $caminho_imagem = $dados['caminho_imagem'];

    // Inserir dados na tabela noticias
    $query = "INSERT INTO noticias (Titulo, Resumo, Categoria, ImagemCapa, Autor) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $titulo_original, $resumo, $temas, $caminho_imagem, $autorID);

    if (!mysqli_stmt_execute($stmt)) {
        echo "Erro ao inserir dados no banco de dados.";
        exit();
    }
    mysqli_stmt_close($stmt); // Close the statement after execution

    // Obter o número de postagens
    $query = "SELECT COUNT(IdNoticia) FROM noticias";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $NumeroDePostagens);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt); // Close the statement after fetching result

    $data_hora_atual = date('Y-m-d H:i:s');
    $sql_insert = "INSERT INTO noticias (IdNoticia, Titulo, Resumo, Categoria, ImagemCapa, Autor, HoraPublicado) VALUES ($NumeroDePostagens, '$titulo_original', '$resumo', '$temas', '$caminho_imagem', $autorID, '$data_hora_atual');";

    $arquivo_sql = fopen("../assets/bd/baseData.sql", "a");
    fwrite($arquivo_sql, $sql_insert . "\n");
    fclose($arquivo_sql);

    if (isset($_FILES['imgConteudo'])) {
        foreach ($_FILES['imgConteudo']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['imgConteudo']['error'][$key] !== UPLOAD_ERR_OK) {
                echo "Erro ao enviar a imagem: " . $_FILES['imgConteudo']['error'][$key];
                exit();
            }

            $imgConteudo = $_FILES['imgConteudo']['name'][$key];
            $extensao = pathinfo($imgConteudo, PATHINFO_EXTENSION);
            $novo_nome_imagem = $titulo_original . '-' . ($key + 1) . '-Conteudo.' . $extensao;
            $caminho_imagem = $caminho_pasta . $novo_nome_imagem;
            $imgsArray[] = $caminho_imagem;

            if (!move_uploaded_file($tmp_name, $caminho_imagem)) {
                echo "Erro ao enviar a imagem: Não foi possível mover o arquivo.";
                exit();
            }
        }
    } else {
        echo "A chave 'imgConteudo' não está definida no array \$_FILES.";
        exit();
    }

    $textsArray = array(); // Inicialize o array se ainda não foi inicializado

    foreach ($texts as $index => $texto) {
        $textsArray[$index] = $texto;
    }

    // Inserir dados na tabela noticiasConteudo
    $query = "INSERT INTO noticiasConteudo (IdNoticia, Text1, Imagem1, Text2, Imagem2, Text3, Imagem3, Text4, Imagem4) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "issssssss", $NumeroDePostagens, $textsArray[0], $imgsArray[0], $textsArray[1], $imgsArray[1], $textsArray[2], $imgsArray[2], $textsArray[3], $imgsArray[3]);

    if (!mysqli_stmt_execute($stmt)) {
        echo "Erro ao inserir conteúdo da notícia no banco de dados.";
        exit();
    }
    mysqli_stmt_close($stmt); // Close the statement after execution

    echo "Notícia cadastrada com sucesso!";
}