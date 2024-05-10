<?php

session_start();
include_once('../assets/bd/conexao.php');

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
    <link rel="stylesheet" href="../assets/style/postagemConteudo.css">


</head>

<body>
    <header id="header">
        <a href="#"><img class="logo" src="../assets/img/icons/iconsNav/etecRgs.png" alt=""></a>
        <img id="menu" src="../assets/img/icons/iconsNav/Menu.svg" alt="">
        <img id="menuClose" src="../assets/img/icons/iconsNav/CloseMenu.svg" alt="">
        <nav id="navPrincipal">
            <ul>
                <li><a href="#">Início</a></li>
                <li><a href="#">Postar Noticia</a></li>
                <li><a href="#">Ver Postagens</a></li>
                <li><a href="#">Autores</a></li>
                <li><a href="#">Logout<img src="../assets/img/icons/IconLogout.svg" alt=""></a></li>
            </ul>
        </nav>
        <!-- Final da Navbar -->
    </header>
    <main>
        <section class="cadastraPostagem">
            <h2>Conteúdo da Noticia</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <div>
                    <label for="Titulo">Texto:</label>
                    <textarea name="Desc" id="Desc" cols="20" rows="5" placeholder="Texto da sua Noticia" required></textarea>
                    <button type="button">Adicionar Texto</button>
                </div>
                <div>
                    <label for="img">Imagens:</label>
                    <label for="img" translate="0" tabindex="0" id="imagem">
                        <span class="ImagemPreview"></span>
                    </label>
                    <input type="file" accept="image/*" id="img" name="img">
                    <button type="button">Adicionar imagem</button>
                </div>
            </form>
        </section>
        <section>
            <h2>Preview</h2>
            <article class="PreviewPost">

            </article>
            <article class="Previewbutton">
                <button>Limpar</button>
                <button>Limpar tudo</button>
                <button>Publicar</button>
            </article>
        </section>
    </main>
    <script src="../assets/js/postagem.js"></script>
    <script src="../assets/js/pattern.js"></script>
</body>

</html>