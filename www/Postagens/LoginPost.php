<?php
session_start();

include_once('../assets/bd/conexao.php'); //Inclui o arquivo de conexão com o banco de dados

$session_timeout = 3600; // Tempo para fazer login novamente (depois de uma hora)

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $session_timeout)) { //Verifica a ultima vez que foi acessado e faz uma comparação com o tempo limite
    session_unset(); //Limpa todas as sessoes 
    session_destroy(); //Destroi a sessão
    header('Location: LoginPost.php?timeout=true'); //Redireciona para a página de login com um parâmetro de timeout, para fazer o login novamente
    exit(); // sai do if
}


$_SESSION['last_activity'] = time(); //Atualiza a ultima atividade da sessão

if ($_SERVER["REQUEST_METHOD"] == "POST") //Verifica se o método de form é POST
{
    $userName = mysqli_real_escape_string($conexao, $_POST['login']); 
    $senha = mysqli_real_escape_string($conexao, $_POST['Senha']);
    // Protege contra SQL Injection: Escape de Caracteres especiais
    $query = "SELECT * FROM usuarios WHERE NomeUser = ? AND SenhaUser = ?"; // define a consulta no banco de dados
    $stmt = mysqli_prepare($conexao, $query); // conecta no banco e roda a consulta definida acima
    mysqli_stmt_bind_param($stmt, "ss", $userName, $senha); // passa os parametros para a consulta
    mysqli_stmt_execute($stmt); // executa a consulta
    $result = mysqli_stmt_get_result($stmt); // pega o resultado da consulta

    if ($row = mysqli_fetch_assoc($result)) // Se encontrar algum usuário
    {
        $_SESSION['userName'] = $userName; // Cria uma sessão com o nome do usuário
        $_SESSION['expire_time'] = time() + $session_timeout; // Define o tempo de expiração da sessão
        header('Location: Principal.php');
        exit();
    } else {
        $erroLogin = "Usuário ou senha incorretos.";
    }

    mysqli_stmt_close($stmt); // fecha a consulta
    mysqli_close($conexao); // fecha a conexão com o banco de dados
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
                <?php if (isset($erroLogin)) : ?>
                    <p class="ErroLogin"><?php echo $erroLogin; ?></p>
                <?php endif; ?>
                <div>
                    <label for="login">Nome de Login:</label>
                    <input type="text" name="login" id="login" placeholder="Digite seu nome de usuario" required>
                </div>
                <div>
                    <label for="Senha">Senha de Login:</label>
                    <span><input type="password" name="Senha" id="Senha" placeholder="Digite sua senha" required minlength="8"><a href="#"><img src="../assets/img/icons/Visivel.svg" alt=""></a></span>
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