<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'../conexion/'.PATH_SEPARATOR.'./');

require_once 'Sanitize.class.php';
require_once 'ArrayHash.class.php';
require_once 'verificarCredenciales.php';
require_once 'Carrera.php';


$obj = new Carrera();

$arr_carreras_habilitadas_registracion = $obj->getCarrerasHabilitadasRegistracion();


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
          <button type="button" class="btn btn-primary btn-block" onclick="location.href='home.php?token=<?=$_SESSION['token'];?>'">Volver</button>
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
    let arreglo_carreras;
   
    arreglo_carreras = <?php echo json_encode($arr_carreras_habilitadas_registracion);?>;
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
            $.post("./funciones/getAlumnosCompletoPorIdCarrera.php?token=<?=$_SESSION['token'];?>",param,function(data) {
                $("#resultado_accion").html(`<table id="tabla" class="table"><thead>
                                                <tr>
                                                  <th scope="col" colspan=6>Listado de Inscriptos&nbsp;
                                                           <a class="btn btn-secondary" href="./funciones/getAlumnosCompletoPorIdCarreraDescarga.php?anio=`+anio+`&carrera_id=`+carrera_id+`" target="_blank">
                                                              Descargar&nbsp;<img src="../public/img/icons/excel_icon.png" width="25">
                                                           </a>
                                                  </th>
                                                </tr>
                                                <tr>
                                                  <th scope="col">#</th>
                                                  <th scope="col">Nombre</th>
                                                  <th scope="col">DNI</th>
                                                  <th scope="col">Email</th>
                                                  <th scope="col">Telefono</th>
                                                  <th scope="col">Localidad</th>
                                                </tr>
                                              </thead><tbody></tbody></table>`);
                if (data.codigo==200) {
                    let cont = 0;
                    $.each(data.datos, function(i, item) {
                        cont++;
                        if (item.anio==anio) {
                            let wsp = '<a href="https://api.whatsapp.com/send/?phone=549'+
                                        item.telefono_caracteristica+item.telefono_numero+
                                        '&text=Hola&type=phone_number&app_absent=0" target="_blank"><img src="../public/img/icons/wsp_icon.png" width="20"></a>';
                            tr="<tr><td>"+cont+"</td><td>"+item.apellido+', '+item.nombre+" ("+item.id+")</td><td>"+item.dni+
                                "</td><td>"+item.email+"</td><td> "+wsp+" ("+item.telefono_caracteristica+") "+
                                item.telefono_numero+"</td><td>"+item.localidad_nombre+" ("+item.provincia_nombre.toUpperCase()+")</td></tr>";
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
/*function getCarrerasHabilitadas() {
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
}*/


</script>

</body>
</html>