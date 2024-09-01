$(function () {
  cargarHome();
})

function cargarHome() {
  //Activo del Menu la opcion Alumnos
  $(".nav-item").removeClass("active");
  $(".nav-item-home").addClass("active");
  //Remuevo la class que me deshabilita
  $("#resultado").removeClass("disabledbutton");
    $.get( "html/cargarHome.html", function( data ) {
       let titulo = '<h1 style="font-size:4vw;"><i>&nbsp;Nuevo Sistema de Gesti&oacute;n de Alumnos</i></h1>';
       let nav = `<nav aria-label="breadcrumb" role="navigation">
                   <ol class="breadcrumb">
                     <li class="breadcrumb-item active">Home</li>
                   </ol>
                 </nav>`;
       $("#breadcrumb").html(nav);
       $("#titulo").html(titulo);
       $("#resultado").html(data);
    });
};

function cambiarPassword() {
  let url = "./html/cambiarPassword.html";
  $.get(url,function(data){
    $("#resultado").html(data);
  });
}

function guardarPassword() {
  let password_actual = $("#inputPasswordActual").val();
  let password_nueva = $("#inputPasswordNueva").val();
  let password_re_nueva = $("#inputRePasswordNueva").val();

  if (password_nueva==password_re_nueva) {
      let url = "./funciones/cambiarPassword.php";
      let parametros = {"password_actual":password_actual,"password_nueva":password_nueva,"password_re_nueva":password_re_nueva}; 

      //console.info(parametros);
      $.post(url,parametros,function(datos){
          if (datos.codigo==100) {
            $("#resultado_carga").html(`<div class="alert alert-dark" role="alert">
                                          <b><img src="../assets/img/icons/ok_icon.png" width="21">&nbsp;</b><span style="color: #000000;"><i>`+datos.data+`</i></span>
                                      </div>`);
            $("#inputPasswordActual").prop("readonly",true);
            $("#inputPasswordNueva").prop("readonly",true);
            $("#inputRePasswordNueva").prop("readonly",true);
            $("#btnGuardarPassword").prop("disabled",true);                          
          } else {
            $("#resultado_carga").html(`<div class="alert alert-dark" role="alert">
            <b><img src="../assets/img/icons/alert_icon.png" width="21">&nbsp;</b><span style="color: #000000;"><i>`+datos.data+`</i></span>
        </div>`);
          }
          
      },"json");
} else {
      $("#resultado_carga").html(`<div class="alert alert-dark" role="alert">
                              <b><img src="../assets/img/icons/alert_icon.png" width="21">&nbsp;&nbsp;</b><span style="color: #000000;">
                              <i>No Coincide la Nueva Contrase&ntilde;a con su Repetici&oacute;n.</i></span>
                                </div>`);
}
}