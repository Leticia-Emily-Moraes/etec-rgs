<?php

$servidor = "mysql.etecrgs.com.br";
$usuario = "etecrgs";
$senha = "Inside2023";
$dbnome = "etecrgs";
$conecta = mysqli_connect( $servidor, $usuario, $senha, $dbnome);

if (!$conecta){
    die ("Conexao falhou: " .mysqli_connect_errno());
} 

