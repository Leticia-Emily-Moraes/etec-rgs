<?php
session_start();

include_once('../assets/bd/conexao.php');

if (isset($_SESSION['userName'])) {

    if (isset($_GET['success']) && $_GET['success'] == 'true') {
        $NoticiaPostada = "Notícia postada com sucesso.";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $titulo = $_POST['Titulo'];
        $descricao = $_POST['Desc'];
        $userName = $_SESSION['userName'];

        $query = "SELECT IdUsuario FROM usuarios WHERE NomeUser = '$userName'";
        $result = mysqli_query($conexao, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $autorID = $row['IdUsuario'];

            // Verifica se um arquivo foi enviado
            if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
                $nomeImg = basename($_FILES['img']['name']);
                $diretorio = "../assets/img/noticias/";
                $caminhoImg = $diretorio . $nomeImg;

                // Move o arquivo para o diretório de destino
                if (move_uploaded_file($_FILES['img']['tmp_name'], $caminhoImg)) {
                    $img = file_get_contents($caminhoImg);
                    $img = mysqli_real_escape_string($conexao, $img);

                    // Insere os dados no banco de dados
                    $inserirBanco = "INSERT INTO noticias (Titulo, Imagem, Descricao, Autor) VALUES ('$titulo', '$img', '$descricao', '$autorID')";
                    $insercaoComSucesso = mysqli_query($conexao, $inserirBanco);
                    if ($insercaoComSucesso) {
                        if (isset($_GET['success']) && $_GET['success'] == 'true') {
                            $NoticiaPostada = "Notícia postada com sucesso.";
                        } else {
                            $NoticiaPostada = "Notícia postada com sucesso.";
                            header("Location: postagem.php?success=true");
                            exit();
                        }
                    } else {
                        $NoticiaErro = "Erro ao inserir notícia no banco de dados.";
                        header("Location: postagem.php");
                        exit();
                    }
                } else {
                    $erroImagem = "Erro ao fazer upload da imagem.";
                    header("Location: postagem.php");
                    exit();
                }
            } else {
                $erroImagem = "Nenhuma imagem enviada ou ocorreu um erro durante o upload.";
                header("Location: postagem.php");
                exit();
            }
        } else {
            $erroUser = "Erro ao obter o ID do usuário.";
            header("Location: postagem.php");
            exit();
        }
    }
}

mysqli_close($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postagens RGS</title>
    <link rel="stylesheet" href="../assets/css/postagem.css">
</head>

<body>
    <header>
        <img src="../assets/img/icons/etecRgs.png" alt="">
        <h1>Postagens RGS</h1>
        <nav>
            <ul>
                <li><a href="">Todas as Postagens</a></li>
                <li><a href="">Autores</a></li>
            </ul>
        </nav>
        <div id="Perfil">
            <p>Olá, Fulano</p>
            <a href=""></a>
        </div>
    </header>
    <main>
        <section class="cadastraPostagem">
            <h2>Postar Noticia</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <?php if (isset($erroUser)) { ?>
                    <p class="Erro"><?php echo $erroUser; ?></p>
                <?php } elseif (isset($erroImagem)) { ?>
                    <p class="Erro"><?php echo $erroImagem; ?></p>
                    <div>
                    <?php } elseif (isset($NoticiaErro)) { ?>
                        <p class="Erro"><?php echo $NoticiaErro;
                                    } ?></p>
                        <?php if (isset($NoticiaPostada)) { ?>
                            <p class="Sucesso"><?php echo $NoticiaPostada;
                                            } ?></p>
                            <div>
                                <label for="Titulo">Titulo:</label>
                                <input type="text" name="Titulo" id="Titulo">
                            </div>
                            <div>
                                <label for="Desc">Descrição:</label>
                                <textarea name="Desc" id="Desc" cols="30" rows="10"></textarea>
                                <div>
                                    <label for="img" translate="0" tabindex="0" id="imagem">
                                        <span class="ImagemPreview"></span>
                                    </label>
                                    <input type="file" accept="image/*" id="img" name="img">
                                </div>
                                <div>
                                    <input type="submit" value="Postar">
                                </div>
            </form>
        </section>
        <section class="Ultimas"></section>
    </main>
    <footer>

    </footer>
    <script type="module" src="../assets/js/postagem.js"></script>
</body>

</html>