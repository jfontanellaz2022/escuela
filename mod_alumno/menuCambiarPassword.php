<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel1.php";
//require_once "../app/lib/CaptchaSecurityImages.php";
$id_url = "menu_historia";
//echo $_SESSION['idAlumno'];die;
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
    <div id="titulo"></div>
    <hr>
  </article>

  <article class="container">
       <section>
           <div class="row" id="resultado">
           <div class="img form visible" id="alumno_editar">
  <div class="jumbotron jumbotron-fluid rounded form2">
    <div class="container justifu+y-content-center">
      <h1 class="display-4">Cambio de Contrase&ntilde;a</h1>
      <hr>
  <form id="form">
    
    <div class="form-row">  
      <div class="form-group col-md-6">
        <strong>Contrase&ntilde;a</strong>
        <input id="inputPasswordActual" placeholder="Password" type="password" class="form-control" minlength="8" maxlength="15" required autocomplete="off">
      </div>
    </div>
    
    <div class="form-row">  
      <div class="form-group col-md-6">
        <strong>Nueva Contrase&ntilde;a</strong>
        <input id="inputPasswordNueva" placeholder="Nueva Password" type="password" class="form-control" minlength="8" maxlength="15" required autocomplete="off">
      </div>
    </div>

    <div class="form-row">  
      <div class="form-group col-md-6">
        <strong>Repetir la nueva Contrase&ntilde;a</strong>
        <input id="inputRePasswordNueva" placeholder="Repetir Nueva Password" type="password" class="form-control" minlength="8" maxlength="15" required autocomplete="off">
      </div>
    </div>


    
  
    
    <div class="form-row">
       <div class="form-group col-md-6">
         <button id="btnGuardarPassword" type="button" class="btn btn-primary btn-block" onclick="guardarPassword()">Guardar</button>
       </div>
     </div>
     
    <div class="form-row">
       <div class="form-group col-md-6">
         <button id="" type="button" class="btn btn-danger btn-block" onclick="location.href='./home.php'">Volver</button>
       </div>
     </div> 
     
    

  </form>
  </div>
  </div>
</div>
           </div><!-- Cierra Row-->
           <div class="row" id="controles"></div><!-- Cierra Row-->
        </section>
  </article>

<!-- FOOTER -->
<?php include("../app/views/footer.html");?>

<!-- JAVASCRIPT LIBRARIES-->
<?php include("../app/views/script_jquery.html");?>

<!-- JAVASCRIPT CUSTOM -->

<script>

$("document").ready(function() {
   cargarCarrerasHistoria(<?=$_SESSION['idAlumno'];?>);
})

function expired() {
  location.href = "../logout.php";
}

setTimeout(expired, 60000*20);

function guardarPassword() {
  let password_actual = $("#inputPasswordActual").val();
  let password_nueva = $("#inputPasswordNueva").val();
  let password_re_nueva = $("#inputRePasswordNueva").val();
//alert(password_actual);
  if (password_actual!='' && password_nueva!='' && password_re_nueva!='' ) {
          if (password_nueva==password_re_nueva) {
              let url = "./funciones/cambiarPassword.php";
              let parametros = {"password_actual":password_actual,"password_nueva":password_nueva,"password_re_nueva":password_re_nueva}; 
        
              //console.info(parametros);
              $.post(url,parametros,function(datos){
                  if (datos.codigo==100) {
                    $("#controles").html(`<div class="alert alert-dark" role="alert">
                                                  <b><img src="../public/img/icons/ok_icon.png" width="21">&nbsp;</b><span style="color: #000000;"><i>`+datos.data+`</i></span>
                                              </div>`);
                    $("#inputPasswordActual").prop("readonly",true);
                    $("#inputPasswordNueva").prop("readonly",true);
                    $("#inputRePasswordNueva").prop("readonly",true);
                    $("#btnGuardarPassword").prop("disabled",true);                          
                  } else {
                    $("#controles").html(`<div class="alert alert-dark" role="alert">
                    <b><img src="../public/img/icons/alert_icon.png" width="21">&nbsp;</b><span style="color: #000000;"><i>`+datos.data+`</i></span>
                </div>`);
                  }
                  
              },"json");
        } else {
              $("#controles").html(`<div class="alert alert-danger" role="alert">
                                      <b><img src="../public/img/icons/alert_icon.png" width="21">&nbsp;&nbsp;</b><span style="color: #000000;">
                                      <i>No Coincide la Nueva Contrase&ntilde;a con su Repetici&oacute;n.</i></span>
                                        </div>`);
        }
} else {
    $("#controles").html(`<div class="alert alert-danger" role="alert">
                                      <b><img src="../public/img/icons/alert_icon.png" width="21">&nbsp;&nbsp;</b><span style="color: #000000;">
                                      <i>Existen campos de Textos que estan vacios.</i></span>
                                        </div>`);
}


}

</script>
</body>
</html>
