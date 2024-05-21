<?php
// Inicializa a sessão
session_start();

// Inclui o arquivo de conexão com o banco de dados
include_once('../assets/bd/conexao.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se o usuário está logado
    if (!isset($_SESSION['userName'])) {
        // Se o usuário não estiver logado, redireciona para a página de login
        header("Location: PaginaDeLogin.php");
        exit();
    }

    // Validação do formulário
    if (empty($_POST['Titulo']) || empty($_POST['Resumo']) || empty($_POST['Temas']) || empty($_FILES["img"]["name"])) {
        echo "Por favor, preencha todos os campos.";
        exit();
    }

    // Recupera os dados do formulário
    $titulo_original = $_POST['Titulo'];
    $resumo = $_POST['Resumo'];
    $temas = $_POST['Temas'];
    $imagem_nome_original = $_FILES["img"]["name"];
    $imagem_temp = $_FILES["img"]["tmp_name"];

    // Lógica para obter o ID do usuário
    $userName = $_SESSION['userName'];
    $query = "SELECT IdUsuario FROM usuarios WHERE NomeUser = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "s", $userName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $autorID = (int)$row['IdUsuario'];
    } else {
        $erroUser = "Erro ao obter o ID do usuário.";
        header("Location: PostagemCapa.php");
        exit();
    }

    // Diretório de destino da imagem
    $target_dir = "../assets/img/Noticias/";

    // Formatar o título para ser o nome da pasta
    $titulo_formatado = toCamelCase($titulo_original);

    // Criar a pasta com o título formatado da notícia
    $caminho_pasta = $target_dir . $titulo_formatado . '/';
    if (!file_exists($caminho_pasta)) {
        if (!mkdir($caminho_pasta, 0777, true)) {
            echo "Erro ao criar a pasta da notícia.";
            exit();
        }
    }

    // Mover a imagem para o diretório com o nome original
    $caminho_imagem = $caminho_pasta . $imagem_nome_original;
    if (!move_uploaded_file($imagem_temp, $caminho_imagem)) {
        echo "Erro ao enviar a imagem.";
        exit();
    }
    $sql_insert = "INSERT INTO noticias (Titulo, Resumo, Categoria, ImagemCapa, Autor) VALUES ('$titulo_original', '$resumo', '$temas', '$caminho_imagem', $autorID);";

    // Salvar a query em um arquivo SQL
    $arquivo_sql = fopen("../assets/bd/baseData.sql", "a");
    fwrite($arquivo_sql, $sql_insert . "\n");
    fclose($arquivo_sql);

    // Prepara o comando SQL para inserir os dados na tabela
    $query = "INSERT INTO noticias (Titulo, Resumo, Categoria, ImagemCapa, Autor) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $titulo_original, $resumo, $temas, $caminho_imagem, $autorID);
    
    // Executa o comando SQL
    if (!mysqli_stmt_execute($stmt)) {
        echo "Erro ao inserir dados no banco de dados.";
        exit();
    }
}
// Função para formatar o título
function removeAcentos($str) {
    $acentos = array('á', 'à', 'ã', 'â', 'é', 'ê', 'í', 'ó', 'ô', 'õ', 'ú', 'ç', 'ü', 'ñ');
    $semAcentos = array('a', 'a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'o', 'u', 'c', 'u', 'n');
    return str_replace($acentos, $semAcentos, $str);
}

function toCamelCase($str) {
    $str = removeAcentos($str); // Remove os acentos da string
    $words = explode(' ', strtolower($str)); // Divide a string em palavras e as converte para minúsculas
    $camelCase = lcfirst(implode('', array_map('ucfirst', $words))); // Capitaliza a primeira letra de cada palavra e as junta
    return $camelCase;
}
?>



<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postagem Rgs - Postar</title>

    <link rel="shortcut icon" href="../assets/img/Favicons/etecRgs.ico" type="image/x-icon">
    <!-- Arquivo reset CSS -->
    <link rel="stylesheet" href="../assets/style/reset.css">
    <!-- Arquivo CSS -->
    <link rel="stylesheet" href="../assets/style/pattern.css">
    <!-- Arquivo CSS responsivo -->
    <link rel="stylesheet" href="../assets/style/patternResponsive.css">
    <!-- Arquivo CSS proprio -->
    <link rel="stylesheet" href="../assets/style/postagemCapa.css">


</head>

<body>
    <header id="header">
        <a href="#"><img class="logo" src="../assets/img/icons/iconsNav/etecRgs.png" alt=""></a>
        <img id="menu" src="../assets/img/icons/iconsNav/Menu.svg" alt="">
        <img id="menuClose" src="../assets/img/icons/iconsNav/CloseMenu.svg" alt="">
        <nav id="navPrincipal">
            <ul>
                <li><a href="Principal.php">Início</a></li>
                <li><a href="postagemCapa.php">Postar Noticia</a></li>
                <li><a href="#">Ver Postagens</a></li>
                <li><a href="#">Autores</a></li>
                <li><a href="#">Logout<img src="../assets/img/icons/IconLogout.svg" alt=""></a></li>
            </ul>
        </nav>
        <!-- Final da Navbar -->
    </header>
    <main>
        <section class="cadastraPostagem">
            <h2>Capa da Noticia</h2>
            <form id="postForm" action="" method="post" enctype="multipart/form-data">
                <div>
                    <label for="Titulo">Titulo:</label>
                    <input type="text" name="Titulo" id="Titulo" maxlength="30" placeholder="Titulo da Notícia" required>
                </div>
                <div>
                    <label for="Resumo">Resumo:</label>
                    <textarea name="Resumo" id="Resumo" cols="20" rows="5" placeholder="Seu resumo da notícia com no máximo 200 caracteres" maxlength="200" required></textarea>
                </div>
                <div>
                    <label for="img">Imagem principal: </label>
                    <label for="img" translate="0" tabindex="0" id="imagem">
                        <span class="ImagemPreview"></span>
                    </label>
                    <input type="file" accept="image/*" id="img" name="img" required>
                </div>
                <div>
                    <label for="Temas">Essa Postagem é sobre: </label>
                    <select name="Temas" id="Temas" required>
                        <option value="Biblioteca">Biblioteca</option>
                        <option value="Direção">Direção</option>
                        <option value="Esportes">Esportes</option>
                        <option value="Esportes">Eventos</option>
                        <option value="Grêmio">Grêmio</option>
                        <option value="Esportes">Vestibulinho</option>
                    </select>
                </div>
                <div>
                    <input type="submit" value="Próximo">
                </div>
            </form>
        </section>
    </main>
    <script src="../assets/js/postagemCapa.js"></script>
    <script src="../assets/js/pattern.js"></script>
</body>

</html>