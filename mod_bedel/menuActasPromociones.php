<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once 'verificarCredenciales.php';
require_once 'Sanitize.class.php';
require_once 'ArrayHash.class.php';
require_once 'CalendarioAcademico.php';
require_once 'Constantes.php';

$objCalendario = new CalendarioAcademico();
$ARRAY_INSCRIPCION = $objCalendario->getLastInscripcionExamen();
$ARRAY_TURNO = $objCalendario->getLastTurnoExamen();

$fecha_desde = $ARRAY_INSCRIPCION['fecha_inicio'];
$fecha_hasta = $ARRAY_TURNO['fecha_final'];
$fecha_actual = date('Y-m-d');
//var_dump($fecha_desde,$fecha_hasta,$fecha_actual);exit;

if($fecha_actual <= $fecha_desde && $fecha_actual<=$fecha_hasta) {
   // echo "Garantia activa";
} else {
    //echo "Garantia inactiva";
}

$turno_id = $inscripcion_activa = 0;

$inscripcion_activa = $ARRAY_INSCRIPCION['id'];
$turno_id = $ARRAY_TURNO['id'];





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
              <li class="breadcrumb-item active" aria-current="page">Actas Promociones</li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="img form">
    <div class="jumbotron jumbotron-fluid rounded form2">
      <div class="container justifu+y-content-center">
        <h2 class="display-5">Actas de Promociones  (<?=$inscripcion_activa?>)</h2>
        <hr>
    <form id="form">
    
      <div class="form-row">  
        <div class="form-group col-md-6">
          <strong>Carrera</strong>
          <select name="selectCarreras" id="selectCarreras"  class="form-control">
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
          <button type="button" class="btn btn-primary btn-block" onclick="cargaMateriasConPromocionadosPorCarrera()">Aceptar</button>
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
    let turno;
    let datos_turno;
    let titulo;
    let carrera;
    let arreglo_carreras;

    let inscripcion_activa_id = <?=$inscripcion_activa?>;
    let turno_id = <?=$turno_id?>;

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

    if (turno_id!='') {
       titulo = "Turno de Examen: <font color='red'>"+inscripcion_activa_id+"</font>";
       carreras = getCarrerasHabilitadas();
       $.each(carreras, function(i, item) {
           $('#selectCarreras').append("<option value='"+item.id+"_"+inscripcion_activa_id+"' >"+item.descripcion+" ("+item.id+")</option>");
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


function cargaMateriasConPromocionadosPorCarrera()
    {
        let val = "";
        val = $("#selectCarreras").val();
        if (val!=0) {
            let parametros = val+'_'+$("#inputFecha").val();
            let p = {"parametros":parametros}

            $.get("./funciones/generarMateriasConPromocionadosPorCarrera.php?token=<?=$_SESSION['token'];?>",p,function (resul){
                $("#resultado").html(resul);
            });
        } else {
            alert('debe seleccionar');
        }
    }




//***********************************************
// RETORNA EL EVENTO CON TODOS SUS DATOS
//***********************************************
function getDatosEventoPorCodigo(codigo) {
   var datos = {"codigo":codigo};
   var evento;
   $.ajax({
      url:"./funciones/getFechasEventoPorCodigo.php?token=<?=$_SESSION['token'];?>",
      type:"POST",
      data: datos,
      dataType : 'json',
      async: false,
      success: function(datos){
         evento = datos;
      }
});
return evento;
}

//***********************************************************
// RETORNA EL TURNO DE EXAMENES ACTIVO O EN CURSO SI LO HAY 
//***********************************************************
function getTurnoActivo() {
   let evento_habilitado = "No";
   let evento_codigo = "";
   let calendario_id = "";
   let cantidad_llamados = "";
   let ultima_inscripcion_id = "";
   let datos;
   for (i=1001;i<=1004;i++) {
         datos_evento = getDatosEventoPorCodigo(i);
         if (datos_evento.codigo==100) {
            if (datos_evento.habilitado=='Si') {
               evento_habilitado = 'Si';
               evento_codigo = i;
               calendario_id = datos_evento.data[0].id;
               ultima_inscripcion_id = datos_evento.id_ultima_inscripcion;
               if (i==1001 || i==1003) {
                  cantidad_llamados = 2;
               } else {
                  cantidad_llamados = 1;
               }
               break;
            } else {
               evento_habilitado = 'No';
            }
         
      };
   };

   datos = {"habilitado":evento_habilitado, "evento_codigo":evento_codigo, "calendario_id":calendario_id, "cantidad_llamados":cantidad_llamados,"calendario_id_inscripcion_asociada":ultima_inscripcion_id};
   return datos;
}


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


//***********************************************************
// RETORNA EL LLAMADO ACTIVO DE UN TURNO EXAMENES SI LO HAY 
//***********************************************************
function getLlamadoActivo() {
   let evento_habilitado = "No";
   let evento_codigo = "";
   let calendario_id = "";
   let cantidad_llamados = "";
   let datos;
   for (i=1020;i<=1021;i++) {
         datos_evento = getDatosEventoPorCodigo(i);
         if (datos_evento.codigo==100) {
            if (datos_evento.habilitado=='Si') {
               evento_habilitado = 'Si';
               evento_codigo = i;
               calendario_id = datos_evento.data[0].id;
               break;
            } else {
               evento_habilitado = 'No';
            }
         
      };
   };

   datos = {"habilitado":evento_habilitado, "evento_codigo":evento_codigo, "calendario_id":calendario_id, "cantidad_llamados":cantidad_llamados};
   return datos;
}


</script>

</body>
</html>