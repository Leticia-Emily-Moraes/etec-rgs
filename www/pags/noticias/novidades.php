<?php require_once("../../assets/bd/conexao.php");?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Etec RGS</title>

  <!-- Favicons -->
  <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icons/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/img/icons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/img/icons/favicon-16x16.png">
  <link rel="manifest" href="assets/img/icons/site.webmanifest">

  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"rel="stylesheet">

  <!-- Vendor CSS Arquivos -->
  <link href="../../assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../../assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="../../assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Arquivo CSS -->
  <link href="../../assets/css/style.css" rel="stylesheet">
</head>

<body>

  <!-- ======= Cabeçalho======= -->
  <header id="header" class="fixed-top ">
    <div id="containerNav" class="container d-flex align-items-center justify-content-evenly">
      <img class="logo" src="../../assets/img/icons/etecRgs.png" alt="">
    
      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="../../index.html">Início</a></li>
          <li class="dropdown"><a href="#hero"><span>Nossa Etec</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="../historiaetec.html">Sobre Nós</a></li>
              <li class="dropdown"><a href="#"><span>Documentação</span> <i class="bi bi-chevron-right"></i></a>
                <ul>
                  <li><a href="../../assets/planos/horarios.pdf">Horários</a></li>
                  <li><a href="../../assets/planos/etec-regimento-comum-2022.pdf">Regimento Comum das Etecs</a></li>
                  <li><a href="../../assets/planos/calendario.pdf">Calendário Anual</a></li>
                </ul>
              </li>
            </ul>
          </li>
         
          <li><a class="nav-link scrollto" href="../faq.html">FAQ</a></li>
          <img src="../../assets/img/icons/sol.png" id="icon">
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- Final da Navbar -->
    </div>

  </header><!-- Final do Cabeç
  alho -->

<main>  

  <center>
  <div class="pandora-notici"> <img style="position: relative; top:120px; animation: up-down 2s ease-in-out infinite alternate-reverse both;" 
  src="../../assets/img/pandoras/raposa gtec.png" alt="">  
  </div>
  </center>
 
  <!---<h1 class="notice-h1">últimas Notícias</h1>-->
         
       
</main>

<?php


$noticias = "SELECT * FROM noticias";
$_noticias = mysqli_query($conecta, $noticias);
if (!$_noticias) {
    die("Falha na consulta ao banco de dados");
}
?>

 <?php

    $_i = 0;
    while ($registro = mysqli_fetch_array($_noticias)) {
    $registro["conteudo"] = mb_convert_encoding($registro["conteudo"], 'UTF-8', 'ISO-8859-1');
    $registro["titulo"] = mb_convert_encoding($registro["titulo"], 'UTF-8', 'ISO-8859-1');
    ?>
    <center><div class="titulo" ><h3> <?php echo  $registro["titulo"] ?></h3></div>
      <div class="card-nov">
      <img src="<?php echo $registro["imagem"] ?>">
      <p> <?php echo  $registro["conteudo"]?></p>
      </div>
      </center>
  <?php
    }
  ?>
   

 
 

 <!-- ======= Rodapé ======= -->
 <footer id="footer">

<div class="footer-top">
  <div class="container">
    <div class="row">

      <div class="col-lg-3 col-md-6 footer-contact">
        <h2>ETEC</h2>

        <p>
          Av. Francisco Morais Ramos, 777 <br>
          Jardim Novo Horizonte<br>
          Rio Grande da Serra - SP <br><br>

          <strong>Telefone:</strong> (11) 4826-83255<br>
          <strong>Email:</strong> e282acad@cps.sp.gov.br<br>
        </p>
      </div>
    </div>
  </div>
</div>
</footer><!-- Final da seção Rodapé-->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
  class="bi bi-arrow-up-short"></i></a>
<div id="preloader"></div>

<!-- Vendor JS Arquivos -->
<script src="../../assets/vendor/purecounter/purecounter.js"></script>
<script src="../../assets/vendor/aos/aos.js"></script>
<script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="../../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="../../assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="../../assets/vendor/php-email-form/validate.js"></script>

<!--  Main JS Arquivos -->
<script src="../../assets/js/main.js"></script>

<script>
var icon = document.getElementById("icon")

icon.onclick = function () {
  document.body.classList.toggle("dark-theme");
  if (document.body.classList.contains("dark-theme")) {
    icon.src = "../../assets/img/icons/sol.png";
  } else {
    icon.src = "../../assets/img/icons/lua.png";
  }
}

function setTheme(newTheme) {
    const themeColors = themes[newTheme]; // Seleciona o tema para aplicar

      Object.keys(themeColors).map(function(key) {
      html.style.setProperty(`--${key}`, themeColors[key]); // Altera as variáveis no css
    });

    localStorage.setItem('dark-theme', newTheme); //Salva o tema escolhido no localStorage
    }

    const theme = localStorage.getItem('dark-theme');
    if( theme ) {
       setTheme(theme)
    }

</script>

</body>
</html>							