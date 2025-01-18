<?php

  set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib'.PATH_SEPARATOR.'./');
  require_once "verificarCredenciales.php";
  require_once 'CalendarioAcademico.php';
  
  $calendario = new CalendarioAcademico();
 
  $arr_datos_inscripcion = $arr_datos_turno = $arr_datos_llamado = [];

  $cantidad_llamados = $inscripcion_activa = $inscripcion_asociada = $llamado1_activo = $llamado2_activo = 0;

  if (empty($arr_datos_turno)) {
     $arr_datos_turno = $calendario->getEventoActivoByCodigo(1001);
     $cantidad_llamados = 2;
  };
  if (empty($arr_datos_turno)) {
     $arr_datos_turno = $calendario->getEventoActivoByCodigo(1002);
     $cantidad_llamados = 1;
  };
  if (empty($arr_datos_turno)) {
     $arr_datos_turno = $calendario->getEventoActivoByCodigo(1003);
     $cantidad_llamados = 2;
  };
  if (empty($arr_datos_turno)) {
     $arr_datos_turno = $calendario->getEventoActivoByCodigo(1004);
     $cantidad_llamados = 1;
  };
  
  $turno_id = isset($arr_datos_turno[0]["id"])?$arr_datos_turno[0]["id"]:0;
  
  // esteeee esta mal 
  //die('ddddd');
  $arr_ultima_inscripcion = $calendario->getLastInscripcionExamen();
  
  //var_dump($arr_ultima_inscripcion);exit;
  if ($arr_ultima_inscripcion[0]["codigo"]==1005) {
     $inscripcion_activa = $arr_ultima_inscripcion[0]["id"];
     $inscripcion_asociada = $inscripcion_activa;
  } else if ($arr_ultima_inscripcion[0]["codigo"]==1006) {
     $inscripcion_activa = $arr_ultima_inscripcion[0]["id"];
     $inscripcion_asociada = $inscripcion_activa;
  } else if ($arr_ultima_inscripcion[0]["codigo"]==1007) {
     $inscripcion_activa = $arr_ultima_inscripcion[0]["id"];
     $inscripcion_asociada = $inscripcion_activa;
  } else if ($arr_ultima_inscripcion[0]["codigo"]==1008) {
     $inscripcion_activa = $arr_ultima_inscripcion[0]["id"];
     $inscripcion_asociada = $inscripcion_activa;
  } else if ($arr_ultima_inscripcion[0]["codigo"]==1009) {
     $inscripcion_activa = $arr_ultima_inscripcion[0]["id"];
     $inscripcion_asociada = 1005; // sacar el id
  } else if ($arr_ultima_inscripcion[0]["codigo"]==1010) {
     $inscripcion_activa = $arr_ultima_inscripcion[0]["id"];
     $inscripcion_asociada = 1007; // sacar el id
  };

  $id_pagina = 'finales';
  //var_dump($arr_ultima_inscripcion);exit;
  $fecha_actual = strtotime(date("d-m-Y H:i:00",time()));
  $fecha_inicio = !is_null($arr_datos_turno[0]['fecha_inicio'])?strtotime($arr_datos_turno[0]['fecha_inicio']):NULL;
  $fecha_final = !is_null($arr_datos_turno[0]['fecha_final'])?strtotime($arr_datos_turno[0]['fecha_final']):NULL; 
  
  $habilitar_listados_materia = FALSE;
  if ($fecha_actual>=$fecha_inicio || $fecha_actual<=$fecha_final) {
     $habilitar_listados_materia = TRUE;
  }
  

  // *********  SACAR LOS PERIODOS CORRESPONDIENTES A LOS LLAMADOS *************
  if ($cantidad_llamados==2) {
      if (empty($arr_datos_llamado)) {
         $arr_datos_llamado[1020] = $calendario->getEventoActivoByCodigo(1020);
         $arr_datos_llamado[1021] = $calendario->getEventoActivoByCodigo(1021);
      };
  } else if ($cantidad_llamados==1) {
      if (empty($arr_datos_llamado)) {
         $arr_datos_llamado[1020] = $calendario->getEventoActivoByCodigo(1020);
      };
  }


  if (isset($arr_datos_llamado[1020]) && !empty($arr_datos_llamado[1020])) {
     $llamado1_activo = TRUE;
  };

  if (isset($arr_datos_llamado[1021]) && !empty($arr_datos_llamado[1021])) {
     $llamado2_activo = TRUE;
   //die('entro 2222');
 };


  // ****************************************************************************

  
   


  






  //echo $fecha_final;exit;



?>

<!doctype html>
<html lang="es">
  <head>
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
                  <li class="breadcrumb-item"><a href="#" onclick="cargarHome()">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Carreras</li>
                </ol>
      </nav>

      <h1><i>&nbsp;Carreras</i></h1>
      <h3>&nbsp;Gestión de los Exámenes Finales de Alumnos</h3>

    </div>
  </article>

  <article class="container-fluid">
    <div id="control_habilitado" style='display:none'></div>
    <div id="cargar_regularidades_habilitado" style='display:none'></div>
    <div id="titulo"></div>
    <hr>
  </article>

  <article class="container-fluid <?php ($inscripcion_activa=='0')?'d-none':'';?>">
       <section>
           <div class="row">

                  <h3>&nbsp;&nbsp;Inscripcion Activa: <?=$inscripcion_activa;?></h3>

           </div><!-- Cierra Row-->
        </section>
  </article>

  <article class="container-fluid">
       <section>
           <div class="row" id="resultado">

                  <div class="col-md-4">
                        <div class="card" style="width: 18rem;">
                                  <img src="../public/img/logo_n.jpg" class="card-img-top">
                                  <div class="card-body">
                                      <h5 class="card-title"><img src="../public/img/icons/add2_icon.png" width="23">&nbsp;Nueva vinculación a Carrera</h5>
                                          <h6 class="card-subtitle mb-2 text-muted"></h6>
                                      <p class="card-text"></p>
                                      <button class="btn btn-primary btn-block" onclick="vincularCarrera(`+idProfesor+`)">Vincularme</button>
                                  </div>
                        </div>
                  </div>


           </div><!-- Cierra Row-->
           <div class="row" id="controles"></div><!-- Cierra Row-->
        </section>
  </article>

<!-- FOOTER -->
<?php
      include_once('../app/views/footer.html');
  ?>

<!-- JAVASCRIPT LIBRARIES-->
<?php include("../app/views/script_jquery.html");?>

<!-- JAVASCRIPT CUSTOM -->
<script>
let carrera_nombre = '';
let carrera_id = '';
var profesor_id = '<?=$_SESSION['id_profesor'];?>';
let habilitar_listados_materia = <?=$habilitar_listados_materia;?>;
let inscripcion_activa = <?=$inscripcion_activa;?>;
let turno_activo = <?=$llamado1_activo;?>;
let llamado1_activo = <?=$llamado1_activo;?>;
let llamado2_activo = <?=$llamado2_activo;?>;
var materia_nombre = '';
var materia_id = '';
var opcion = 'Examenes';
let llamado_numero = '';
function expired() {
  //location.href = "./logout.php";
}



$(function () {
    /*$.get("./html/modalEliminar.html",function(data){
      $("#modalEliminar").html(data);
    })*/
    cargarCarreras(profesor_id);
});


//setTimeout(expired, 60000*20);

</script>
<script src="./js/gestionarFinales.js"></script>
<script src="./js/funciones.js"></script>
</body>
</html>
