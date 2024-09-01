<?php
session_start();
$id_url = "menu_home";
?>

<!doctype html>
<html lang="es">
<head>
  <title>Escuela Normal Superior 40 - Gestion de Alumnado</title>
  <link rel="shortcut icon" href="../../public/img/favicon.png">
  
  <?php include_once('../app/views/header.html');?>
  
</head>


<body>
   <!-- NAVBAR -->
   <header>
    <?php include("navbar.php");?>
  </header>
  
  <article>
    <div id="breadcrumb">
      <nav aria-label="breadcrumb" role="navigation">
          <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page">Home</li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="container">
    <div id="control_habilitado" style='display:none'></div>
    <div id="cargar_regularidades_habilitado" style='display:none'></div>
    <div id="titulo"></div>
    <hr>
  </article>

  <article class="container">
       <section>
            <div class="row" id="filtro"></div><!-- Cierra Row-->
            <div class="row" id="resultado">
				<div class="col-xs-12 col-sm-12 col-md-3">&nbsp;</div>
				<div class="col-xs-12 col-sm-12 col-md-6">
				    <img src="../public/img/esc40a.jpg">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-3"></div>
			</div><!-- Cierra Row-->
            <div class="row" id="resultado_accion">&nbsp;</div><!-- Cierra Row-->
        </section>
  </article>


  <article class="container">
       <section>
           <div class="row" id="resultado"></div><!-- Cierra Row-->
           <div class="row" id="controles"></div><!-- Cierra Row-->
        </section>
  </article>

<!-- FOOTER -->
<?php include("../app/views/footer.html");?>

<!-- JAVASCRIPT LIBRARIES-->
<?php include("../app/views/script_jquery.html");?>

<!-- JAVASCRIPT CUSTOM -->
<script>

function expired() {
  //location.href = "./logout.php";
}

setTimeout(expired, 60000*20);
</script>

</script>
<script src="./js/cargarCarrerasExamenes.js"></script>

</body>
</html>
