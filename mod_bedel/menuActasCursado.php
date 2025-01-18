<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'../conexion/'.PATH_SEPARATOR.'./funciones/'.PATH_SEPARATOR.'./');
require_once 'config.php';
require_once 'seguridad.php';
require_once 'conexion.php';
require_once 'Sanitize.class.php';
require_once 'ArrayHash.class.php';
require_once 'CalendarioAcademico.php';
require_once 'Carrera.php';

$evento_inscripcion_cursado = new CalendarioAcademico();
$evento_activo = $evento_inscripcion_cursado->getEventoActivoByCodigo(1023);
//var_dump($evento_activo);die;
$ARREGLO_CARRERAS = array();
$bandInscripcionCursadoActivo = FALSE;

$calendario_descripcion = "";
$calendario_id = 0;

if (!empty($evento_activo)) {
    
    $bandInscripcionCursadoActivo = TRUE;
    $obj_carrera = new Carrera();
    //$arr_carreras = $obj_carrera->getCarreras();

    //var_dump($arr_carreras);die;    

    foreach ($obj_carrera->getCarreras() as $item) {
        $arreglo = array();
        if ($item['habilitacion_cursado']=='Si') {
            array_push($arreglo, $item['id'], $item['descripcion']);
            array_push($ARREGLO_CARRERAS, $arreglo);
        }
    }
    
    /*$sqlCarreras = "SELECT a.id, a.descripcion
                    FROM carrera a";
    $resultadoCarreras = mysqli_query($conex, $sqlCarreras);
    $_SESSION['carreras'] = array();
    while ($filaCarreras = mysqli_fetch_assoc($resultadoCarreras)) {
        $arreglo = array();
        array_push($arreglo, $filaCarreras['id'], $filaCarreras['descripcion']);
        array_push($_SESSION['carreras'], $arreglo);
    }; */

    $calendario_descripcion = $evento_activo[0]['evento_descripcion'];
    $calendario_id = $evento_activo[0]['id'];
    //var_dump($calendario_descripcion.'*'.$calendario_id);die;
};

?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SiGeAl - Bedelia</title>
   <?php include_once('componente_header.html'); ?>
   <?php include("componente_script_jquery.html"); ?>
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
              <li class="breadcrumb-item active" aria-current="page">Actas Cursado</li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="img form">
    <div class="jumbotron jumbotron-fluid rounded form2">
      <div class="container justifu+y-content-center">
        <h2 class="display-5">Actas de Cursado</h2>
        <hr>
    <form id="form">
    
      <div class="form-row">  
        <div class="form-group col-md-6">
          <strong>Carrera</strong>
          <select name="selectCarreras" id="selectCarreras"  class="form-control" onchange="cargaMateriasConInscriptosCursadoPorCarrera(this.value)">
            <option value='0'> - Seleccione Carrera - </option>  
        </select>
        </div>
      </div>
        
      <div class="form-row">
        <div class="form-group col-md-6">
          <button type="button" class="btn btn-primary btn-block" onclick="location.href='menuActasCursado.php'">Nueva Consulta</button>
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
            <div class="row" id="filtro"></div><!-- Cierra Row-->
            <div class="row" id="resultado"></div><!-- Cierra Row-->
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
    let titulo;
    let carrera;
    let arreglo_carreras;

    
    
    let calendario_evento = '<?=$calendario_descripcion?> (<?=$calendario_id?>)';

    if (<?=$bandInscripcionCursadoActivo;?>) {
       titulo = "Inscripcion: <font color='red'>"+calendario_evento+"</font>";
       carreras = getCarrerasHabilitadas();
       arreglo_carreras = carreras.data;
       $.each(arreglo_carreras, function(i, item) {
           $('#selectCarreras').append("<option value='"+item.id+"' >"+item.descripcion+" ("+item.id+")</option>");
       });


    };
    
    //let titulo1 = "<h3><i><strong><u>CARRERA:</u></strong> "+datos_carrera+ "</i></h3>";
    //let titulo2 = "<h4>Listado de Alumnos</h4>";

    $("#titulo").html(titulo);
});





//***********************************************************
// RETORNA LAS CARRERAS ACTIVAS 
//***********************************************************
function getCarrerasHabilitadas() {
   var evento;
   $.ajax({
      url:"./funciones/carrerasHabilitadasCursadoSelect.php",
      type:"POST",
      dataType : 'json',
      async: false,
      success: function(datos){
         evento = datos;
      }
    });
   //console.info(evento); 
   return evento;
}


function cargaMateriasConInscriptosCursadoPorCarrera(val)
    {
        if (val!=0) {
            let parametros = val;
            let p = {"parametros":parametros}

            $.get("./funciones/generarMateriasConInscriptosCursadoPorCarrera.php",p,function (resul){
                $("#resultado").html(resul);
            });
        } else {
            alert('debe seleccionar');
        }
    }


function cargaInscriptosCursadoPorMateria(materia_id,materia_nombre)
    {
        //alert(materia_nombre+'*'+materia_id);
        if (materia_id!=0) {
            let parametros = {"materia_id":materia_id,"materia_nombre":materia_nombre}

            $.post("./funciones/alumnosInscriptosCursadoPorIdMateria.php",parametros,function (resul){
                $("#resultado").html(resul);
            });
        } else {
            alert('debe seleccionar');
        };
    }




/*
$("body").on("click","#botonFiltro",function(e) {
      e.preventDefault();
      let anio_lectivo = $("#inputFiltroAnioLectivo").val();
      let evento = $("#inputFiltroEvento").val();
      console.info(anio_lectivo+'-'+evento);
      let per_page = 10;
      let parametros = {"action": "listar","page": 1,"per_page": per_page,"anio":anio_lectivo,"codigo":evento};
      $("#titulo").html(titulo);
      $.ajax({
          url: 'funciones/calendarioListar.php',
          data: parametros,
          method: 'POST',
          success: function (data) {
              $("#resultado").html(data);
              $("#tabla_calendario>tfoot").prepend(`<tr>
                                                      <td colspan="5">
                                                          <button class="btn btn-primary" onclick="calendarioAgregar()">Nuevo</button>
                                                      </td>
                                                  </tr>`);
          }
      });

}) 


function loadFiltros() {
    $.get("./html/calendarioFiltro.html",function(datos) {
        $("#filtro").html(datos);
        $.post("./funciones/getAllEvento.php",function(datos_evento){
            if (datos_evento.codigo == 100) {
                datos_evento.data.forEach(evento => {
                      $("#inputFiltroEvento").append($('<option/>', {
                            text: '('+evento.codigo+') '+evento.descripcion,
                            value: evento.codigo,
                      }));
                });
                $('#inputFiltroEvento').select2({
                    theme: "bootstrap4",
                });
            } else {
                console.log("error codigo");
            }
        },"json");
    });
}    



function calendarioAgregar() {
    let titulo = `<h1><i><u>Calendario de Eventos</u></i></h1><h2>Agregar Evento al Calendario</h2>`;
    let bread = `<nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="#" onclick="">Home</a></li>
                      <li class="breadcrumb-item"><a href="#" onclick="cargarModulos()">Calendario</a></li>
                      <li class="breadcrumb-item">Agregar Evento </li>
                    </ol>
                  </nav>`;
    $("#filtro").html("");
    $("#breadcrumb").html(bread);
    $("#titulo").html(titulo);
    $.get("./html/calendarioAlta.html",function(datos) {
         $("#resultado").html(datos);
         $("#inputAltaFechaInicio").datepicker({dateFormat: "yy-mm-dd"});
         $("#inputAltaFechaFinalizacion").datepicker({dateFormat: "yy-mm-dd"});
         $.post("./funciones/getAllEvento.php",function(datosEventos) {
              if (datosEventos.codigo == 100) {
                  let obj = datosEventos.data; 
                  obj.forEach(evento => {
                        $("#inputAltaEvento").append($('<option/>', {
                              text: ' ('+evento.codigo+') '+evento.descripcion,
                              value: evento.id,
                        }));
                  });
                  $('#inputAltaEvento').select2({
                        theme: "bootstrap4",
                  });
              }
         },"json")
    });
}

function calendarioEditar(idCalendario) {
    let titulo = `<h1><i><u>Calendario de Eventos</u></i></h1><h2>Editar Evento del Calendario</h2>`;
    let bread = `<nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="#" onclick="">Home</a></li>
                      <li class="breadcrumb-item"><a href="#" onclick="cargarModulos()">Calendario</a></li>
                      <li class="breadcrumb-item">Editar Evento</li>
                    </ol>
                  </nav>`;
    let calendario_id = idCalendario;
    let anio_lectivo;
    let fecha_inicio;
    let fecha_finalizacion;
    let evento_id;       
    $("#filtro").html("");       
    $("#breadcrumb").html(bread);
    $("#titulo").html(titulo);

    let datos_calendario = getCalendarioPorId(calendario_id);
    if (datos_calendario.codigo == 100) {
        anio_lectivo = datos_calendario.data[0].AnioLectivo;
        fecha_inicio = datos_calendario.data[0].fechaInicioEvento;
        fecha_finalizacion = datos_calendario.data[0].fechaFinalEvento;
        evento_id = datos_calendario.data[0].idEvento;
    };
    $.get("./html/calendarioEditar.html",function(datos) {
         $("#resultado").html(datos);
         $("#inputEditarAnio").val(anio_lectivo);
         $("#inputEditarFechaInicio").val(fecha_inicio);
         $("#inputEditarFechaFinalizacion").val(fecha_finalizacion);
         $("#inputEditarFechaInicio").datepicker({dateFormat: "yy-mm-dd"});
         $("#inputEditarFechaFinalizacion").datepicker({dateFormat: "yy-mm-dd"});
         $("#inputEditarIdCalendario").val(calendario_id);
         $.post("./funciones/getAllEvento.php",function(datosEventos) {
              if (datosEventos.codigo == 100) {
                    let obj = datosEventos.data; 
                    obj.forEach(evento => {
                            $("#inputEditarEvento").append($('<option/>', {
                                text: ' ('+evento.codigo+') '+evento.descripcion,
                                value: evento.id,
                            }));
                    });
                    $("#inputEditarEvento option[value="+ evento_id +"]").attr("selected",true);
                    $('#inputEditarEvento').select2({
                        theme: "bootstrap4",
                    });
              };

          },"json")
    });
};

function calendarioEditarGuardar() {
    let anio_lectivo = $("#inputEditarAnio").val();
    let evento = $('#inputEditarEvento').val();
    let fecha_inicio = $("#inputEditarFechaInicio").val();
    let fecha_finalizacion = $("#inputEditarFechaFinalizacion").val();
    let calendario = $("#inputEditarIdCalendario").val();
    let parametros = {"anio":anio_lectivo,"evento":evento,"fecha_inicio":fecha_inicio,"fecha_finalizacion":fecha_finalizacion,"calendario":calendario};

    $.post("./funciones/updCalendario.php",parametros,function(datos){
        if (datos.codigo == 100) {
            cargarModulos();
            $("#resultado_accion").html();
            $("#resultado_accion").append(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../assets/img/icons/ok_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                           `+datos.data+`</span></i>
                                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                           <span aria-hidden="true">&times;</span>
                                         </button></div></div>`);
        } else {
            
            $("#resultado_accion").html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../assets/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                           `+datos.data+`</span></i>
                                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                           <span aria-hidden="true">&times;</span>
                                         </button></div></div>`);
        }
    },"json");

    
}


function calendarioAltaGuardar() {
    let anio_lectivo = $("#inputAltaAnio").val();
    let evento = $('#inputAltaEvento').val();
    let fecha_inicio = $("#inputAltaFechaInicio").val();
    let fecha_finalizacion = $("#inputAltaFechaFinalizacion").val();
    let parametros = {"anio":anio_lectivo,"evento":evento,"fecha_inicio":fecha_inicio,"fecha_finalizacion":fecha_finalizacion};
    $.post("./funciones/setCalendario.php",parametros,function(datos){
        if (datos.codigo == 100) {
            cargarModulos();
            $("#resultado_accion").html();
            $("#resultado_accion").append(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../assets/img/icons/ok_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                           `+datos.data+`</span></i>
                                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                           <span aria-hidden="true">&times;</span>
                                         </button></div></div>`);
        } else {
            
            $("#resultado_accion").html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../assets/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                           `+datos.data+`</span></i>
                                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                           <span aria-hidden="true">&times;</span>
                                         </button></div></div>`);
        }
    },"json");
   
}


function calendarioEliminar(idCalendario) {
    if (confirm("Va a Eliminar un Evento del Calendario, desea hacerlo?.")) {
        let parametros = {"calendario":idCalendario};
        $.post("./funciones/delCalendario.php",parametros,function(datos){
            if (datos.codigo == 100) {
                cargarModulos();
                $("#resultado_accion").html();
                $("#resultado_accion").append(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../assets/img/icons/ok_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                            `+datos.data+`</span></i>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button></div></div>`);
            } else {
                $("#resultado_accion").html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../assets/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                            `+datos.data+`</span></i>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button></div></div>`);
            }
        },"json");
    }
   
}

*/

</script>

</body>
</html>