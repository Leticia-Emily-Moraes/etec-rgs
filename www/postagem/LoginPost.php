<?php
session_start();

include_once('../assets/bd/conexao.php');

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
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <?php if (isset($erroLogin)) { ?>
                    <p class="ErroLogin"><?php echo $erroLogin; ?></p>
                <?php } ?>
                <div>
                    <label for="login">Nome de Login:</label>
                    <input type="text" name="login" id="login" required>
                </div>
                <div>
                    <label for="senha">Senha de Login:</label>
                    <input type="password" name="senha" id="senha" required>
                </div>
                <div>
                    <input type="submit" value="Entrar">
                </div>
            </form>
        </section>
        <section class="imagem">
            <img src="../assets/img/pandoras/raposa etec 2.png" class="img-fluid animated" alt="">
        </section>
    </main>
</body>

</html>