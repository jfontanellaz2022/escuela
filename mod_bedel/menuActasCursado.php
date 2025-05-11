<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once 'verificarCredenciales.php';
require_once 'Sanitize.class.php';
require_once 'ArrayHash.class.php';
require_once 'CalendarioAcademico.php';
require_once 'Constantes.php';
/*
$objCalendario = new CalendarioAcademico();
$ARRAY_INSCRIPCION = $objCalendario->getLastInscripcionExamen();
$ARRAY_TURNO = $objCalendario->getLastTurnoExamen();
//var_dump($ARRAY_TURNO);exit;
$cantidad_llamados = 1;
$turno_id = $inscripcion_activa = $inscripcion_asociada = 0;
if ( $ARRAY_INSCRIPCION['codigo']==Constantes::CODIGO_INSCRIPCION_PRIMER_TURNO || 
     $ARRAY_INSCRIPCION['codigo']==Constantes::CODIGO_INSCRIPCION_TERCER_TURNO ) {
      $inscripcion_activa = $ARRAY_INSCRIPCION['id'];
      $inscripcion_asociada = $ARRAY_INSCRIPCION['id'];
      $cantidad_llamados = 2;
      $turno_id = $ARRAY_TURNO['id'];
}*/

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
              <li class="breadcrumb-item active" aria-current="page">Actas Exámenes</li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="img form">
    <div class="jumbotron jumbotron-fluid rounded form2">
      <div class="container justifu+y-content-center">
        <h2 class="display-5">Actas de Cursado del Año en Curso</h2>
        <hr>
    <form id="form">
        
      
    
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
          <strong>Fecha del Acta</strong>
          <input type="text"  class="form-control datepicker" id="inputFecha" autocomplete="off" placeholder="dd/mm/aaaa" required>
        </div>
      </div>
        
      <div class="form-row">
        <div class="form-group col-md-6">
          <button type="button" class="btn btn-primary btn-block" onclick="cargaMateriasConInscriptosPorCarrera()">Aceptar</button>
        </div>
      </div>
      
      <div class="form-row">
        <div class="form-group col-md-6">
          <button type="button" class="btn btn-primary btn-block" onclick="location.href='home.php?token=<?=$_SESSION['token'];?>'">Volver</button>
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
    let titulo;
    let carrera;
    let arreglo_carreras;

    $('.datepicker').datepicker({
      dateFormat: 'dd/mm/yy',
      showButtonPanel: false,
      changeMonth: false,
      changeYear: false,
      /*showOn: "button",
      buttonImage: "images/calendar.gif",
      buttonImageOnly: true,
      minDate: '+1D',
      maxDate: '+3M',*/
      inline: true
    }).datepicker("setDate", new Date());;
  
    $.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '<Ant',
    nextText: 'Sig>',
    currentText: 'Hoy',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
    weekHeader: 'Sm',
    dateFormat: 'dd/mm/yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''
  };
  $.datepicker.setDefaults($.datepicker.regional['es']);
    /**** SACA LAS CARRERAS HABILITADAS ******/
    carreras = getCarrerasHabilitadas();
       $.each(carreras, function(i, item) {
           $('#selectCarreras').append("<option value='"+item.id+"' >"+item.descripcion+" ("+item.id+")</option>");
       });
    
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
      url:"./funciones/getCarrerasHabilitadas.php?token=<?=$_SESSION['token'];?>",
      type:"POST",
      dataType : 'json',
      async: false,
      success: function(response){
         evento = response.datos;
      }
    });
   return evento;
}


function cargaMateriasConInscriptosPorCarrera() {
        let carrera = $("#selectCarreras").val();
        let fecha = $("#inputFecha").val();
        if (carrera!="" && fecha!="") {
            let p = {"carrera":carrera, "fecha":fecha}
            $.post("./funciones/generarMateriasConInscriptosCursadoPorCarrera.php?token=<?=$_SESSION['token'];?>",p,function (resul){
                $("#resultado").html(resul);
            });
        } else {
            alert('Debe seleccionar alguna de las opciones.');
        }
}






</script>

</body>
</html>