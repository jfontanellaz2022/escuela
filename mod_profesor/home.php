<?php
   set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib'.PATH_SEPARATOR.'./');

   include_once "verificarCredenciales.php";
   require_once "Profesor.php";
   require_once "CalendarioAcademico.php";

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
  <link rel="shortcut icon" href="../public/img/favicon.png">
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

<!-- FOOTER -->
<?php include_once('../app/views/footer.html');?>

<!-- JAVASCRIPT LIBRARIES-->
<?php include("../app/views/script_jquery.html");?>

<!-- JAVASCRIPT CUSTOM -->
<script>
  $(function () {
    $('[data-toggle="popover"]').popover({
        html: true,
        sanitize: false,
    })
    load();

    
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

  
</script>
</body>
</html>
