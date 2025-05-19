<?php
   set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib'.PATH_SEPARATOR.'./');

   include_once "verificarCredenciales.php";
   require_once "Profesor.php";
   require_once "CalendarioAcademico.php";

   //var_dump($_SESSION['arreglo_datos_usuario']);exit;
   $id_pagina = 'home';
   $_SESSION['id_persona'] = $_SESSION['arreglo_datos_usuario']['idPersona'];
   $objProfesor = new Profesor();
   $_SESSION['id_profesor'] = $objProfesor->getProfesorByIdPersona($_SESSION['id_persona'])['id'];

   // Determina si el Evento Armado de Lista de Materias de un Cuatrimestre dado esta habilitado en la fecha de hoy.
   $cal = new CalendarioAcademico;
   $_SESSION['ARRAY_CODIGOS_EVENTOS_ARMADO_LISTADOS_MATERIAS_ACTIVOS'] = $cal->getEventosArmadoMateriasActivo();
   
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

<!-- Modal -->
<?php include_once('./html/cambiarUsuario.html');?>

<!-- FOOTER -->
<?php include_once('../app/views/footer.html');?>

<!-- JAVASCRIPT LIBRARIES-->
<?php include("../app/views/script_jquery.html");?>

<!-- JAVASCRIPT CUSTOM -->
<script>
  let idUsuario = <?=$_SESSION['arreglo_datos_usuario']['id'];?>;     
  let password_vencida = '<?=$_SESSION['arreglo_datos_usuario']['password_vencida'];?>';
  
  // LOAD
  $(function () {
    $('[data-toggle="popover"]').popover({
        html: true,
        sanitize: false,
    })
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


// EXPIRED
function expired() {
  location.href = "../logout.php";
}

setTimeout(expired, 60000*20);


// CAMBIO CONTRASEÑA OPCIONAL
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
     $('#img_captcha').attr('width',"90");
     $('#img_captcha').attr('height',"24");
     $("#img_captcha").attr('src', '../app/lib/CaptchaSecurityImages.php?width=90&height=25&characters=5');
});

$("#idCambioPwd").on('hide.bs.modal', function(){
     $("#msg_restablecer").addClass("d-none");
     $('#inputPasswordNueva').prop("disabled",false); $('#inputPasswordNueva').val("");
     $('#inputRePasswordNueva').prop("disabled",false); $('#inputRePasswordNueva').val("");
     $('#inputCaptcha').prop("disabled",false); $('#inputCaptcha').val("");
});

$("body").on("click",".img",function(e){
           e.preventDefault();
           if ( $('#inputPasswordNueva').attr('type')=='password') {
                $('#inputPasswordNueva').attr('type', 'text');
                $('#inputRePasswordNueva').attr('type', 'text');
                $('#imgPassword1').attr('width',"23");
                $('#imgPassword2').attr('width',"23");
                $('#imgPassword1').attr('src',"../public/img/icons/eye_closed.png");
                $('#imgPassword2').attr('src',"../public/img/icons/eye_closed.png");
           } else if ($('#inputPasswordNueva').attr('type')=='text') {
                $('#inputPasswordNueva').attr('type', 'password');
                $('#inputRePasswordNueva').attr('type', 'password');
                $('#imgPassword1').attr('width',"23");
                $('#imgPassword2').attr('width',"23");
                $('#imgPassword1').attr('src',"../public/img/icons/eye_open.png");
                $('#imgPassword2').attr('src',"../public/img/icons/eye_open.png");

           }
})
// FIN CAMBIO CONTRASEÑA OPCIONAL


// CAMBIO CONTRASEÑA OBLIGATORIO
$( "#idCambioPwdObligatorio" ).on('shown.bs.modal', function (e) {
     $('#img_captchaO').attr('width',"90");
     $('#img_captchaO').attr('height',"24");
     $("#img_captchaO").attr('src', '../app/lib/CaptchaSecurityImages.php?width=90&height=25&characters=5');
});

$("#idCambioPwdObligatorio").on('hide.bs.modal', function(){
     $("#msg_restablecerO").addClass("d-none");
     $('#inputPasswordNuevaO').prop("disabled",false); $('#inputPasswordNuevaO').val("");
     $('#inputRePasswordNuevaO').prop("disabled",false); $('#inputRePasswordNuevaO').val("");
     $('#inputCaptchaO').prop("disabled",false); $('#inputCaptchaO').val("");
});

$("body").on("click",".imgO",function(e){
           e.preventDefault();
           if ( $('#inputPasswordNuevaO').attr('type')=='password') {
                $('#inputPasswordNuevaO').attr('type', 'text');
                $('#inputRePasswordNuevaO').attr('type', 'text');
                $('#imgPassword1o').attr('width',"23");
                $('#imgPassword2o').attr('width',"23");
                $('#imgPassword1o').attr('src',"../public/img/icons/eye_closed.png");
                $('#imgPassword2o').attr('src',"../public/img/icons/eye_closed.png");
           } else if ($('#inputPasswordNuevaO').attr('type')=='text') {
                $('#inputPasswordNuevaO').attr('type', 'password');
                $('#inputRePasswordNuevaO').attr('type', 'password');
                $('#imgPassword1o').attr('width',"23");
                $('#imgPassword2o').attr('width',"23");
                $('#imgPassword1o').attr('src',"../public/img/icons/eye_open.png");
                $('#imgPassword2o').attr('src',"../public/img/icons/eye_open.png");

           }
})

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

// FIN CAMBIO CONTRASEÑA OBLIGATORIO



// JS CAMBIO DE USUARIO
$( "#idCambioUsuario" ).on('shown.bs.modal', function (e) {
     $('#img_captcha_usuario').attr('width',"90");
     $('#img_captcha_usuario').attr('height',"24");
     $("#img_captcha_usuario").attr('src', '../app/lib/CaptchaSecurityImages.php?width=90&height=30&characters=5');
});

$("#idCambioUsuario").on('hide.bs.modal', function(){
     $("#msg_restablecer_usuario").addClass("d-none");
     $('#inputUsuario').prop("disabled",false); $('#inputUsuario').val("");
     $('#inputCaptchaCambioUsuario').prop("disabled",false); $('#inputCaptchaCambioUsuario').val("");
     $('#btnCambiarUsuario').prop("disabled",false);
});

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


  
</script>
</body>
</html>
