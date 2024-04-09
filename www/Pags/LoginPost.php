<?php
session_start();

include_once('../assets/bd/conexao.php');

// Define o tempo limite da sessão em segundos
$session_timeout = 1800; // 30 minutos

// Verifica se a sessão expirou
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $session_timeout)) {
    // Sessão expirada, destrói a sessão e redireciona para a página de login
    session_unset();
    session_destroy();
    header('Location: login.php?timeout=true');
    exit();
}

// Atualiza o tempo da última atividade na sessão
$_SESSION['last_activity'] = time();

if(isset($_SESSION['userName'])) {
    header('Location: postagem.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userName = $_POST['login'];
    $senha = $_POST['senha'];

    $query = "SELECT * FROM usuarios WHERE NomeUser = '$userName'";
    $result = mysqli_query($conexao, $query);
    $row = mysqli_fetch_assoc($result);
    $Nomebd = $row['NomeUser'];
    $Senhabd = $row['SenhaUser'];

    if ($Nomebd == $userName && $Senhabd == $senha) {
        $_SESSION['userName'] = $userName;
        header('Location: postagem.php');
        exit();
    } else {
        $erroLogin = "Usuário ou senha incorretos.";
    }

    mysqli_close($conexao);
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postagens RGS</title>
    <link rel="stylesheet" href="../assets/css/LoginPost.css">
</head>

<body>
    <main>
        <section class="login">
            <h1>Entrar para publicar</h1>
            <form method="post" action="">
                <p class="ErroLogin"></p>
                <div>
                    <label for="login">Nome de Login:</label>
                    <input type="text" name="login" id="login" placeholder="Digite seu nome de usuario" required>
                </div>
                <div>
                    <label for="senha">Senha de Login:</label>
                    <span><input type="password" name="Senha" id="senha" placeholder="Digite sua senha" required minlength="8"><a href="#"><img src="../assets/img/icons/Visivel.svg" alt=""></a></span>
                </div>
                <div>
                    <input type="submit" value="Entrar">
                </div>
            </form>
        </section>
        <section class="imagem">
            <img src="../assets/img/pandoras/raposaEtec.png" class="img-fluid animated" alt="">
        </section>
    </main>
    <script>
        const inputSenha = document.querySelector('input[type="password"]');
        const imgVisivel = document.querySelector('img');
        imgVisivel.addEventListener('click', () => {
            if (inputSenha.type === 'password') {
                inputSenha.type = 'text';
                imgVisivel.src = '../assets/img/icons/Invisivel.svg';
            } else {
                inputSenha.type = 'password';
                imgVisivel.src = '../assets/img/icons/Visivel.svg';
            }
        });
    </script>
</body>

</html>