<?php
session_start();
include_once('../bd/conexao.php');
include_once('Verifica.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $NomeCompleto = trim($_POST['NomeCompleto']);
    $NomeUser = trim($_POST['NomeUser']);
    $SenhaUser = trim($_POST['SenhaUser']);
    $Cargo = trim($_POST['Cargo']);
    $ImagemAutor = $_FILES['img']['name'];
    $ImagemTmp = $_FILES['img']['tmp_name'];
    $ImagemSize = $_FILES['img']['size'];

    // Validação dos campos
    if (empty($NomeCompleto) || empty($NomeUser) || empty($SenhaUser) || empty($Cargo) || empty($ImagemAutor)) {
        echo "Por favor, preencha todos os campos.";
        exit;
    }

    // Validação do tamanho do arquivo
    if ($ImagemSize > 5 * 1024 * 1024) {
        echo "Desculpe, o arquivo é muito grande (limite de 5MB).";
        exit;
    }

    $diretorioDestinoDataBase = "assets/img/equipe/" . basename($ImagemAutor);
    $diretorioDestino = "../img/equipe/" . basename($ImagemAutor);

    if (move_uploaded_file($ImagemTmp, $diretorioDestino)) {

        // Hashing da senha
        $SenhaHashed = password_hash($SenhaUser, PASSWORD_BCRYPT);

        $sql = "INSERT INTO usuarios (NomeCompleto, NomeUser, SenhaUser, Cargo, ImagemAutor) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $conexao->prepare($sql)) {
            $stmt->bind_param("sssss", $NomeCompleto, $NomeUser, $SenhaHashed, $Cargo, $diretorioDestinoDataBase);
            if ($stmt->execute()) {
                $sql_insert = "INSERT INTO usuarios ( NomeCompleto, NomeUser, SenhaUser, Cargo, ImagemAutor) VALUES ( '$NomeCompleto', '$NomeUser', '$SenhaHashed', '$Cargo', '$diretorioDestinoDataBase');";
                $arquivo_sql = fopen("../bd/BaseUsers.sql", "a");
                fwrite($arquivo_sql, $sql_insert . "\n");
                fclose($arquivo_sql);
                echo "Novo usuário adicionado com sucesso!";
                header("../../autores.html");
            } else {
                echo "Erro ao inserir os dados.";
            }
            $stmt->close();
        } else {
            echo "Erro ao preparar a declaração SQL.";
        }
    } else {
        echo "Falha ao mover o arquivo para o diretório de destino.";
    }
}

$conexao->close();
?>
