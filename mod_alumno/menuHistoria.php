<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "verificarCredenciales.php";
require_once "Alumno.php";

$id_url = "menu_historia";
//echo $_SESSION['idAlumno'];die;
//var_dump($_SESSION['arreglo_datos_usuario']);die;
$persona_id = $_SESSION['arreglo_datos_usuario']['idPersona'];
$alumno = new Alumno();
$_SESSION['idAlumno'] = $alumno->getAlumnoByIdPersona($persona_id)['id'];

//var_dump($_SESSION['idAlumno']);exit;



//$_SESSION['arreglo_credenciales_usuario'] = $objUsuario->getCredencialesByIdPersona($idPersona);
?>

<!doctype html>
<html lang="es">
<head>
<link rel="shortcut icon" href="../public/img/favicon.png">
  <?php
      include_once('../app/views/header.html');
    ?>
 
  
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
       <div id="grafica" class="col-xs-12 col-sm-6 col-lg-4 d-none">
        <div class="card">
                <div class="card-header bg-info">
                    <h4 class="title-2 m-b-40">Avance en la carrera</h4>
                </div>
                <div class="card-body">
                  <canvas id="avance" ></canvas>
                </div>
            </div>
        </div>
           </div><!-- Cierra Row-->
           <div class="row" id="resultado"></div><!-- Cierra Row-->
           <div class="row" id="controles"></div><!-- Cierra Row-->
        </section>
  </article>

<!-- FOOTER -->
<?php include("../app/views/footer.html");?>

<!-- JAVASCRIPT LIBRARIES-->
<?php include("../app/views/script_jquery.html");?>
 <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- JAVASCRIPT CUSTOM -->
<script>
$("document").ready(function() {
   cargarCarrerasHistoria(<?=$_SESSION['idAlumno'];?>);
})

function expired() {
  location.href = "../logout.php";
}

setTimeout(expired, 60000*20);

</script>

<script src="./js/cargarCarrerasHistoria.js"></script>

</body>
</html>
