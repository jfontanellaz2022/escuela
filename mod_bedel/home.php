<?php 
set_include_path('./');
require_once 'verificarCredenciales.php';
$dni = 24912834;

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
  

<!-- FOOTER -->
<?php
    include_once('../app/views/footer.html');
?>

<!-- JAVASCRIPT LIBRARIES-->
<?php 
    include("../app/views/script_jquery.html");
?>

<script>
$(function () {
    load();
});

function load() {
    $.get("./html/notice.html", function(data) {
            $("#section_principal").html(data);
    });
    $("#section_footer").html("");
}
  

function cambiarPassword() {
    $.get("./html/passwordModificar.html?token=<?=$_SESSION['token'];?>", function(data) {
            $("#section_principal").html(data);
    });
}

function guardarPassword() {
    let password_actual = $("#inputPasswordActual").val();
    let password_nueva = $("#inputPasswordNueva").val();
    let password_re_nueva = $("#inputRePasswordNueva").val();
    let parametros = {"dni":<?=$dni?>,"password_actual":password_actual,"password_nueva":password_nueva,"password_re_nueva":password_re_nueva}
    if ( password_nueva==password_re_nueva) {
        $.post("./funciones/passwordModificar.php?token=<?=$_SESSION['token'];?>",parametros,function(datos){
                if (datos.codigo == 100) {
                    $("#section_footer").html();
                    $("#section_footer").append(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../public/img/icons/ok_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                                `+datos.mensaje+`</span></i>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button></div></div>`);
                    habilitarControles(true);                             
                } else {
                    $("#section_footer").html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../public/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                                `+datos.mensaje+`</span></i>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button></div></div>`);
                    habilitarControles(false);
                }
        },"json");
    } else {
        $("#section_footer").html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../public/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                                La contraseña no coincide con la repetición.</span></i>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button></div></div>`);
        habilitarControles(true);
    }    

};

function habilitarControles(val) {
    $("#inputPasswordActual").attr("disabled",val);
    $("#inputPasswordNueva").attr("disabled",val);
    $("#inputRePasswordNueva").attr("disabled",val);
    $("#btnVerEditar").attr("disabled",val);
}

</script>
</body>
</html>