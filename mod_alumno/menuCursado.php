<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "verificarCredenciales.php";
require_once "Constantes.php";
require_once "CalendarioAcademico.php";
require_once "InscripcionCursarMaterias.php";
require_once "Alumno.php";

$id_url = "menu_cursado";
$_SESSION['arr_materias_inscriptas_cursar_actualizadas'] = [];


$idPersona = $_SESSION['arreglo_datos_usuario']['idPersona'];
$objAlumno = new Alumno();
$arr_datos_alumno = $objAlumno->getAlumnoByIdPersona($idPersona);

$hoy = date('Y-m-d');
if (!empty($arr_datos_alumno)) {
  $idAlumno = $arr_datos_alumno['idAlumno'];
  $_SESSION['idAlumno'] = $idAlumno;
}

$calendario = new CalendarioAcademico();
$arr_datos_cursado = $calendario->getLastInscripcionCursado();

$disabledClass = 'disabledbutton';
if (!empty($arr_datos_cursado)) {
  $fecha_inicio = $arr_datos_cursado['fecha_inicio'];
  $fecha_final = $arr_datos_cursado['fecha_final'];
  if ( strtotime($hoy)>=strtotime($fecha_inicio) && strtotime($hoy)<=strtotime($fecha_final) ) {
     $disabledClass = '';
  }
}


?>

<!doctype html>
<html lang="es">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="robots" content="noindex">
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

  <article class="container <?=$disabledClass?>">
    <div id="titulo"><h3><strong>Inscripción a Cursado</strong></h3></div>
  </article>

  <article class="container <?=$disabledClass?>">
       <section>
           <div class="row" id="resultado">
             
           </div><!-- Cierra Row-->
           <div class="row" id="controles"></div><!-- Cierra Row-->
        </section>
  </article>

<!-- FOOTER -->
<?php include("../app/views/footer.html");?>

<!-- JAVASCRIPT LIBRARIES-->
<?php include("../app/views/script_jquery.html");?>

<!-- JAVASCRIPT CUSTOM -->
<script>
$("document").ready(function() {
   cargarCarrerasCursado(<?=$_SESSION['idAlumno'];?>);
})
function expired() {
  location.href = "../logout.php";
}
setTimeout(expired, 60000*20);

//*************************************************************************************
//********************* CARGA LAS CARRERAS QUE TIENE EL ALUMNO ************************
//*************************************************************************************

function cargarCarrerasCursado(idAlumno) {
    //Activo del Menu la opcion Alumnos
    let titulo;
    let subtitulo;
    let bread;
    bread = `<nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cursado</li>
                    </ol>
                </nav>`;
    titulo = `<h1><i>Inscripción a Cursado</i></h1>
                        <h3>Seleccione Carrera</h3>`;

  //Remuevo la class que me deshabilita
  $("#resultado").removeClass("disabledbutton");
  let parametros = {'alumno':idAlumno};
  $.post( "./funciones/getCarrerasPorIdAlumno.php?token=<?=$_SESSION['token'];?>", parametros, function( data ) {
    $("#breadcrumb").html(bread);
    $("#titulo").html(titulo);
    $("#resultado").html("");
    data.datos.forEach(carrera => {
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
     });
  },"json");
}



function cargarMaterias(alumno_id,carrera_id,carrera_descripcion) 
{
  let table1,table2,filas = "";
  let bread;
  let titulo;
  let subtitulo;
  bread = `<nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="#" onclick="cargarCarrerasCursado(`+alumno_id+`)">Inscripción a Cursado</a></li>
                    <li class="breadcrumb-item active" aria-current="page">`+carrera_descripcion+`</li>
                    </ol>
                </nav>`;
  titulo = `<h1><i>Inscripción a Cursado</i></h1><hr>`;
  subtitulo = `<h3>Seleccione las Materias</h3><p><i><strong>*</strong> Una vez que seleccione las materias que se inscribe deberá presionar el Botón <strong>Confirmar</strong> para que la inscripción se haga efectiva.</i>`;
  $("#breadcrumb").html(bread);
  $("#titulo").html(titulo+subtitulo);
   $.post("./funciones/getMateriasParaCursarPorIdCarrera.php?token=<?=$_SESSION['token'];?>",{"carrera_id":carrera_id},function(data){
         $("#resultado").html();
         table1 = `<table class="table table-striped" id="tabla_cursado">
              <thead>
              <tr><th colspan=4 style='text-align: center;background-color: #E4E72A;' >INSCRIPCIÓN PARA CURSADO</th></tr>
              <tr><th>&nbsp;</th><th scope="col" align='center' >Materia</th><th scope="col" align='center'>Inscribirse a Cursar</th></tr>
              </thead>  
              <tbody>`;
         table2 = `</tbody>
              <tfoot><td colspan=3><button class='btn btn-primary btn-block' onclick='persistirInscripciones()'>Confirmar</button></td></tfoot>
              </table>`;     
         data.forEach(materia => {
                let disable_cursado_talleres = "";
                if (materia['materia_formato_codigo']==<?=Constantes::CODIGO_FORMATO_TALLER;?> ||
                    materia['materia_formato_codigo']==<?=Constantes::CODIGO_FORMATO_TALLER_PRACTICA;?> ) {
                      disable_cursado_talleres = "disabledbutton";
                    }
                if (materia['estado'][0]==1) {
                          filas += `<tr class=''>
                                        <td id='col_`+materia.materia_id+`'></td>
                                        <td>`+materia.nombre+` <strong>(`+materia.materia_id+`) </strong><br><strong>Año: </strong>`+materia.anio+`</td>
                                        <td><button class='btn btn-success btn-block ' onclick="guardarIncripcion('Presencial','`+materia.materia_id+`')" >Presencial</button>
                                            <button class='btn btn-warning btn-block ` + disable_cursado_talleres +`' onclick="guardarIncripcion('Semipresencial','`+materia.materia_id+`')">Semipresencial</button>
                                            <button class='btn btn-primary btn-block ` + disable_cursado_talleres +`' onclick="guardarIncripcion('Libre','`+materia.materia_id+`')">Libre</button>
                                            <button class='btn btn-danger btn-block ` + disable_cursado_talleres +`' onclick="guardarIncripcion('No','`+materia.materia_id+`')">Anular</button></td>
                                    </tr>`;
                                              
                } else if (materia['estado'][0]==2) {
                          let class_badge = '';
                          if (materia['estado'][1]=='Presencial') class_badge = 'badge-success'
                          else if (materia['estado'][1]=='Semipresencial') class_badge = 'badge-warning'
                          else if (materia['estado'][1]=='Libre') class_badge = 'badge-primary'
                          filas += `<tr style="cursor:default">
                                        <td id='col_`+materia.materia_id+`'><span class="badge `+class_badge+`">`+materia['estado'][1]+`</span></td>
                                        <td>`+materia.nombre+` <strong>(`+materia.materia_id+`) </strong><br><strong>Año: </strong>`+materia.anio+`</td>
                                        <td><button class='btn btn-success btn-block ' onclick="guardarIncripcion('Presencial','`+materia.materia_id+`')" >Presencial</button>
                                            <button class='btn btn-warning btn-block ` + disable_cursado_talleres +`' onclick="guardarIncripcion('Semipresencial','`+materia.materia_id+`')">Semipresencial</button>
                                            <button class='btn btn-primary btn-block ` + disable_cursado_talleres +`' onclick="guardarIncripcion('Libre','`+materia.materia_id+`')">Libre</button>
                                            <button class='btn btn-danger btn-block ` + disable_cursado_talleres +`' onclick="guardarIncripcion('No','`+materia.materia_id+`')">Anular</button></td>
                                    </tr>`;
                };

              });
              $("#resultado").html(table1+filas+table2);       
   },"json");
   
}




function guardarIncripcion(val,id) {

$.post('./funciones/setMateriasParaCursar.php?token=<?=$_SESSION['token'];?>',{"materia_id":id,"inscribir":val},function(data){
    if (data.res=='Presencial') {
      Swal.fire({
          title: "Registración Provisoria Realizada!",
          text: "La inscripción provisoria se ha Registrado. Confirme esta acción desde el Botón Confirmar",
          icon: "success"
      });
    $("#col_"+id).html("<span class='badge badge-success'>Presencial</span>").fadeIn(3000);
    } else if (data.res=='Semipresencial') {
      Swal.fire({
          title: "Registración Provisoria Realizada!",
          text: "La inscripción provisoria se ha Registrado. Confirme esta acción desde el Botón Confirmar",
          icon: "success"
      });
      $("#col_"+id).html("<span class='badge badge-warning'>Semipresencial</span>").fadeIn(3000);
    } else if (data.res=='Libre') {
      Swal.fire({
          title: "Registración Provisoria Realizada!",
          text: "La inscripción provisoria se ha Registrado. Confirme esta acción desde el Botón Confirmar",
          icon: "success"
      });
      $("#col_"+id).html("<span class='badge badge-primary'>Libre</span>").fadeIn(3000);
    } else if (data.res=='No') {
      Swal.fire({
          title: "Anulación Provisoria Realizada!",
          text: "La inscripción provisoria se ha Anulado. Confirme esta acción desde el Botón Confirmar",
          icon: "success"
      });
      $("#col_"+id).html("");
    }
},"json")


}



function persistirInscripciones() {
  $.post("./funciones/persistirInscripcionesParaCursar.php?token=<?=$_SESSION['token'];?>",function(data) {
      Swal.fire({
            title: "Actualización Realizada!",
            text: "La inscripción se ha Confirmado.",
            icon: "success"
      });
  },"json")
  $("#tabla_cursado").addClass('disabledbutton');
    
}

</script>


</body>
</html>
