<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once 'verificarCredenciales.php';
require_once 'Sanitize.class.php';
require_once 'ArrayHash.class.php';
require_once 'Carrera.php';

$ARREGLO_CARRERAS = [];

$objCarrera = new Carrera();
$ARREGLO_CARRERAS = $objCarrera->getCarrerasHabilitadas();
//var_dump($ARREGLO_CARRERAS);exit;

?>
<!doctype html>
<html lang="es">
<head>
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
              <li class="breadcrumb-item active" aria-current="page">Homologacion Materia Aprobada</li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="container">
    <div id="titulo"></div>
  </article>

  <article class="container">
       <section>
            <form>
                    <div class="form-row">
                        <label for="inputCarrera"><strong>Carrera</strong></label><br>
                        <select id="inputCarrera" class="form-control">
                            <option value="">-- Carrera --</option>
                            <?php
                                 if (count($ARREGLO_CARRERAS)>0) {
                                     foreach($ARREGLO_CARRERAS as $item) {
                                         echo "<option value='" . $item['id'] . "'>" . $item['descripcion'] . " (" . $item['id'] . ")</option>";
                                     }
                                 }
                            ?>
                        </select>
                    </div>

                    <div class="form-row">
                        <label for="inputMateria"><strong>Materia</strong></label><br>
                        <select id="inputMateria" class="form-control select2-container" style="height: 100;">
                            <option value="">-- Materia --</option>
                        </select>
                    </div>

                    <div class="form-row">
                    <label for="inputAlumno"><strong>Alumno</strong></label><br>
                        <select id="inputAlumno" class="form-control select2-container" style="height: 100;" >
                            <option value="">-- Alumno --</option>
                        </select>
                    </div>

                    <div class="form-row">
                    <label for="inputNota"><strong>Nota</strong></label><br>
                        <select id="inputNota"  class="form-control select2-container" style="height: 100;">
                            <option value="">-- Nota --</option>
                            <option value="6">6 (Seis)</option>
                            <option value="7">7 (Siete)</option>
                            <option value="8">8 (Ocho)</option>
                            <option value="9">9 (Nueve)</option>
                            <option value="10">10 (Diez)</option>
                        </select>
                    </div>
                    <br>
                    <div class="form-row">
                        <input type="button" id="btnGuardar" class="btn btn-primary" onclick="guardarHomologacion()" value="Guardar">&nbsp;&nbsp;&nbsp;
                        <input type="button" class="btn btn-primary" onclick="location.href='menuHomologacionMateriaAprobada.php'" value="Nueva" >
                    </div>
                
                </form>
            <br>
            <div class="row" id="resultado_accion"></div><!-- Cierra Row-->
            <br>
        </section>
  </article>

  

<!-- FOOTER -->
<?php include("componente_footer.html"); ?>




<!-- JAVASCRIPT CUSTOM -->
<script>

$('#inputCarrera').select2({
                    theme: "bootstrap",
                });

$('#inputNota').select2({
                    theme: "bootstrap",
                });                


$("#inputCarrera").change(function(){
    let carrera_id = $("#inputCarrera").val();
    //Carga las Materias de una carrera en la select2
    //alert(carrera_id);
    $('#inputAlumno').val(null).trigger('change');
    $('#inputMateria').val(null).trigger('change');
    $.post("./funciones/getMateriasPorIdCarrera.php",{"carrera_id":carrera_id},function(response){
          if (response.codigo==200) {
               $("#inputMateria").empty(); 
           response.datos.forEach(materia => {
                      $("#inputMateria").append($('<option/>', {
                            text: materia.nombre + ' ('+materia.id+') ',
                            value: materia.id,
                      }));
                });
                $('#inputMateria').select2({
                    theme: "bootstrap",
                });
          };
    },"json")

    //Carga los Alumnos de una carrera en la select2
    $.post("./funciones/getAlumnosPorIdCarrera.php",{"carrera_id":carrera_id},function(response){
          if (response.codigo==200) {
            $("#inputAlumno").empty(); 
            response.datos.forEach(alumno => {
                      $("#inputAlumno").append($('<option/>', {
                            text: alumno.apellido+', '+alumno.nombre + ' ('+alumno.id+') ',
                            value: alumno.id,
                      }));
                });
                $('#inputAlumno').select2({
                    theme: "bootstrap",
                    allowClear: false,
                    /*theme: "custom-option-select"*/
                });
          };
    },"json")
})

function guardarHomologacion() {
    let carrera_id = $("#inputCarrera").val();
    let materia_id = $("#inputMateria").val();
    let alumno_id = $("#inputAlumno").val();
    let nota = $("#inputNota").val();
    let parametros = {"carrera_id":carrera_id,"materia_id":materia_id,"alumno_id":alumno_id,"nota":nota}
    console.log(parametros);
    $.post("./funciones/persistirHomologacionMateriaAprobada.php",parametros,function(res){
        let msg = "";
        if (res.codigo==200) {
            msg = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Atención</strong> `+res.mensaje+`
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>`;
            $("#inputCarrera").attr ('disabled','disabled');
            $("#inputMateria").attr ('disabled','disabled');
            $("#inputAlumno").attr ('disabled','disabled');
            $("#inputNota").attr ('disabled','disabled');
            $("#btnGuardar").attr ('disabled','disabled');        
         
        } else {
            msg = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error</strong> `+res.mensaje+`
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>`;
        };
        $("#resultado_accion").html(msg);
    },"json")

}

</script>

</body>
</html>