<?php

set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib'.PATH_SEPARATOR.'./');
require_once "verificarCredenciales.php";
require_once 'CalendarioAcademico.php';
require_once 'Constantes.php';
  
$calendario = new CalendarioAcademico();
 
$arr_datos_inscripcion = $arr_datos_turno = $arr_datos_llamado = [];
$cantidad_llamados = $inscripcion_activa = $inscripcion_asociada = $llamado1_activo = $llamado2_activo = 0;
$disabledVerCarreras = $disabledVerLlamado1 = $disabledVerMateriasLlamado1 = $disabledVerAlumnosLlamado1 = $disabledPonerNotasAlumnosLlamado1 = "";
$disabledVerLlamado2 = $disabledVerMateriasLlamado2 = $disabledVerAlumnosLlamado2 = $disabledPonerNotasAlumnosLlamado2 = "";

$arr_datos_turno = $calendario->getLastTurnoExamen();
//var_dump($arr_datos_turno);exit;
$fecha_actual = strtotime(date("d-m-Y H:i:00",time()));
$fecha_inicio = !is_null($arr_datos_turno['fecha_inicio'])?strtotime($arr_datos_turno['fecha_inicio']):NULL;
$fecha_final = !is_null($arr_datos_turno['fecha_final'])?strtotime($arr_datos_turno['fecha_final']):NULL; 

// SI EXISTE TURNO ACTIVO
if ($fecha_actual>=$fecha_inicio && $fecha_actual<=$fecha_final) {
    // DETERMINAR LA CANTIDAD DE LLAMADOS EN FUNCION DEL TURNO (1er,2do,3er,Mesa Especial)
    if ($arr_datos_turno['codigo']==Constantes::CODIGO_PRIMER_TURNO) {
      $cantidad_llamados = 2;
    } else if ($arr_datos_turno['codigo']==Constantes::CODIGO_SEGUNDO_TURNO) {
        $cantidad_llamados = 1;
    } else if ($arr_datos_turno['codigo']==Constantes::CODIGO_TERCER_TURNO) {
        $cantidad_llamados = 2;
    } else if ($arr_datos_turno['codigo']==Constantes::CODIGO_MESA_ESPECIAL_TURNO) {
        $cantidad_llamados = 1;
    }

    $_SESSION['turno_id'] = $arr_datos_turno['id'];

    //var_dump($_SESSION['turno_id'],$arr_datos_turno);exit;

    $disabledVerCarreras = $disabledVerMaterias = $disabledVerAlumnos = "";
    $disabledPonerNotasAlumnos = "disabledbutton";

    // *********  SACAR LOS PERIODOS CORRESPONDIENTES A LOS LLAMADOS *************
    if ($cantidad_llamados==2) {
      if (empty($arr_datos_llamado)) {
        //var_dump('entroooo',$calendario->getEventoActivoByCodigo(Constantes::CODIGO_SEGUNDO_LLAMADO));exit;
        $arr_datos_llamado[Constantes::CODIGO_PRIMER_LLAMADO] = $calendario->getEventoActivoByCodigo(Constantes::CODIGO_PRIMER_LLAMADO);
        $arr_datos_llamado[Constantes::CODIGO_SEGUNDO_LLAMADO] = $calendario->getEventoActivoByCodigo(Constantes::CODIGO_SEGUNDO_LLAMADO);
      };
    } else if ($cantidad_llamados==1) {
        if (empty($arr_datos_llamado)) {
          $arr_datos_llamado[Constantes::CODIGO_PRIMER_LLAMADO] = $calendario->getEventoActivoByCodigo(Constantes::CODIGO_PRIMER_LLAMADO);
        };
    }

    // DETERMINAR SI HAY LLAMADO1_ACTIVO - HABILITAR EDICION_ALUMNO_PONER_NOTAS
    if (isset($arr_datos_llamado[Constantes::CODIGO_PRIMER_LLAMADO]) && !empty($arr_datos_llamado[Constantes::CODIGO_PRIMER_LLAMADO])) {
      $llamado1_activo = TRUE;
      $disabledPonerNotasAlumnosLlamado1 = "";
    } else {
      $disabledPonerNotasAlumnosLlamado1 = "disabledbutton";
    }

    // DETERMINAR SI HAY LLAMADO2_ACTIVO - HABILITAR EDICION_ALUMNO_PONER_NOTAS

    if (isset($arr_datos_llamado[Constantes::CODIGO_SEGUNDO_LLAMADO]) && !empty($arr_datos_llamado[Constantes::CODIGO_SEGUNDO_LLAMADO])) {
      $llamado2_activo = TRUE;
      $disabledPonerNotasAlumnosLlamado2 = "";
    } else {
      $disabledPonerNotasAlumnosLlamado2 = "disabledbutton";
    }






} else {
   $disabledVerCarreras = $disabledVerLlamado1 = $disabledVerMateriasLlamado1 = $disabledVerAlumnosLlamado1 = $disabledPonerNotasAlumnosLlamado1  = "disabledbutton";
   $disabledVerLlamado2 = $disabledVerMateriasLlamado2 = $disabledVerAlumnosLlamado2 = $disabledPonerNotasAlumnosLlamado2  = "disabledbutton";

}

  $arr_ultima_inscripcion = $calendario->getLastInscripcionExamen();

  $inscripcion_activa = 0;
  $inscripcion_asociada = 0;
  if ($arr_ultima_inscripcion["codigo"]==Constantes::CODIGO_INSCRIPCION_PRIMER_TURNO) {
     $inscripcion_activa = $arr_ultima_inscripcion["id"];
     //$inscripcion_asociada = $inscripcion_activa;
  } else if ($arr_ultima_inscripcion["codigo"]==Constantes::CODIGO_INSCRIPCION_SEGUNDO_TURNO) {
     $inscripcion_activa = $arr_ultima_inscripcion["id"];
     //$inscripcion_asociada = $inscripcion_activa;
  } else if ($arr_ultima_inscripcion["codigo"]==Constantes::CODIGO_INSCRIPCION_TERCER_TURNO) {
     $inscripcion_activa = $arr_ultima_inscripcion["id"];
     //$inscripcion_asociada = $inscripcion_activa;
  } else if ($arr_ultima_inscripcion["codigo"]==Constantes::CODIGO_INSCRIPCION_MESA_ESPECIAL) {
     $inscripcion_activa = $arr_ultima_inscripcion["id"];
     //$inscripcion_asociada = $inscripcion_activa;
  };

  $id_pagina = 'finales';
  //var_dump($arr_ultima_inscripcion);exit;
  $fecha_actual = strtotime(date("d-m-Y H:i:00",time()));
  $fecha_inicio = !is_null($arr_datos_turno['fecha_inicio'])?strtotime($arr_datos_turno['fecha_inicio']):NULL;
  $fecha_final = !is_null($arr_datos_turno['fecha_final'])?strtotime($arr_datos_turno['fecha_final']):NULL; 
  
  $habilitar_listados_materia = FALSE;
  if ($fecha_actual>=$fecha_inicio || $fecha_actual<=$fecha_final) {
     $habilitar_listados_materia = TRUE;
  }
  

  




 


  
// DETERMINAR SI HAY INSCRIPCION_ACTIVA (FECHA_DESDE_INSCRIPCION A FECHA_HASTA_TURNO_2DO_LLAMADO) - HABILITAR VER_CARRERAS, VER_LLAMADO_1, VER_MATERIAS, VER_ALUMNOS 
   

   
   







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
           <div class="row disabledbutton" id="resultado">

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
let profesor_id = '<?=$_SESSION['id_profesor'];?>';
let habilitar_listados_materia = <?=$habilitar_listados_materia;?>;
let inscripcion_activa = <?=$inscripcion_activa;?>;
let turno_activo = <?=$llamado1_activo;?>;
let llamado1_activo = <?=$llamado1_activo;?>;
let llamado2_activo = <?=$llamado2_activo;?>;
let materia_nombre = '';
let materia_id = '';
let opcion = 'Examenes';
let llamado_numero = '';
let token = "<?=$_SESSION['token'];?>";
let disabledVerCarreras = "<?=$disabledVerCarreras;?>";
let disabledVerLlamado1 = "<?=$disabledVerLlamado1;?>";
let disabledVerMateriasLlamado1 = "<?=$disabledVerMateriasLlamado1;?>";
let disabledPonerNotasAlumnosLlamado1 = "<?=$disabledPonerNotasAlumnosLlamado1;?>";
let disabledVerLlamado2 = "<?=$disabledVerLlamado2;?>";
let disabledVerMateriasLlamado2 = "<?=$disabledVerMateriasLlamado2;?>";
let disabledVerAlumnosLlamado2 = "<?=$disabledVerAlumnosLlamado2;?>";
let disabledPonerNotasAlumnosLlamado2 = "<?=$disabledPonerNotasAlumnosLlamado2;?>";


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
