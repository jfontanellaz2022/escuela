<?php 
require_once('seguridad.php');
?>
<!doctype html>
<html lang="es">
  <head>
  <link rel="shortcut icon" href="../assets/img/favicon.png">
   <?php include_once('componente_header.html'); ?>
</head>


<body>
  <!-- NAVBAR -->
  <header>
    <?php include("componente_navbar.php"); ?>
  </header>

  <article>
    <div id="breadcrumb">
      <nav aria-label="breadcrumb" role="navigation">
          <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page">/ Home</li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="container">
    <div id="titulo"></div>
    <hr>
  </article>

  <article class="container">
       <section>
           <div class="row" id="resultado"></div><!-- Cierra Row-->
        </section>
  </article>

<!-- FOOTER -->
<?php include("componente_footer.html"); ?>

<!-- JAVASCRIPT LIBRARIES-->
<?php include("componente_script_jquery.html"); ?>

<!-- JAVASCRIPT CUSTOM -->

</body>
</html>
