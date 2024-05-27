<?php
session_start();

var_dump($_SESSION['dados']); // Apenas para depuração, mostra os dados armazenados na sessão

include_once('../bd/conexao.php');
date_default_timezone_set('America/Sao_Paulo');

// Função para lidar com erros e exibir mensagens
function handleError($message)
{
    echo $message;
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o usuário está logado
    if (!isset($_SESSION['userName'])) {
        header('Location: ../../LoginPost.php');
        exit();
    }

    // Recupera os dados da sessão
    $dados = $_SESSION['dados'] ?? null;
    if (!$dados) {
        handleError("Erro ao recuperar os dados da sessão.");
    }

    // Decodifica os dados do formulário
    $postData = json_decode($_POST['dados'], true);
    $texts = $postData['texts'] ?? [];

    // Atribui os valores dos dados
    $titulo_original = $dados['titulo_original'];
    $resumo = $dados['resumo'];
    $temas = $dados['temas'];
    $imagem_nome_original = $dados['imagem_nome_original'];
    $imagem_temp = $dados['imagem_temp'];
    $titulo_formatado = $dados['titulo_formatado'];
    $autorID = $dados['autorID'];
    $caminho_pasta = $dados['caminho_pasta'];
    $imagem_nome_modificado = $dados['imagem_nome_modificado'];
    $caminho_imagem = $dados['caminho_imagem'];
    $target_dir_Banco = $dados['target_dir_Banco'];
    $caminho_pasta_Banco = $dados['caminho_pasta_Banco'];
    $caminho_imagem_banco = $dados['caminho_imagem_banco'];

    // Insere o artigo principal na tabela noticias
    $query = "INSERT INTO noticias (Titulo, Resumo, Categoria, ImagemCapa, Autor) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $titulo_original, $resumo, $temas, $caminho_imagem_banco, $autorID);

    if (!mysqli_stmt_execute($stmt)) {
        handleError("Erro ao inserir dados no banco de dados.");
    }

    mysqli_stmt_close($stmt);

    // Recupera o número de postagens
    $query = "SELECT COUNT(IdNoticia) FROM noticias";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $NumeroDePostagens);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    // Registra o comando de inserção em um arquivo
    $data_hora_atual = date('Y-m-d H:i:s');
    $sql_insert = "INSERT INTO noticias (IdNoticia, Titulo, Resumo, Categoria, ImagemCapa, Autor, HoraPublicado) VALUES ($NumeroDePostagens, '$titulo_original', '$resumo', '$temas', '$caminho_imagem_banco', $autorID, '$data_hora_atual');";
    $arquivo_sql = fopen("../bd/baseNoticiaCapa.sql", "a");
    fwrite($arquivo_sql, $sql_insert . "\n");
    fclose($arquivo_sql);
    // Processa as imagens do conteúdo
    $imgsArray = [];
    if (isset($_FILES['imgConteudo'])) {
        foreach ($_FILES['imgConteudo']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['imgConteudo']['error'][$key] !== UPLOAD_ERR_OK) {
                handleError("Erro ao enviar a imagem: " . $_FILES['imgConteudo']['error'][$key]);
            }

            $imgConteudo = $_FILES['imgConteudo']['name'][$key];
            $extensao = pathinfo($imgConteudo, PATHINFO_EXTENSION);
            $novo_nome_imagem = $titulo_formatado . '-' . ($key + 1) . '-Conteudo.' . $extensao;
            $caminho_imagem = $caminho_pasta . $novo_nome_imagem;
            $imgsArray[] = $caminho_imagem;

            if (!move_uploaded_file($tmp_name, $caminho_imagem)) {
                handleError("Erro ao enviar a imagem: Não foi possível mover o arquivo.");
            }
        }
    } else {
        handleError("A chave 'imgConteudo' não está definida no array \$_FILES.");
    }
    // Processa os textos do conteúdo
    $textsArray = [];
    foreach ($texts as $index => $texto) {
        $textsArray[$index] = $texto;
    }
    // Insere o conteúdo do artigo na tabela noticiasConteudo
    $query = "INSERT INTO noticiasConteudo (IdNoticia, Text1, Imagem1, Text2, Imagem2, Text3, Imagem3, Text4, Imagem4) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "issssssss", $NumeroDePostagens, $textsArray[0], $imgsArray[0], $textsArray[1], $imgsArray[1], $textsArray[2], $imgsArray[2], $textsArray[3], $imgsArray[3]);
    if (!mysqli_stmt_execute($stmt)) {
        handleError("Erro ao inserir conteúdo da notícia no banco de dados.");
    }
    mysqli_stmt_close($stmt);
    // Registra o comando de inserção de conteúdo em um arquivo
    $sql_insert_conteudo_log = "INSERT INTO noticiasConteudo (IdConteudo, IdNoticia, Text1, Imagem1, Text2, Imagem2, Text3, Imagem3, Text4, Imagem4) VALUES ($NumeroDePostagens, $NumeroDePostagens, '$textsArray[0]', '$imgsArray[0]', '$textsArray[1]', '$imgsArray[1]', '$textsArray[2]', '$imgsArray[2]', '$textsArray[3]', '$imgsArray[3]');";
    $arquivo_sql_conteudo = fopen("../bd/baseNoticiaConteudo.sql", "a");
    fwrite($arquivo_sql_conteudo, $sql_insert_conteudo_log . "\n");
    fclose($arquivo_sql_conteudo);
    // Limpa os dados da sessão para 'dados'
    unset($_SESSION['dados']);
    echo "Notícia cadastrada com sucesso!";
}
?>
