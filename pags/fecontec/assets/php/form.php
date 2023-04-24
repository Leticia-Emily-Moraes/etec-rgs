<?php include_once("connect.php");?>
<?php include_once("../../lendoQR.html");?>

<?php
    
 $_nome= $_POST["nome"];
 $_tipoDoc= $_POST["tipoDoc"];
 $_rm= $_POST["rm"];
 $_rg= $_POST["rg"];
 $_curso= $_POST["curso"];
 $_script= $_POST "<script> var = scanner </script>";
 

$sql = "INSERT INTO convidados (nome, tipoDoc, rm, rg, cursos) VALUES ( '$_nome', '$_tipoDoc', '$_rm', '$_rg', '$_curso')";
if (mysqli_query($conexao, $sql)){
    echo "<script> alert('Dados Salvos '); window.location = 'index.php';
    </script>";
}else{
    echo "Deu erro" . $sql . "<br>" . mysqli_error($conexao);
}


mysqli_close($conexao);
?>