<?php
// logout.php
session_start();
session_unset();
session_destroy();

// Limpa o cookie
setcookie('username', '', time() - 3600, "/");

// Redirecionar para a página de login
header('Location: LoginPost.php');
exit;
?>
