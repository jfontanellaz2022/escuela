<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel1.php";
require_once "CalendarioAcademico.php";

$id_url = "menu_examenes";
$_SESSION['arr_materias_inscriptas_actualizadas'] = [];

/* ACA HACEMOS LAS PRUEBAS ** NO OLVIDAR COMENTAR */
$c = new CalendarioAcademico();
$_SESSION['arr_calendario'] = $c->getInscripcionExamenActiva();

//var_dump($_SESSION['arr_calendario']);die;
$disabledClass = 'disabledbutton';
if (!$_SESSION['arr_calendario']['inscripcion_activa']==0) {
  $disabledClass = '';
}


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

  <article class="container <?=$disabledClass?>">
    <div id="titulo"><h3><strong>Inscripción a Exámenes</strong></h3></div>
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
   cargarCarrerasExamenes(<?php $_SESSION['idAlumno'];?>);
})
function expired() {
  location.href = "../logout.php";
}
setTimeout(expired, 60000*20);


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
  $("#resultado").removeClass("disabledbutton");
  let parametros = {'alumno':idAlumno};
  $.post( "./funciones/getCarrerasPorIdAlumno.php", parametros, function( data ) {
    $("#breadcrumb").html(bread);
    $("#titulo").html(titulo+subtitulo);
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
  let titulo;
  let subtitulo;
  let bread;
  bread = `<nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="menuEscritorio.php">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="#" onclick="cargarCarrerasExamenes(`+alumno_id+`)">Inscripción a Exámenes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">`+carrera_descripcion+`</li>
                    </ol>
                </nav>`;
  titulo = `<h1><i>Ex&aacute;menes Finales</i></h1>`;
  subtitulo = `<h3>Seleccione Materias</h3><p><i><strong>*</strong> Una vez que seleccione las materias que se inscribe deberá presionar el Botón <strong>Confirmar</strong> para que la inscripción se haga efectiva.</i>`;
  $("#breadcrumb").html(bread);
  $("#titulo").html(titulo+subtitulo);
  let table1,table2,filas = "";
  //console.info('aaa:' + carrera_id);
   $.post("./funciones/getMateriasParaRendirPorIdCarrera.php",{"carrera_id":carrera_id},function(data){
         $("#resultado").html();
         table1 = `<table class="table table-striped" id="tabla_examen">
              <thead>
              <tr><th colspan=4 style='text-align: center;background-color: #1692ED;' >INSCRIPCIÓN A EXÁMENES FINALES</th></tr>
              <tr><th>&nbsp;</th><th scope="col" align='center' >Materia</th><th scope="col" align='center'>Inscribirse</th></tr>
              </thead>  
              <tbody>`;
         table2 = `</tbody>
              <tfoot><td colspan=3><button class='btn btn-primary btn-block' onclick='persistirInscripciones(`+carrera_id+`)'>Confirmar</button></td></tfoot>
              </table>`;     
         data.forEach(materia => {
                if (materia['estado']==1) {
                          filas += `<tr class=''>
                                        <td id='col_`+materia.materia_id+`'></td>
                                        <td>`+materia.nombre+` <strong>(`+materia.materia_id+`) </strong><br><strong>Cursado: `+materia.cursado+`</strong></br><strong>Año: </strong>`+materia.anio+`<br><strong>Fecha Exámen: `+materia.fecha+`</strong></td>
                                        <td><button class='btn btn-success btn-block' onclick="guardarIncripcion('Si','`+materia.materia_id+`')" >Si</button>
                                            <button class='btn btn-danger btn-block' onclick="guardarIncripcion('No','`+materia.materia_id+`')">No</button></td>
                                    </tr>`;
                    
                } else if (materia['estado']==2) {
                          filas += `<tr style="cursor:default">
                                        <td id='col_`+materia.materia_id+`'><span class="badge badge-success">Inscripto</span></td>
                                        <td>`+materia.nombre+` <strong>(`+materia.materia_id+`) </strong><br><strong>Cursado: `+materia.cursado+`</strong></br><strong>Año: </strong>`+materia.anio+`<br><strong>Fecha Exámen: `+materia.fecha+`</strong></td>
                                        <td><button class='btn btn-success btn-block' onclick="guardarIncripcion('Si','`+materia.materia_id+`')" >Si</button>
                                            <button class='btn btn-danger btn-block' onclick="guardarIncripcion('No','`+materia.materia_id+`')">No</button></td>
                                    </tr>`;
                } else if (materia['estado']==3) {
                          filas += `<tr class='disabledbutton'>
                                        <td id='col_`+materia.materia_id+`'><span class="badge badge-success">Inscripto</span></td>
                                        <td>`+materia.nombre+` <strong>(`+materia.materia_id+`) </strong><br><strong>Cursado: `+materia.cursado+`</strong></br><strong>Año: </strong>`+materia.anio+`<br><strong>Fecha Exámen: `+materia.fecha+` </strong></td>
                                        <td><button class='btn btn-success btn-block' onclick="guardarIncripcion('Si','`+materia.materia_id+`')" >Si</button>
                                            <button class='btn btn-danger btn-block' onclick="guardarIncripcion('No','`+materia.materia_id+`')">No</button></td>
                                    </tr>`;
                }

              });
              $("#resultado").html(table1+filas+table2);       
   },"json");
   
}


function guardarIncripcion(val,id) {

    $.post('./funciones/setMateriasParaRendir.php',{"materia_id":id,"inscribir":val},function(data){
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
  $.post("./funciones/persistirInscripcionesParaRendir.php",{"carrera_id":carrera},function(data) {
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
