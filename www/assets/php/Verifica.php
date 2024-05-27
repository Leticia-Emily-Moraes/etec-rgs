<?php
include_once('../bd/conexao.php');
function verficaSessaoOuCookie()
{
    session_start();
    if (!isset($_SESSION['userName'])) {
        if (isset($_COOKIE['username'])) {
            // O cookie ainda existe e é válido
            $userName = $_COOKIE['username'];
            $_SESSION['userName'] = $userName; // Renova a sessão
        } else {
            // Redirecionar para a página de login se não estiver autenticado
            header('Location: ../../Principal.html');
            exit;
        }
    } else {
        $userName = $_SESSION['userName'];
    }
    return $userName;
}

?>