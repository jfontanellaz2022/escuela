<?php
//set_include_path('../app/lib/'.PATH_SEPARATOR.'../conexion/'.PATH_SEPARATOR.'./');
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');

require_once 'CalendarioAcademico.php';
//include_once 'conexion.php';
include_once 'Sanitize.class.php';
include_once 'ArrayHash.class.php';

$turno_id = 137;
$inscripcion_activa = 136;
$inscripcion_asociada = 136;
$cantidad_llamados = 1;

?>

<!doctype html>
<html lang="es"><head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SiGeAl - Bedelia</title>
   <?php include_once('componente_header.html'); ?>
   <?php include("componente_script_jquery.html"); ?>
  

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
              <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Actas Examenes</li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="img form">
    <div class="jumbotron jumbotron-fluid rounded form2">
      <div class="container justifu+y-content-center">
        <h2 class="display-5">Actas de Exámenes <?=$inscripcion_activa?></h2>
        <hr>
    <form id="form">
        
      <div class="form-row">  
        <div class="form-group col-md-6">
          <strong>Llamado</strong>
          <select name="selectLlamado" id="selectLlamado" class="form-control" >
             <option value='0'> - Seleccione Llamado - </option>
          </select>
        </div>
      </div>
    
      <div class="form-row">  
        <div class="form-group col-md-6">
          <strong>Carrera</strong>
          <select name="selectCarreras" id="selectCarreras"  class="form-control" >
            <option value='0'> - Seleccione Carrera - </option>  
        </select>
        </div>
      </div>
        
      <div class="form-row">
        <div class="form-group col-md-6">
          <button type="button" class="btn btn-primary btn-block" onclick="cargaMateriasConInscriptosPorCarrera()">Aceptar</button>
        </div>
      </div>
      
      <div class="form-row">
        <div class="form-group col-md-6">
          <button type="button" class="btn btn-primary btn-block" onclick="location.href='home.php'">Volver</button>
        </div>
      </div>
    
    </form>
    
    </div>
    </div>
</article>

  <article class="container">
       <section>
            <div class="row" id="filtro"></div><!-- Cierra Row-->
            <div class="row" id="resultado"></div><!-- Cierra Row-->
            <div class="row" id="resultado_accion"></div><!-- Cierra Row-->
        </section>
  </article>

  

<!-- FOOTER -->
<?php include("componente_footer.html"); ?>


<!-- JAVASCRIPT CUSTOM -->
<script>

$(function () {
    let inscripcion_activa_id = <?=$inscripcion_activa?>;
    let inscripcion_asociada_id = <?=$inscripcion_asociada?>;
    let turno_id = <?=$turno_id?>;
    let cantidad_llamados = <?=$cantidad_llamados?>;
    let titulo;
    let carrera;
    let arreglo_carreras;

    if (turno_id!='') {
       titulo = "Turno de Examen: <font color='red'>"+inscripcion_activa_id+"</font>";
       if (cantidad_llamados==1) {
         $('#selectLlamado').append("<option value='"+inscripcion_asociada_id+"_1' >1er Llamado</option>");
    } else if (cantidad_llamados==2) {
         $('#selectLlamado').append("<option value='"+inscripcion_asociada_id+"_1' >1er Llamado</option>");
         $('#selectLlamado').append("<option value='"+inscripcion_asociada_id+"_2' >2do Llamado</option>");
    };
       
       /**** SACA LAS CARRERAS HABILITADAS ******/
       carreras = getCarrerasHabilitadas();
       arreglo_carreras = carreras.data;
       $.each(arreglo_carreras, function(i, item) {
           $('#selectCarreras').append("<option value='"+item.id+"' >"+item.descripcion+" ("+item.id+")</option>");
       });
    };
    
    //let titulo1 = "<h3><i><strong><u>CARRERA:</u></strong> "+datos_carrera+ "</i></h3>";
    //let titulo2 = "<h4>Listado de Alumnos</h4>";

    $("#titulo").html(titulo);
});


function cargaMateriasConInscriptosPorCarrera()
    {
        let val = "";
        val = $("#selectCarreras").val();
        if ($("#selectLlamado").val()!=0 && val!=0) {
            let parametros = val+'_'+$("#selectLlamado").val();
            let p = {"parametros":parametros}
            $.get("./funciones/generarMateriasConInscriptosPorCarrera.php",p,function (resul){
                $("#resultado").html(resul);
            });
        } else {
            alert('Debe seleccionaralguna de las opciones.');
        }
    }


//***********************************************************
// RETORNA LAS CARRERAS ACTIVAS 
//***********************************************************
function getCarrerasHabilitadas() {
   var evento;
   $.ajax({
      url:"./funciones/getCarrerasHabilitadas.php",
      type:"POST",
      dataType : 'json',
      async: false,
      success: function(datos){
         evento = datos;
      }
    });
   return evento;
}



</script>

</body>
</html>