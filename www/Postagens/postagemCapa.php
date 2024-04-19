<?php
session_start();

include_once('../assets/bd/conexao.php');

if (isset($_SESSION['userName'])) {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validação do formulário
        if (empty($_POST['Titulo']) || empty($_POST['Resumo']) || empty($_POST['Temas']) || empty($_FILES["img"]["name"])) {
            echo "Por favor, preencha todos os campos.";
            exit();
        }

        $titulo = $_POST['Titulo'];
        $Resumo = $_POST['Resumo'];
        $Tema = $_POST['Temas'];
        $userName = $_SESSION['userName'];

        $query = "SELECT IdUsuario FROM usuarios WHERE NomeUser = ?";
        $stmt = mysqli_prepare($conexao, $query);
        mysqli_stmt_bind_param($stmt, "s", $userName);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $autorID = $row['IdUsuario'];

            // Verifica o tamanho do arquivo
            if ($_FILES["img"]["size"] > 5000000) {
                echo "Desculpe, o arquivo é muito grande.";
                exit();
            }

            $target_dir = "../assets/img/Noticias/";
            $target_file = $target_dir . basename($_FILES["img"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Verifica se o arquivo já existe
            if (file_exists($target_file)) {
                echo "Desculpe, o arquivo já existe.";
                exit();
            }

            // Permitir apenas alguns formatos de arquivo
            $allowedFormats = array("jpg", "jpeg", "png", "gif");
            if (!in_array($imageFileType, $allowedFormats)) {
                echo "Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
                exit();
            }
            $folder_path = $target_dir . $titulo; // Remova a barra no final
            if (!file_exists($folder_path)) {
                mkdir($folder_path, 0777, true); // Cria a pasta recursivamente
            }

            // Define o caminho completo do novo arquivo (dentro da pasta)
            $target_file = $folder_path . '/' . basename($_FILES["img"]["name"]); // Corrigido
            $newFileName = $titulo . "_capa." . $imageFileType;
            $newFilePath = $folder_path . '/' . $newFileName; // Corrigido
            $target_file =  $newFilePath;
            // Tenta fazer o upload do arquivo
            if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) { // Corrigido

                $query = "INSERT INTO noticias (Titulo, Resumo, Categoria, ImagemCapa, Autor) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conexao, $query);
                mysqli_stmt_bind_param($stmt, "sssss", $titulo, $Resumo, $Tema, $target_file, $autorID);
                mysqli_stmt_execute($stmt);

                header("Location: postagem.php?success=true");
                exit();
            } else {
                echo "Desculpe, ocorreu um erro ao enviar seu arquivo.";
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
            <h2>Capa da Noticia</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <div>
                    <label for="Titulo">Titulo:</label>
                    <input type="text" name="Titulo" id="Titulo" maxlength="30" placeholder="Titulo da Notícia" required>
                </div>
                <div>
                    <label for="Resumo">Resumo:</label>
                    <textarea name="Resumo" id="Resumo" cols="20" rows="5" placeholder="Seu resumo da notícia com no máximo 100 caracteres" maxlength="100" required></textarea>
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
                        <option value="Direção">Direção</option>
                        <option value="Grêmio">Grêmio</option>
                        <option value="Biblioteca">Biblioteca</option>
                        <option value="Esportes">Esportes</option>
                    </select>
                </div>
                <div>
                    <input type="submit" value="Próximo">
                </div>
            </form>
        </section>
    </main>
    <script src="../assets/js/postagem.js"></script>
    <script src="../assets/js/pattern.js"></script>
</body>

</html>