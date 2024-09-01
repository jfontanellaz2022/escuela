<?php
//set_include_path('../app/lib/'.PATH_SEPARATOR.'../../conexion/');
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'../conexion/');
require_once 'seguridad.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
include_once 'ArrayHash.class.php';
$sql = "SELECT * FROM carrera WHERE habilitada = 'Si'";
$resultado = mysqli_query($conex,$sql);
$ARREGLO_CARRERAS = array();
if ($resultado) {
  $ARREGLO_CARRERAS = mysqli_fetch_all($resultado);
};

?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SiGeAl - Bedelia</title>
   <?php include_once('componente_header.html'); ?>
   <?php include("componente_script_jquery.html"); ?>
  
  
    <style>
    .dropdown-item:hover{
          background-color: #CFC290;
        }        
     footer.nb-footer {
        background: #222;
        border-top: 4px solid #b78c33; }
    footer.nb-footer .about {
        margin: 0 auto;
        margin-top: 30px;
        max-width: 1170px;
        text-align: center; }
    footer.nb-footer .about p {
        font-size: 13px;
        color: #999;
        margin-top: 30px; }
    footer.nb-footer .about .social-media {
        margin-top: 15px; }
    footer.nb-footer .about .social-media ul li a {
        display: inline-block;
        width: 45px;
        height: 45px;
        line-height: 45px;
        border-radius: 50%;
        font-size: 16px;
        color: #b78c33;
        border: 1px solid rgba(255, 255, 255, 0.3); }
    footer.nb-footer .about .social-media ul li a:hover {
        background: #b78c33;
        color: #fff;
        border-color: #b78c33; }
    footer.nb-footer .footer-info-single {
        margin-top: 30px; }
    footer.nb-footer .footer-info-single .title {
        color: #aaa;
        text-transform: uppercase;
        font-size: 16px;
        border-left: 4px solid #b78c33;
        padding-left: 5px; }
    footer.nb-footer .footer-info-single ul li a {
        display: block;
        color: #aaa;
        padding: 2px 0; }
    footer.nb-footer .footer-info-single ul li a:hover {
        color: #b78c33; }
    footer.nb-footer .footer-info-single p {
        font-size: 13px;
        line-height: 20px;
        color: #aaa; }
    footer.nb-footer .copyright {
        margin-top: 15px;
        background: #111;
        padding: 7px 0;
        color: #999; }
    footer.nb-footer .copyright p {
        margin: 0;
        padding: 0; }
    .thead-green {
        background-color: rgb(0, 99, 71);
        color: white;
    }
    .disabledbutton {
          pointer-events: none;
          opacity: 0.5;
      }

    .input-form {
          border: 1px solid black;
          border-radius: 2px;
          height: 36px !important;
    }
    
    
   </style>    
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
                                         echo "<option value='$item[0]'>$item[2] ($item[0])</option>";
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
    $.post("./funciones/getMateriasPorIdCarrera.php",{"carrera_id":carrera_id},function(data){
          if (data.codigo==100) {
            data.datos.forEach(materia => {
                      $("#inputMateria").append($('<option/>', {
                            text: '('+materia.id+') '+materia.nombre,
                            value: materia.id,
                      }));
                });
                $('#inputMateria').select2({
                    theme: "bootstrap",
                });
          };
    },"json")

    //Carga los Alumnos de una carrera en la select2
    $.post("./funciones/getAlumnosPorIdCarrera.php",{"carrera_id":carrera_id},function(data){
          if (data.codigo==100) {
            data.datos.forEach(alumno => {
                      $("#inputAlumno").append($('<option/>', {
                            text: '('+alumno.id+') '+alumno.apellido+', '+alumno.nombre,
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
        if (res.codigo==100) {
            msg = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Atención</strong> `+res.data+`
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
                    <strong>Error</strong> `+res.data+`
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