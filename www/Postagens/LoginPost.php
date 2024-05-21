<?php

include_once('../assets/bd/conexao.php'); //Inclui o arquivo de conexão com o banco de dados

function autenticar($conexaoComBanco, $nomeDoUsuario, $Senha)
{
    $nomeDoUsuario = mysqli_real_escape_string($conexaoComBanco, $_POST['login']);
    $Senha = mysqli_real_escape_string($conexaoComBanco, $_POST['Senha']);
    $stmt = $conexaoComBanco->prepare("SELECT * FROM usuarios WHERE NomeUser = ? AND SenhaUser = ?");
    $stmt->bind_param("ss", $nomeDoUsuario, $Senha);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) // Se encontrar algum usuário
    {
        $row = $result->fetch_assoc();
        return $row['NomeUser'];
    } else {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") // Verifica se o método de form é POST
{
    $userName = $_POST['login'];
    $senha = $_POST['Senha'];

    if (autenticar($conexao, $userName, $senha)) {
        session_start();
        $_SESSION['userName'] = $userName;
        setcookie('username', $userName, time() + (1 * 24 * 60 * 60), "/"); 
        header('Location: Principal.html');
        exit();
    } else {
        $erroLogin = "Usuário ou senha incorretos.";
    }

    // Fecha a consulta
    mysqli_close($conexao); // Fecha a conexão com o banco de dados
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postagens RGS - Login</title>
    <link rel="shortcut icon" href="../assets/img/Favicons/etecRgs.ico" type="image/x-icon">
    <link rel="stylesheet" href="../assets/style/LoginPost.css">
</head>

<body>
    <main>
        <section class="login">
            <h1>Entrar para publicar</h1>
            <form method="post" action="">
                <div>
                    <label for="login">Nome de Login:</label>
                    <input type="text" name="login" id="login" placeholder="Digite seu nome de usuario" required>
                </div>
                <div>
                    <label for="Senha">Senha de Login:</label>
                    <span><input type="password" name="Senha" id="Senha" placeholder="Digite sua senha" required minlength="8"><a href="#"><img src="../assets/img/icons/Visivel.svg" alt=""></a></span>
                </div>
                <?php if (isset($erroLogin)) : ?>
                    <p class="ErroLogin"><?php echo $erroLogin; ?></p>
                <?php endif; ?>
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