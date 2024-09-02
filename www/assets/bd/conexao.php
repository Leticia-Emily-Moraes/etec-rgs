<?php
// Configurações de conexão com o banco de dados
$servidor = "mysql.etecrgs.com.br";
$usuario = "etecrgs_add1";
$senha = "Etecrgs2024";
$bancoDeDados = "etecrgs";

// Criando uma conexão com o banco de dados
$conexao = new mysqli($servidor, $usuario, $senha, $bancoDeDados);

// Verificando se houve erro na conexão
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

mysqli_set_charset($conexao, "utf8");

?>
