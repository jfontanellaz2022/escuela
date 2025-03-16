<?php
  set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib'.PATH_SEPARATOR.'./');
  require_once "verificarCredenciales.php";
  $id_pagina = 'carreras';
?>

<!doctype html>
<html lang="es">
  <head>
  <?php
      include_once('../app/views/header.html');
  ?>
<style>

.select2-container .select2-selection--single {
    height: 39px !important;
}

 

</style>   
</head>


<body>
  <!-- NAVBAR -->
  <header>
    <?php include("navbar.php");?>

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="../public/css/footerProfesor.css" />

  </header>

  <article>
    <div id="breadcrumb">
      
      <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#" onclick="cargarHome()">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Carreras</li>
                </ol>
      </nav>

      <h1><i>&nbsp;Carreras</i></h1>
      <h3>&nbsp;Gestionar Examenes Finales de Alumnos</h3>

    </div>
  </article>

  <article class="container-fluid">
    <div id="control_habilitado" style='display:none'></div>
    <div id="cargar_regularidades_habilitado" style='display:none'></div>
    <div id="titulo"></div>
  </article>

  <article class="container-fluid">
       <section>
           <div class="row" id="resultado">

                  <div class="col-md-4">
                        <div class="card" style="width: 18rem;">
                                  <img src="../public/img/logo_100x100.jpg" class="card-img-top">
                                  <div class="card-body">
                                      <h5 class="card-title"><img src="../public/img/icons/add2_icon.png" width="23">&nbsp;Nueva vinculación a Carrera</h5>
                                          <h6 class="card-subtitle mb-2 text-muted"></h6>
                                      <p class="card-text"></p>
                                      <button class="btn btn-primary btn-block" onclick="vincularCarrera(`+idProfesor+`)">Vincularme</button>
                                  </div>
                        </div>
                  </div>


           </div><!-- Cierra Row-->
           <div class="row" id="controles"></div><!-- Cierra Row-->
        </section>
  </article>

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
   
   let carrera_id = '';
   let profesor_id = '<?=$_SESSION['id_profesor'];?>';
   let arr_eventos_activos = '';
   let materia_id = '';
   let materia_nombre = '';
   let opcion = 'cursado';
   let llamado_numero = '';
   let token = "<?=$_SESSION['token'];?>";
   let idUsuario = <?=$_SESSION['arreglo_datos_usuario']['id'];?>;  


   // JS CAMBIO DE USUARIO
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

$( "#idCambioUsuario" ).on('shown.bs.modal', function (e) {
     $("#img_captcha_usuario").attr('src', '../app/lib/CaptchaSecurityImages.php?width=90&height=30&characters=5');
});

$("#idCambioUsuario").on('hide.bs.modal', function(){
     $("#msg_restablecer_usuario").addClass("d-none");
     $('#inputUsuario').prop("disabled",false); $('#inputUsuario').val("");
     $('#inputCaptchaCambioUsuario').prop("disabled",false); $('#inputCaptchaCambioUsuario').val("");
     $('#btnCambiarUsuario').prop("disabled",false);
});


   function expired() {
      location.href = "./logout.php";
   }


   $(function () {
      cargarCarreras(profesor_id);
   });

   //setTimeout(expired, 60000*20);

</script>

<script src="./js/funciones.js"></script>
<script src="./js/gestionarCursado.js"></script>


</body>
</html>
