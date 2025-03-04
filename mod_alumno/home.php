<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "verificarCredenciales.php";
$id_url = "menu_home";
?>

<!doctype html>
<html lang="es">
<head>
  <title>Escuela Normal Superior 40 - Gestion de Alumnado</title>
  <link rel="shortcut icon" href="../../public/img/favicon.png">
  
  <?php include_once('../app/views/header.html');?>
  
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
              <li class="breadcrumb-item active" aria-current="page">Home</li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="container">
    <div id="control_habilitado" style='display:none'></div>
    <div id="cargar_regularidades_habilitado" style='display:none'></div>
    <div id="titulo"></div>
    <hr>
  </article>

  <article class="container">
       <section>
            <div class="row" id="filtro"></div><!-- Cierra Row-->
            <div class="row" id="resultado">
				<div class="col-xs-12 col-sm-12 col-md-3">&nbsp;</div>
				<div class="col-xs-12 col-sm-12 col-md-6">
				    <img src="../public/img/esc40a.jpg">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-3"></div>
			</div><!-- Cierra Row-->
            <div class="row" id="resultado_accion">&nbsp;</div><!-- Cierra Row-->
        </section>
  </article>


  <article class="container">
       <section>
           <div class="row" id="resultado"></div><!-- Cierra Row-->
           <div class="row" id="controles"></div><!-- Cierra Row-->
        </section>
  </article>

<!-- Modal -->
<?php include_once('./html/cambiarPassword.html');?>

<!-- FOOTER -->
<?php include("../app/views/footer.html");?>

<!-- JAVASCRIPT LIBRARIES-->
<?php include("../app/views/script_jquery.html");?>

<!-- JAVASCRIPT CUSTOM -->
<script>

let password_vencida = '<?=$_SESSION['arreglo_datos_usuario']['password_vencida'];?>';

$(function () {
    $('[data-toggle="popover"]').popover({
        html: true,
        sanitize: false,
    });

    if (password_vencida=='Si') {
       $( "#idCambioPwd").modal("show");
    }

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

function expired() {
  //location.href = "./logout.php";
}

setTimeout(expired, 60000*20);

</script>

</script>
<script src="./js/cargarCarrerasExamenes.js"></script>

</body>
</html>
