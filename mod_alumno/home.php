<?php

set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');

require_once "verificarCredenciales.php";

$id_url = "menu_home";

?>



<!doctype html>
<html lang="es">
<head>
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
    <div id="titulo">
    </div>
  </article>

  <article class="container">
       <section id="section_principal">
        </section>
  </article>


  <article class="container">
       <section id="section_footer">
        </section>
  </article>

<!-- Modal -->
<?php include_once('./html/cambiarPassword.html');?>

<!-- Modal -->
<?php include_once('./html/cambiarPasswordObligatorio.html');?>

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
    load();
    if (password_vencida=='Si') {
          $('#idCambioPwdObligatorio').modal({
                        backdrop: 'static',
                        keyboard: false, 
                        show: true
                });
    }
});


function load() {
    $.get("./html/notice.html", function(data) {
            $("#section_principal").html(data);
    });
    $("#section_footer").html("");
}

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
              $("#msg_restablecer").removeClass("d-none");
              $("#msg_restablecer").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong>&nbsp;No coinciden las contraseñas.</div>');
         }
      } else {
          $("#msg_restablecer").removeClass("d-none");
          $("#msg_restablecer").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong>&nbsp;Existen campos vacíos.</div>');
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

$('#btnCambiarPasswordO').click(function(event) {
      let password = $('#inputPasswordNuevaO').val();
      let rePassword = $('#inputRePasswordNuevaO').val();
      let captcha = $('#inputCaptchaO').val();

      let parametros = {'password':password, "repassword": rePassword, "captcha":captcha}
      let link = "../API/cambiarPassword.php?token=<?=$_SESSION['token'];?>";

      if (password!="" && rePassword!="" && captcha!="") {
          if (password==rePassword) {
                  $.post(link,parametros,function(response) {
                       $("#msg_restablecerO").removeClass("d-none");
                       $("#msg_restablecerO").html('<div class="alert alert-'+response.class+'" role="alert"><strong>Atención:</strong>&nbsp;'+response.mensaje+'</div>');
                       if (response.codigo==200) {
                            $('#inputPasswordNuevaO').prop("disabled",true);
                            $('#inputRePasswordNuevaO').prop("disabled",true);
                            $('#inputCaptchaO').prop("disabled",true);
                            $('#btnCambiarPasswordO').prop("disabled",true);
                            setTimeout(function(){
                               $('#idCambioPwdObligatorio').modal('hide');
                            },1500); 
                            
                       }
                  },"json");
         } else {
              $("#msg_restablecerO").removeClass("d-none");
              $("#msg_restablecerO").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong>&nbsp;No coinciden las contraseñas.</div>');
         }
      } else {
          $("#msg_restablecerO").removeClass("d-none");
          $("#msg_restablecerO").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong>&nbsp;Existen campos vacíos.</div>');
      }
});

$( "#idCambioPwdObligatorio" ).on('shown.bs.modal', function (e) {
     $("#img_captchaO").attr('src', '../app/lib/CaptchaSecurityImages.php?width=90&height=30&characters=5');
});

$("#idCambioPwdObligatorio").on('hide.bs.modal', function(){
     $("#msg_restablecerO").addClass("d-none");
     $('#inputPasswordNuevaO').prop("disabled",false); $('#inputPasswordNuevaO').val("");
     $('#inputRePasswordNuevaO').prop("disabled",false); $('#inputRePasswordNuevaO').val("");
     $('#inputCaptchaO').prop("disabled",false); $('#inputCaptchaO').val("");
});


function expired() {

  location.href = "./logout.php";

}



setTimeout(expired, 60000*20);



</script>



</script>



</body>

</html>

