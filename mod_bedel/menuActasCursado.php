<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'../conexion/'.PATH_SEPARATOR.'./funciones/'.PATH_SEPARATOR.'./');
require_once 'config.php';
require_once 'seguridad.php';
require_once 'conexion.php';
require_once 'Sanitize.class.php';
require_once 'ArrayHash.class.php';
require_once 'CalendarioAcademico.php';
require_once 'Carrera.php';

$evento_inscripcion_cursado = new CalendarioAcademico();
$evento_activo = $evento_inscripcion_cursado->getEventoActivoByCodigo(1023);
//var_dump($evento_activo);die;
$ARREGLO_CARRERAS = array();
$bandInscripcionCursadoActivo = FALSE;

$calendario_descripcion = "";
$calendario_id = 0;

if (!empty($evento_activo)) {
    
    $bandInscripcionCursadoActivo = TRUE;
    $obj_carrera = new Carrera();
    //$arr_carreras = $obj_carrera->getCarreras();

    //var_dump($arr_carreras);die;    

    foreach ($obj_carrera->getCarreras() as $item) {
        $arreglo = array();
        if ($item['habilitacion_cursado']=='Si') {
            array_push($arreglo, $item['id'], $item['descripcion']);
            array_push($ARREGLO_CARRERAS, $arreglo);
        }
    }
    
    /*$sqlCarreras = "SELECT a.id, a.descripcion
                    FROM carrera a";
    $resultadoCarreras = mysqli_query($conex, $sqlCarreras);
    $_SESSION['carreras'] = array();
    while ($filaCarreras = mysqli_fetch_assoc($resultadoCarreras)) {
        $arreglo = array();
        array_push($arreglo, $filaCarreras['id'], $filaCarreras['descripcion']);
        array_push($_SESSION['carreras'], $arreglo);
    }; */

    $calendario_descripcion = $evento_activo[0]['evento_descripcion'];
    $calendario_id = $evento_activo[0]['id'];
    //var_dump($calendario_descripcion.'*'.$calendario_id);die;
};

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
    <?php include("navbar.php"); ?>
  </header>

  <article>
    <div id="breadcrumb">
      <nav aria-label="breadcrumb" role="navigation">
          <ol class="breadcrumb">
              <li class="breadcrumb-item" aria-current="page"><a href="home.php?token=<?=$_SESSION['token'];?>">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Actas Cursado</li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="img form">
    <div class="jumbotron jumbotron-fluid rounded form2">
      <div class="container justifu+y-content-center">
        <h2 class="display-5">Actas de Cursado</h2>
        <hr>
    <form id="form">
    
      <div class="form-row">  
        <div class="form-group col-md-6">
          <strong>Carrera</strong>
          <select name="selectCarreras" id="selectCarreras"  class="form-control" onchange="cargaMateriasConInscriptosCursadoPorCarrera(this.value)">
            <option value='0'> - Seleccione Carrera - </option>  
        </select>
        </div>
      </div>
        
      <div class="form-row">
        <div class="form-group col-md-6">
          <button type="button" class="btn btn-primary btn-block" onclick="location.href='menuActasCursado.php'">Nueva Consulta</button>
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
<!-- Modal -->
<?php include_once('./html/cambiarPassword.html');?>
  
<!-- FOOTER -->
<?php
    include_once('../app/views/footer.html');
?>

<!-- JAVASCRIPT LIBRARIES-->
<?php 
    include("../app/views/script_jquery.html");
?>


<!-- JAVASCRIPT CUSTOM -->
<script>

$(function () {
    let turno;
    let datos_turno;
    let titulo;
    let carrera;
    let arreglo_carreras;

    
    
    let calendario_evento = '<?=$calendario_descripcion?> (<?=$calendario_id?>)';

    if (<?=$bandInscripcionCursadoActivo;?>) {
       titulo = "Inscripcion: <font color='red'>"+calendario_evento+"</font>";
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
              alert("no coinciden las contraseñas");
         }
      } else {
          alert("existen campos nulos");
      }
});

$( "#idCambioPwd" ).on('shown.bs.modal', function (e) {
     $("#img_captcha").attr('src', '../app/lib/CaptchaSecurityImages.php?width=90&height=30&characters=5');
});

$("#idCambioPwd").on('hide.bs.modal', function(){
     $("#msg_restablecer").addClass("d-none");
     $('#inputPasswordNueva').prop("disabled",false); $('#inputPasswordNueva').val("");
     $('#inputRePasswordNueva').prop("disabled",false); $('#inputRePasswordNueva').val("");
     $('#inputCaptcha').prop("disabled",false); $('#inputCaptcha').val("");
});



//***********************************************************
// RETORNA LAS CARRERAS ACTIVAS 
//***********************************************************
function getCarrerasHabilitadas() {
   var evento;
   $.ajax({
      url:"./funciones/carrerasHabilitadasCursadoSelect.php",
      type:"POST",
      dataType : 'json',
      async: false,
      success: function(datos){
         evento = datos;
      }
    });
   return evento;
}


function cargaMateriasConInscriptosCursadoPorCarrera(val)
    {
        if (val!=0) {
            let parametros = val;
            let p = {"parametros":parametros}

            $.get("./funciones/generarMateriasConInscriptosCursadoPorCarrera.php",p,function (resul){
                $("#resultado").html(resul);
            });
        } else {
            alert('debe seleccionar');
        }
    }


function cargaInscriptosCursadoPorMateria(materia_id,materia_nombre)
    {
        //alert(materia_nombre+'*'+materia_id);
        if (materia_id!=0) {
            let parametros = {"materia_id":materia_id,"materia_nombre":materia_nombre}

            $.post("./funciones/alumnosInscriptosCursadoPorIdMateria.php",parametros,function (resul){
                $("#resultado").html(resul);
            });
        } else {
            alert('debe seleccionar');
        };
    }


</script>

</body>
</html>