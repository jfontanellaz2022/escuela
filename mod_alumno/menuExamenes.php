<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');

require_once "verificarCredenciales.php";
require_once "Alumno.php";
require_once "CalendarioAcademico.php";
require_once "Constantes.php";

$id_url = "menu_examenes";

$_SESSION['arr_materias_inscriptas_actualizadas'] = [];
$_SESSION['turno_mesa_especial'] = false;
//var_dump($_SESSION['arreglo_credenciales_usuario']);exit;
//* OBTENEMOS EL ID DE LA PERSONA Y EL ID DEL ALUMNO  **
$idPersona = $idAlumno = $idCalendario = $cantidad_llamados = 0;
$fecha_inicio = $fecha_final = "";
$hoy = date('Y-m-d');

$idPersona = $_SESSION['arreglo_datos_usuario']['idPersona'];
$objAlumno = new Alumno();
$arr_datos_alumno = $objAlumno->getAlumnoByIdPersona($idPersona);

if (!empty($arr_datos_alumno)) {
  $idAlumno = $arr_datos_alumno['idAlumno'];
  $_SESSION['idAlumno'] = $idAlumno;
}
// ******************************************************


$objCalendario = new CalendarioAcademico();
$arr_datos_calendario = $objCalendario->getLastInscripcionExamen();
$arr_datos_turno = $objCalendario->getLastTurnoExamen();

//var_dump($arr_datos_calendario,$arr_datos_turno);exit;

$_SESSION['turno_id'] = $arr_datos_turno['id'];
$_SESSION['arr_calendario']['inscripcion_asociada'] = $_SESSION['arr_calendario']['inscripcion_activa'] = 0;

if (!empty($arr_datos_calendario)) {
      $fecha_inicio = $arr_datos_calendario['fecha_inicio'];
      $fecha_final = $arr_datos_calendario['fecha_final'];
      if ( strtotime($hoy)>=strtotime($fecha_inicio) && strtotime($hoy)<=strtotime($fecha_final) ) {
            if ($arr_datos_calendario['codigo']==Constantes::CODIGO_INSCRIPCION_PRIMER_TURNO || 
                  $arr_datos_calendario['codigo']==Constantes::CODIGO_INSCRIPCION_TERCER_TURNO) {
                  $idCalendario = $arr_datos_calendario['id'];
                  $_SESSION['arr_calendario']['inscripcion_asociada'] = $idCalendario;
                  $_SESSION['arr_calendario']['inscripcion_activa'] = $idCalendario;
                  $cantidad_llamados = 2;
            } else if ($arr_datos_calendario['codigo']==Constantes::CODIGO_INSCRIPCION_SEGUNDO_TURNO) {
                  $idCalendario = $arr_datos_calendario['id'];
                  $_SESSION['arr_calendario']['inscripcion_asociada'] = $idCalendario;
                  $_SESSION['arr_calendario']['inscripcion_activa'] = $idCalendario;
                  $cantidad_llamados = 1;
            } else if ($arr_datos_calendario['codigo']==Constantes::CODIGO_INSCRIPCION_INTERMEDIA_PRIMER_TURNO ||
                      $arr_datos_calendario['codigo']==Constantes::CODIGO_INSCRIPCION_INTERMEDIA_SEGUNDO_TURNO) {
                  $idCalendario = $objCalendario->getLastInscripcionExamen()['id'];
                  $_SESSION['arr_calendario']['inscripcion_asociada'] = $idCalendario;
                  $_SESSION['arr_calendario']['inscripcion_activa'] = $arr_datos_calendario['id'];
                  $cantidad_llamados = 2;
            } else if ($arr_datos_calendario['codigo']==Constantes::CODIGO_INSCRIPCION_MESA_ESPECIAL) {
                  $_SESSION['turno_mesa_especial'] = true;
                  $idCalendario = $arr_datos_calendario['id'];
                  $_SESSION['arr_calendario']['inscripcion_asociada'] = $idCalendario;
                  $_SESSION['arr_calendario']['inscripcion_activa'] = $idCalendario;
                  $cantidad_llamados = 1;
            }
            
            $_SESSION['arr_calendario']['cantidad_llamados'] = $cantidad_llamados;
      } else { 
            $arr_datos_calendario = $objCalendario->getLastInscripcionExamenConIntermedias();
            $fecha_inicio = $arr_datos_calendario['fecha_inicio'];
            $fecha_final = $arr_datos_calendario['fecha_final'];
            if ( strtotime($hoy)>=strtotime($fecha_inicio) && strtotime($hoy)<=strtotime($fecha_final) ) {
                $_SESSION['arr_calendario']['inscripcion_asociada'] = $objCalendario->getLastInscripcionExamen()['id'];
                $_SESSION['arr_calendario']['inscripcion_activa'] = $arr_datos_calendario['id'];
            } else {
                $_SESSION['arr_calendario']['inscripcion_activa'] =  $idCalendario;
            }
          //die('nada activo');
      }
}



$disabledCarrerasClass = 'disabledbutton';
$disabledMateriasClass = 'disabledbutton';
if ( strtotime($hoy)>=strtotime($objCalendario->getLastInscripcionExamen()['fecha_inicio']) && 
     strtotime($hoy)<=strtotime($objCalendario->getLastTurnoExamen()['fecha_final']) ) {
    $disabledCarrerasClass = '';
}

if (!$_SESSION['arr_calendario']['inscripcion_activa']==0) {
    $disabledMateriasClass = '';
}

?>

<!doctype html>
<html lang="es">
<head>

<?php include_once('../app/views/header.html');?>

<!-- CUSTOM CSS -->
<link rel="stylesheet" href="../public/css/footerProfesor.css" />
 
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

  <article class="container <?//=$disabledClass?>">
    <div id="titulo"><h3><strong>Inscripción a Exámenes</strong></h3></div>
  </article>

  <article class="container <?//=$disabledClass?>">
       <section>
           <div class="row" id="resultado">
              
           </div><!-- Cierra Row-->
           <div class="row" id="controles"></div><!-- Cierra Row-->
        </section>
  </article>

<!-- Modal -->
<?php include_once('./html/cambiarPassword.html');?>

<!-- Modal -->
<?php include_once('./html/cambiarUsuario.html');?>

<!-- FOOTER -->
<?php include("../app/views/footer.html");?>

<!-- JAVASCRIPT LIBRARIES-->
<?php include("../app/views/script_jquery.html");?>

<!-- JAVASCRIPT CUSTOM -->
<script>
let idUsuario = <?=$_SESSION['arreglo_datos_usuario']['id'];?>; 

$("document").ready(function() {
    $('[data-toggle="popover"]').popover({
        html: true,
        sanitize: false,
    });
   cargarCarrerasExamenes(<?=$_SESSION['idAlumno'];?>);
})

//***********************************************************
// RETORNA SI UN ALUMNO TIENE MESA ESPECIAL EN UNA CARRERA
//***********************************************************
function hasMesaEspecial(idAlumno,idCarrera) {
   let has_mesa;
   let parametros = {"alumno":idAlumno,"carrera":idCarrera};
   $.ajax({
         url:"../API/findAlumnoMesaEspecial.php",
         type:"POST",
         data: parametros,
         dataType : 'json',
         async: false,
         success: function(response){
            has_mesa = response.datos;
         }
   });
  return has_mesa;
} 

//EXPIRED
function expired() {
  location.href = "../logout.php";
}

setTimeout(expired, 60000*20);

// CAMBIO CONTRASEÑA OPCIONAL
$( "#idCambioPwd" ).on('shown.bs.modal', function (e) {
     $('#img_captcha').attr('width',"100");
     $('#img_captcha').attr('height',"25");
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



//*************************************************************************************
//********************* CARGA LAS CARRERAS QUE TIENE EL ALUMNO ************************
//*************************************************************************************

function cargarCarrerasExamenes(idAlumno) {
    //Activo del Menu la opcion Alumnos
    let titulo;
    let subtitulo;
    let bread;
    bread = `<nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Examenes</li>
                    </ol>
                </nav>`;
    titulo = `<h1><i>Ex&aacute;menes Finales</i></h1>`;
    subtitulo = `<h3>Seleccione Carrera</h3>`;

  //Remuevo la class que me deshabilita
  //$("#resultado").removeClass("disabledbutton");
  let parametros = {'alumno':idAlumno};
  $.post( "../API/findAllCarrerasPorAlumno.php?token=<?=$_SESSION['token'];?>", parametros, function( response ) {
    $("#breadcrumb").html(bread);
    $("#titulo").html(titulo+subtitulo);
    $("#resultado").html("");
    response.datos.forEach(carrera => {
       let resul = `<div class="col-md-4">
             <div class="card" style="width: 18rem;">
                     <img src="../public/img/`+carrera.imagen+`" class="card-img-top">
                     <div class="card-body">
                         <h3 class="card-title">`+carrera.descripcion+`</h3>
                             <h6 class="card-subtitle mb-2 text-muted"></h6>
                             <p class="card-text"></p>
                             <a href="#" class="btn btn-primary btn-block" onclick="cargarMaterias(`+idAlumno+`,`+carrera.id+`,'`+carrera.descripcion+`')" ><i class="fa fa-check">&nbsp;Ingresar a Inscribirme</i></a>
                     </div>
                   </div>
             </div>`;
        $("#resultado").append(resul);
        $(".container").addClass("<?=$disabledCarrerasClass;?>");
     });
  },"json");
}




function cargarMaterias(alumno_id,carrera_id,carrera_descripcion) 
{
    let titulo;
    let subtitulo;
    let bread;
    bread = `<nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="menuEscritorio.php">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="menuExamenes.php?token=<?=$_SESSION['token'];?>">Inscripción a Exámenes</a></li>
                        <li class="breadcrumb-item active" aria-current="page">`+carrera_descripcion+`</li>
                        </ol>
                    </nav>`;
    titulo = `<h1><i>Ex&aacute;menes Finales</i></h1>`;
    subtitulo = `<h3>Seleccione Materias</h3><p><i><strong>*</strong> Una vez que seleccione las materias que se inscribe deberá presionar el Botón <strong>Confirmar</strong> para que la inscripción se haga efectiva.</i>`;
    $("#breadcrumb").html(bread);
    $("#titulo").html(titulo+subtitulo);
    let table1,table2,filas = "";
    let mesa_especial = false;

    
    if (<?=$_SESSION['turno_mesa_especial'];?>==true) {
            mesa_especial = hasMesaEspecial(alumno_id,carrera_id);
    };
    
  

  //console.info('aaa:' + carrera_id);
   $.post("./funciones/getMateriasParaRendirPorIdCarrera.php?token=<?=$_SESSION['token'];?>",{"carrera_id":carrera_id},function(data){
         $("#resultado").html();
         table1 = `<table class="table table-striped" id="tabla_examen">
              <thead>
              <tr><th colspan=4 style='text-align: center;background-color:rgb(22, 237, 58);' >INSCRIPCIÓN A EXÁMENES FINALES</th></tr>
              <tr><th>&nbsp;</th><th scope="col" align='center' >Materia</th><th scope="col" align='center'>Inscribirse</th></tr>
              </thead>  
              <tbody>`;
         table2 = `</tbody>
              <tfoot><td colspan=3><button class='btn btn-primary btn-block' onclick='persistirInscripciones(`+carrera_id+`)'>Confirmar</button></td></tfoot>
              </table>`;    
         data.forEach(materia => {
                if (materia['estado_inscripcion']==1) {
                          filas += `<tr class=''>
                                        <td id='col_`+materia.materia_id+`'></td>
                                        <td>`+materia.nombre+` <strong>(`+materia.materia_id+`) </strong><br>Cursado: <strong>`+materia.cursado+`</strong> | Condición: <strong>`+materia.condicion+`</strong></br>Año: <strong>`+materia.anio+`</strong><br>Fecha Exámen: <strong>`+materia.fecha+`</strong></td>
                                        <td><button class='btn btn-success btn-block' onclick="guardarIncripcion('`+materia.condicion+`','`+materia.materia_id+`')" >Si</button>
                                            <button class='btn btn-danger btn-block' onclick="guardarIncripcion('No','`+materia.materia_id+`')">No</button></td>
                                    </tr>`;
                    
                } else if (materia['estado_inscripcion']==2) {
                          filas += `<tr style="cursor:default">
                                        <td id='col_`+materia.materia_id+`'><span class="badge badge-success">Inscripto</span></td>
                                        <td>`+materia.nombre+` <strong>(`+materia.materia_id+`) </strong><br>Cursado: <strong>`+materia.cursado+`</strong> | Condición: <strong>`+materia.condicion+`</strong></br>Año: <strong>`+materia.anio+`</strong><br>Fecha Exámen: <strong>`+materia.fecha+`</strong></td>
                                        <td><button class='btn btn-success btn-block' onclick="guardarIncripcion('`+materia.condicion+`','`+materia.materia_id+`')" >Si</button>
                                            <button class='btn btn-danger btn-block' onclick="guardarIncripcion('No','`+materia.materia_id+`')">No</button></td>
                                    </tr>`;
                } else if (materia['estado_inscripcion']==3) {
                          filas += `<tr class='disabledbutton'>
                                        <td id='col_`+materia.materia_id+`'><span class="badge badge-success">Inscripto</span></td>
                                        <td>`+materia.nombre+` <strong>(`+materia.materia_id+`) </strong><br>Cursado: <strong>`+materia.cursado+`</strong> | Condición: <strong>`+materia.condicion+`</strong></br>Año: <strong>`+materia.anio+`</strong><br>Fecha Exámen: <strong>`+materia.fecha+`</strong></td>
                                        <td><button class='btn btn-success btn-block' onclick="guardarIncripcion('`+materia.condicion+`','`+materia.materia_id+`')" >Si</button>
                                            <button class='btn btn-danger btn-block' onclick="guardarIncripcion('No','`+materia.materia_id+`')">No</button></td>
                                    </tr>`;
                }

              });
              $("#resultado").html(table1+filas+table2);       
              if (<?=$_SESSION['turno_mesa_especial'];?>==true && mesa_especial==true) {
                  $(".container").removeClass("disabledbutton");
              } else if (<?=$_SESSION['turno_mesa_especial'];?>==true && mesa_especial==false) {
                  $(".container").addClass("disabledbutton");
              } else {
                  $(".container").addClass("<?=$disabledMateriasClass;?>");    
              }
              
   },"json");
   
}


function guardarIncripcion(val,id) {
    $.post('../API/setMateriasParaRendir.php?token=<?=$_SESSION['token'];?>',{"materia_id":id,"inscribir":val},function(data){
        if (data.res=='Si') {
          Swal.fire({
              title: "Actualización Realizada!",
              text: "La inscripción se ha Registrado. Confirme esta acción desde el Botón Confirmar",
              icon: "success"
          });
        $("#col_"+id).html("<span class='badge badge-success'>Inscripto</span>");
        } else if (data.res=='No') {
          Swal.fire({
              title: "Actualización Realizada!",
              text: "La inscripción se ha Eliminado. Confirme esta acción desde el Botón Confirmar",
              icon: "error"
          });
          $("#col_"+id).html("");
        }
    },"json")

    
}


function persistirInscripciones(carrera) {
  $.post("../API/insertInscripcionesParaRendir.php?token=<?=$_SESSION['token'];?>",{"carrera_id":carrera},function(data) {
      Swal.fire({
            title: "Actualización Realizada!",
            text: "La inscripción se ha Confirmado.",
            icon: "success"
      });
  },"json")
   $("#tabla_examen").addClass("disabledbutton");
}

</script>


</body>
</html>
