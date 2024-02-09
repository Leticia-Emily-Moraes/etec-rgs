<?php
// Configurações de conexão com o banco de dados
$servidor = "localhost";
$usuario = "root";
$senha = "";
$bancoDeDados = "SiteEtec";

// Criando uma conexão com o banco de dados
$conexao = new mysqli($servidor, $usuario, $senha, $bancoDeDados);

// Verificando se houve erro na conexão
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}
?>
