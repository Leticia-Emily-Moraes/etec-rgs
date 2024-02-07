<?php //variaveis basicas da conexão do bd
$_servidor = "localhost";
$_usuario = "root";
$_banco =  "fecontecAdmin";
$_senha =  "";
$conexao = mysqli_connect($_servidor, $_usuario, $_senha, $_banco);

if(mysqli_connect_errno()) {
    die("Conexão Falhou: " . mysqli_connect_errno());
}


