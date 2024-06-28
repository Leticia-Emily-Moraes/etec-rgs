<?php

include_once('assets/bd/conexao.php');

function autenticar($conexaoComBanco, $nomeDoUsuario, $senha)
{
    $stmt = $conexaoComBanco->prepare("SELECT * FROM usuarios WHERE NomeUser = ?");
    $stmt->bind_param("s", $nomeDoUsuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1)
    {
        $row = $result->fetch_assoc();
        if (password_verify($senha, $row['SenhaUser'])) {
            return $row['NomeUser'];
        }
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $userName = $_POST['login'];
    $senha = $_POST['Senha'];
    $userName = mysqli_real_escape_string($conexao, $userName);
    $senha = mysqli_real_escape_string($conexao, $senha);

    if (autenticar($conexao, $userName, $senha)) {
        session_start();
        $_SESSION['userName'] = $userName;

        $cookieParams = session_get_cookie_params();
        setcookie(
            'username',
            $userName,
            time() + (1 * 24 * 60 * 60),
            $cookieParams['path'],
            $cookieParams['domain'],
            true,
            true 
        );

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
    <link rel="shortcut icon" href="assets/img/Favicons/etecRgs.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/style/LoginPost.css">
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
                    <span><input type="password" name="Senha" id="Senha" placeholder="Digite sua senha" required minlength="8"><a href="#"><img src="assets/img/icons/Visivel.svg" alt=""></a></span>
                </div>
                <?php if (isset($erroLogin)) : ?>
                    <p class="ErroLogin"><?php echo $erroLogin; ?></p>
                <?php endif; ?>
                <div>
                    <input id="submit" type="submit" value="Entrar">
                </div>
            </form>
        </section>
        <section class="imagem">
            <img src="assets/img/pandoras/raposaEtec.png" class="img-fluid animated" alt="">
        </section>
    </main>
    <script src="assets/js/LoginPost.js"></script>
</body>

</html>