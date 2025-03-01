<?php
   set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib'.PATH_SEPARATOR.'./');
   include_once "verificarCredenciales.php";
   require_once "Profesor.php";
   require_once "CalendarioAcademico.php";



   $id_pagina = 'home';
   $_SESSION['id_persona'] = $_SESSION['arreglo_datos_usuario']['idPersona'];
   $objProfesor = new Profesor();
   $_SESSION['id_profesor'] = $objProfesor->getProfesorByIdPersona($_SESSION['id_persona'])['id'];

   // Determina si el Evento Armado de Lista de Materias de un Cuatrimestre dado esta habilitado en la fecha de hoy.
   $cal = new CalendarioAcademico;
   $_SESSION['ARRAY_CODIGOS_EVENTOS_ARMADO_LISTADOS_MATERIAS_ACTIVOS'] = $cal->getEventosArmadoMateriasActivo();
   
  // var_dump($_SESSION['ARRAY_CODIGOS_EVENTOS_ARMADO_LISTADOS_MATERIAS_ACTIVOS']);exit;
  

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

  <article class="container-fluid">
    <div id="control_habilitado" style='display:none'></div>
    <div id="cargar_regularidades_habilitado" style='display:none'></div>
    <div id="titulo"></div>
    <hr>
  </article>

  <article class="container">
       <section>
           <div class="row" id="resultado"></div><!-- Cierra Row-->
           <div class="row" id="controles"></div><!-- Cierra Row-->
        </section>
  </article>

<!-- FOOTER -->
<?php include_once('../app/views/footer.html');?>

<!-- JAVASCRIPT LIBRARIES-->
<?php include("../app/views/script_jquery.html");?>

<!-- JAVASCRIPT CUSTOM -->
<script>
let carrera_nombre = '';
let carrera_id = '';
var profesor_id = '';
var materia_nombre = '';
var materia_id = '';
var opcion = '';
let llamado_numero = '';
function expired() {
  location.href = "./logout.php";
}

//setTimeout(expired, 60000*20);
</script>
<script src="./js/loadHome.js"></script>
</body>
</html>
