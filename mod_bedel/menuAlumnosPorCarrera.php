<?php
set_include_path('../app/lib/'.PATH_SEPARATOR.'../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
include_once 'ArrayHash.class.php';
require_once 'seguridad.php';

$bandExisteTurnoActivo = true;
if (isset($_SESSION['arrayTurnoActivo'])) {
    $sqlCarreras = "SELECT a.id, a.descripcion
                      FROM carrera a";
    $resultadoCarreras = mysqli_query($conex, $sqlCarreras);
    $_SESSION['carreras'] = array();
    while ($filaCarreras = mysqli_fetch_assoc($resultadoCarreras)) {
        $arreglo = array();
        array_push($arreglo, $filaCarreras['id'], $filaCarreras['descripcion']);
        array_push($_SESSION['carreras'], $arreglo);
    };

    $cantidadLlamados = $_SESSION['arrayTurnoActivo'][4];
    $descripcionTurno = $_SESSION['arrayTurnoActivo'][2];
    $idTurno = $_SESSION['arrayTurnoActivo'][0];
} else {
    $bandExisteTurnoActivo = false;
}
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SiGeAl - Bedelia</title>
   <?php include_once('componente_header.html'); ?>
   <?php include("componente_script_jquery.html"); ?>
  
  
    <style>
    .dropdown-item:hover{
          background-color: #CFC290;
        }        
     footer.nb-footer {
        background: #222;
        border-top: 4px solid #b78c33; }
    footer.nb-footer .about {
        margin: 0 auto;
        margin-top: 30px;
        max-width: 1170px;
        text-align: center; }
    footer.nb-footer .about p {
        font-size: 13px;
        color: #999;
        margin-top: 30px; }
    footer.nb-footer .about .social-media {
        margin-top: 15px; }
    footer.nb-footer .about .social-media ul li a {
        display: inline-block;
        width: 45px;
        height: 45px;
        line-height: 45px;
        border-radius: 50%;
        font-size: 16px;
        color: #b78c33;
        border: 1px solid rgba(255, 255, 255, 0.3); }
    footer.nb-footer .about .social-media ul li a:hover {
        background: #b78c33;
        color: #fff;
        border-color: #b78c33; }
    footer.nb-footer .footer-info-single {
        margin-top: 30px; }
    footer.nb-footer .footer-info-single .title {
        color: #aaa;
        text-transform: uppercase;
        font-size: 16px;
        border-left: 4px solid #b78c33;
        padding-left: 5px; }
    footer.nb-footer .footer-info-single ul li a {
        display: block;
        color: #aaa;
        padding: 2px 0; }
    footer.nb-footer .footer-info-single ul li a:hover {
        color: #b78c33; }
    footer.nb-footer .footer-info-single p {
        font-size: 13px;
        line-height: 20px;
        color: #aaa; }
    footer.nb-footer .copyright {
        margin-top: 15px;
        background: #111;
        padding: 7px 0;
        color: #999; }
    footer.nb-footer .copyright p {
        margin: 0;
        padding: 0; }
    .thead-green {
        background-color: rgb(0, 99, 71);
        color: white;
    }
    .disabledbutton {
          pointer-events: none;
          opacity: 0.5;
      }

    .input-form {
          border: 1px solid black;
          border-radius: 2px;
          height: 36px !important;
    }

   </style>    
</head>
<body>
 

 
 <!-- NAVBAR -->
 <header>
    <?php include("componente_navbar.php"); ?>
  </header>

  <article>
    <div id="breadcrumb">
      <nav aria-label="breadcrumb" role="navigation">
          <ol class="breadcrumb">
              <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Listado de Alumnos</li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="img form">
    <div class="jumbotron jumbotron-fluid rounded form2">
      <div class="container justifu+y-content-center">
        <h2 class="display-5">Listado de Alumnos</h2>
        <hr>
    <form id="form">
      <div class="form-row">  
        <div class="form-group col-md-6">
          <strong>Carrera</strong>
          <select name="selectCarreras" id="selectCarreras"  class="form-control" required>
            <option value='0'> - Seleccione Carrera - </option>  
        </select>
        </div>
      </div>
        
      <div class="form-row">  
        <div class="form-group col-md-6">
          <strong>A&ntilde;o de Ingreso</strong>
            <input type="text" id="inputAnio" class="form-control" value="" maxlength="4" required> 
        </div>
      </div>
        
      <div class="form-row">
        <div class="form-group col-md-6">
          <button type="button" class="btn btn-primary btn-block" onclick="cargarAlumnos()">Aceptar</button>
        </div>
      </div>
      
      <div class="form-row">
        <div class="form-group col-md-6">
          <button type="button" class="btn btn-primary btn-block" onclick="location.href='home.php'">Volver</button>
        </div>
      </div>
    
    </form>
    
    </div>
    </div>
</article>

  <article class="container">
       <section>
            <div class="row" id="resultado">
                        
            </div><!-- Cierra Row-->
            <div class="row" id="resultado_accion"></div><!-- Cierra Row-->
        </section>
  </article>

  

<!-- FOOTER -->
<?php include("componente_footer.html"); ?>




<!-- JAVASCRIPT CUSTOM -->
<script>

$(function () {
    let turno;
    let datos_turno;
    let carrera;
    let arreglo_carreras;
    carreras = getCarrerasHabilitadas();
    arreglo_carreras = carreras.data;
    $.each(arreglo_carreras, function(i, item) {
           $('#selectCarreras').append("<option value='"+item.id+"' >"+item.descripcion+" ("+item.id+")</option>");
    });
});


function cargarAlumnos() {
    let carrera_id = $("#selectCarreras").val();
    let anio = $("#inputAnio").val();
    let param = {"carrera_id":carrera_id,"anio":anio};
    let tr = "";
    $("#resultado_accion").html("");
    if (anio!="") {
            $.post("./funciones/getAlumnosCompletoPorIdCarrera.php",param,function(data) {
                $("#resultado_accion").html(`<table id="tabla" class="table"><thead>
                                                <tr>
                                                  <th scope="col">#</th>
                                                  <th scope="col">Nombre</th>
                                                  <th scope="col">DNI</th>
                                                  <th scope="col">Email</th>
                                                  <th scope="col">Telefono</th>
                                                  <th scope="col">Localidad</th>
                                                </tr>
                                              </thead><tbody></tbody></table>`);
                if (data.codigo==100) {
                 
                $.each(data.datos, function(i, item) {
                     if (item.anioIngreso==anio) {
                         let wsp = '<a href="https://api.whatsapp.com/send/?phone=549'+
                                    item.telefono_caracteristica+item.telefono_numero+
                                    '&text=Hola&type=phone_number&app_absent=0" target="_blank"><img src="../public/img/icons/wsp_icon.png" width="20"></a>';
                         tr="<tr><td>"+item.id+"</td><td>"+item.apellido+', '+item.nombre+"</td><td>"+item.dni+
                            "</td><td>"+item.email+"</td><td> "+wsp+" ("+item.telefono_caracteristica+") "+
                            item.telefono_numero+"</td><td>"+item.localidad+" ("+item.provincia.toUpperCase()+")</td></tr>";
                         $("#tabla tbody").append(tr);  
                     };
                });
                } else {
                    
                }
                
            },"json");
    } else {
        $("#tabla").empty();
        $("#resultado_accion").html("No ingreso A&ntilde;o");
    }

}





//***********************************************************
// RETORNA LAS CARRERAS ACTIVAS 
//***********************************************************
function getCarrerasHabilitadas() {
   var evento;
   $.ajax({
      url:"./funciones/carrerasHabilitadasSelect.php",
      type:"POST",
      dataType : 'json',
      async: false,
      success: function(datos){
         evento = datos;
      }
    });
   return evento;
}


</script>

</body>
</html>