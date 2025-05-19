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
    $fecha_primer_llamado_desde = $fecha_primer_llamado_hasta = $fecha_segundo_llamado_desde = $fecha_segundo_llamado_hasta = "";

    // *********  SACAR LOS PERIODOS CORRESPONDIENTES A LOS LLAMADOS *************
    if ($cantidad_llamados==2) {
      if (empty($arr_datos_llamado)) {
        $arr_datos_llamado[Constantes::CODIGO_PRIMER_LLAMADO] = $calendario->getEventoActivoByCodigo(Constantes::CODIGO_PRIMER_LLAMADO);
        $arr_datos_llamado[Constantes::CODIGO_SEGUNDO_LLAMADO] = $calendario->getEventoActivoByCodigo(Constantes::CODIGO_SEGUNDO_LLAMADO);
        
        //var_dump('entroooo',$calendario->getLastPrimerLlamado());exit;
        $fecha_primer_llamado_desde = $calendario->getLastPrimerLlamado()['fecha_inicio'];
        $fecha_primer_llamado_hasta = $calendario->getLastPrimerLlamado()['fecha_final'];
        $fecha_segundo_llamado_desde = $calendario->getLastSegundoLlamado()['fecha_inicio'];
        $fecha_segundo_llamado_hasta = $calendario->getLastSegundoLlamado()['fecha_final'];
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

 <!-- CUSTOM CSS -->
 <link rel="stylesheet" href="../public/css/footerProfesor.css" />

</head>


<body>
  <!-- NAVBAR -->
  <header>
    <?php include("navbar.php");?>
  </header>

  <article id="breadcrumb"></article>

  <article class="container-fluid">
    <div id="control_habilitado" style='display:none'></div>
    <div id="cargar_regularidades_habilitado" style='display:none'></div>
  </article>

  <article class="container-fluid">
       <section>
           <div class="row disabledbutton" id="resultado">

           </div><!-- Cierra Row-->
           <div class="row" id="controles"></div><!-- Cierra Row-->
        </section>
  </article>

<!-- Modal -->
<?php include_once('./html/cambiarPassword.html');?>

<!-- Modal -->
<?php include_once('./html/cambiarUsuario.html');?>

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
let fecha_primer_llamado_desde = "<?=$fecha_primer_llamado_desde;?>";
let fecha_primer_llamado_hasta = "<?=$fecha_primer_llamado_hasta;?>";
let fecha_segundo_llamado_desde = "<?=$fecha_segundo_llamado_desde;?>";
let fecha_segundo_llamado_hasta = "<?=$fecha_segundo_llamado_hasta;?>";
let idUsuario = <?=$_SESSION['arreglo_datos_usuario']['id'];?>;     


// CAMBIO CONTRASEÑA OPCIONAL
$( "#idCambioPwd" ).on('shown.bs.modal', function (e) {
     $('#img_captcha').attr('width',"90");
     $('#img_captcha').attr('height',"24");
     $("#img_captcha").attr('src', '../app/lib/CaptchaSecurityImages.php?width=90&height=30&characters=5');
});

$("#idCambioPwd").on('hide.bs.modal', function(){
     $("#msg_restablecer").addClass("d-none");
     $('#inputPasswordNueva').prop("disabled",false); $('#inputPasswordNueva').val("");
     $('#inputRePasswordNueva').prop("disabled",false); $('#inputRePasswordNueva').val("");
     $('#inputCaptcha').prop("disabled",false); $('#inputCaptcha').val("");
});

$("body").on("click",".img",function(e){
           e.preventDefault();
           if ( $('#inputPasswordNueva').attr('type')=='password') {
                $('#inputPasswordNueva').attr('type', 'text');
                $('#inputRePasswordNueva').attr('type', 'text');
                $('#imgPassword1').attr('width',"23");
                $('#imgPassword2').attr('width',"23");
                $('#imgPassword1').attr('src',"../public/img/icons/eye_closed.png");
                $('#imgPassword2').attr('src',"../public/img/icons/eye_closed.png");
           } else if ($('#inputPasswordNueva').attr('type')=='text') {
                $('#inputPasswordNueva').attr('type', 'password');
                $('#inputRePasswordNueva').attr('type', 'password');
                $('#imgPassword1').attr('width',"23");
                $('#imgPassword2').attr('width',"23");
                $('#imgPassword1').attr('src',"../public/img/icons/eye_open.png");
                $('#imgPassword2').attr('src',"../public/img/icons/eye_open.png");

           }
})

$('#btnCambiarPassword').click(function(event) {
      let password = $('#inputPasswordNueva').val();
      let rePassword = $('#inputRePasswordNueva').val();
      let captcha = $('#inputCaptcha').val();

      let parametros = {'password':password, "repassword": rePassword, "captcha":captcha}
      let link = "../API/cambiarPassword.php?token=<?=$_SESSION['token'];?>";

      if (password!="" && rePassword!="" && captcha!="") {
          if (password==rePassword) {
                  $.post(link,parametros,function(response) {
                       $("#msg_restablecer").removeClass("d-none");
                       $("#msg_restablecer").html('<div class="alert alert-'+response.class+'" role="alert"><strong>Atención:</strong>&nbsp;'+response.mensaje+'</div>');
                       if (response.codigo==200) {
                            $('#inputPasswordNueva').prop("disabled",true);
                            $('#inputRePasswordNueva').prop("disabled",true);
                            $('#inputCaptcha').prop("disabled",true);
                            $('#btnCambiarPassword').prop("disabled",true);
                       }
                  },"json");
         } else {
              $("#msg_restablecer").removeClass("d-none");
              $("#msg_restablecer").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong>&nbsp;No coinciden las contraseñas.</div>');
         }
      } else {
          $("#msg_restablecer").removeClass("d-none");
          $("#msg_restablecer").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong>&nbsp;Existen campos vacíos.</div>');
      }
});

// FIN CAMBIO CONTRASEÑA OPCIONAL

// JS CAMBIO DE USUARIO
$( "#idCambioUsuario" ).on('shown.bs.modal', function (e) {
     $('#img_captcha_usuario').attr('width',"90");
     $('#img_captcha_usuario').attr('height',"24");
     $("#img_captcha_usuario").attr('src', '../app/lib/CaptchaSecurityImages.php?width=90&height=30&characters=5');
});

$("#idCambioUsuario").on('hide.bs.modal', function(){
     $("#msg_restablecer_usuario").addClass("d-none");
     $('#inputUsuario').prop("disabled",false); $('#inputUsuario').val("");
     $('#inputCaptchaCambioUsuario').prop("disabled",false); $('#inputCaptchaCambioUsuario').val("");
     $('#btnCambiarUsuario').prop("disabled",false);
});

$('#btnCambiarUsuario').click(function(event) {
      let nombre = $('#inputUsuario').val();
      let captcha = $('#inputCaptchaCambioUsuario').val();

      let parametros = {'nombre':nombre, "idUsuario": idUsuario, "captcha":captcha}
      let link = "../API/setNombreUsuario.php?token=<?=$_SESSION['token'];?>";

      if (nombre!="" && idUsuario!="" && captcha!="") {
                  $.post(link,parametros,function(response) {
                       $("#msg_restablecer_usuario").removeClass("d-none");
                       $("#msg_restablecer_usuario").html('<div class="alert alert-'+response.class+'" role="alert"><strong>Atención:</strong>&nbsp;'+response.mensaje+'</div>');
                       if (response.codigo==200) {
                          $('#inputUsuario').prop("disabled",true);
                          $('#inputCaptchaCambioUsuario').prop("disabled",true);
                          $('#btnCambiarUsuario').prop("disabled",true);
                        }
                  },"json");
        
      } else {
         $("#msg_restablecer_usuario").removeClass("d-none");
         $("#msg_restablecer_usuario").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong>&nbsp;Existen campos vacíos.</div>');
      }
});



// LOAD
$(function () {
    $('[data-toggle="popover"]').popover({
        html: true,
        sanitize: false,
    })
    cargarCarreras(profesor_id);
});

//EXPIRED
function expired() {
  location.href = "../logout.php";
}

setTimeout(expired, 60000*20);

</script>
<script src="./js/gestionarFinales.js"></script>
<script src="./js/funciones.js"></script>
</body>
</html>
