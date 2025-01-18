<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');

require_once 'verificarCredenciales.php';
require_once 'Sanitize.class.php';
require_once 'ArrayHash.class.php';
require_once 'CalendarioAcademico.php';
require_once 'Constantes.php';

$objCalendario = new CalendarioAcademico();
$ARRAY_INSCRIPCION = $objCalendario->getLastInscripcionExamen();
$ARRAY_TURNO = $objCalendario->getLastTurnoExamen();

$fecha_desde = $ARRAY_INSCRIPCION['fecha_inicio'];
$fecha_hasta = $ARRAY_TURNO['fecha_final'];
$fecha_actual = date('Y-m-d');
//var_dump($fecha_desde,$fecha_hasta,$fecha_actual);exit;

if($fecha_actual <= $fecha_desde && $fecha_actual<=$fecha_hasta) {
   // echo "Garantia activa";
} else {
    //echo "Garantia inactiva";
}

$turno_id = $inscripcion_activa = 0;

$inscripcion_activa = $ARRAY_INSCRIPCION['id'];
$turno_id = $ARRAY_TURNO['id'];





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
              <li class="breadcrumb-item active" aria-current="page">Actas Promociones</li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="img form">
    <div class="jumbotron jumbotron-fluid rounded form2">
      <div class="container justifu+y-content-center">
        <h2 class="display-5">Actas de Promociones  (<?=$inscripcion_activa?>)</h2>
        <hr>
    <form id="form">
    
      <div class="form-row">  
        <div class="form-group col-md-6">
          <strong>Carrera</strong>
          <select name="selectCarreras" id="selectCarreras"  class="form-control">
            <option value='0'> - Seleccione Carrera - </option>  
        </select>
        </div>
      </div>

      <div class="form-row">  
        <div class="form-group col-md-6">
          <strong>Fecha del Acta</strong>
          <input type="text"  class="form-control datepicker" id="inputFecha" autocomplete="off" placeholder="dd/mm/aaaa" required>
        </div>
      </div>
        
      <div class="form-row">
        <div class="form-group col-md-6">
          <button type="button" class="btn btn-primary btn-block" onclick="cargaMateriasConPromocionadosPorCarrera()">Aceptar</button>
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

    let inscripcion_activa_id = <?=$inscripcion_activa?>;
    let turno_id = <?=$turno_id?>;

    $('.datepicker').datepicker({
      dateFormat: 'dd/mm/yy',
      showButtonPanel: false,
      changeMonth: false,
      changeYear: false,
      /*showOn: "button",
      buttonImage: "images/calendar.gif",
      buttonImageOnly: true,
      minDate: '+1D',
      maxDate: '+3M',*/
      inline: true
    }).datepicker("setDate", new Date());;
  
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);

    if (turno_id!='') {
       titulo = "Turno de Examen: <font color='red'>"+inscripcion_activa_id+"</font>";
       carreras = getCarrerasHabilitadas();
       $.each(carreras, function(i, item) {
           $('#selectCarreras').append("<option value='"+item.id+"_"+inscripcion_activa_id+"' >"+item.descripcion+" ("+item.id+")</option>");
       });


    };
    
    //let titulo1 = "<h3><i><strong><u>CARRERA:</u></strong> "+datos_carrera+ "</i></h3>";
    //let titulo2 = "<h4>Listado de Alumnos</h4>";

    $("#titulo").html(titulo);
});


function cargaMateriasConPromocionadosPorCarrera()
    {
        let val = "";
        val = $("#selectCarreras").val();
        if (val!=0) {
            let parametros = val+'_'+$("#inputFecha").val();
            let p = {"parametros":parametros}

            $.get("./funciones/generarMateriasConPromocionadosPorCarrera.php",p,function (resul){
                $("#resultado").html(resul);
            });
        } else {
            alert('debe seleccionar');
        }
    }




//***********************************************
// RETORNA EL EVENTO CON TODOS SUS DATOS
//***********************************************
function getDatosEventoPorCodigo(codigo) {
   var datos = {"codigo":codigo};
   var evento;
   $.ajax({
      url:"./funciones/getFechasEventoPorCodigo.php",
      type:"POST",
      data: datos,
      dataType : 'json',
      async: false,
      success: function(datos){
         evento = datos;
      }
});
return evento;
}

//***********************************************************
// RETORNA EL TURNO DE EXAMENES ACTIVO O EN CURSO SI LO HAY 
//***********************************************************
function getTurnoActivo() {
   let evento_habilitado = "No";
   let evento_codigo = "";
   let calendario_id = "";
   let cantidad_llamados = "";
   let ultima_inscripcion_id = "";
   let datos;
   for (i=1001;i<=1004;i++) {
         datos_evento = getDatosEventoPorCodigo(i);
         if (datos_evento.codigo==100) {
            if (datos_evento.habilitado=='Si') {
               evento_habilitado = 'Si';
               evento_codigo = i;
               calendario_id = datos_evento.data[0].id;
               ultima_inscripcion_id = datos_evento.id_ultima_inscripcion;
               if (i==1001 || i==1003) {
                  cantidad_llamados = 2;
               } else {
                  cantidad_llamados = 1;
               }
               break;
            } else {
               evento_habilitado = 'No';
            }
         
      };
   };

   datos = {"habilitado":evento_habilitado, "evento_codigo":evento_codigo, "calendario_id":calendario_id, "cantidad_llamados":cantidad_llamados,"calendario_id_inscripcion_asociada":ultima_inscripcion_id};
   return datos;
}


//***********************************************************
// RETORNA LAS CARRERAS ACTIVAS 
//***********************************************************
function getCarrerasHabilitadas() {
   var evento;
   $.ajax({
      url:"./funciones/getCarrerasHabilitadas.php",
      type:"POST",
      dataType : 'json',
      async: false,
      success: function(response){
         evento = response.datos;
      }
    });
   console.info(evento); 
   return evento;
}


//***********************************************************
// RETORNA EL LLAMADO ACTIVO DE UN TURNO EXAMENES SI LO HAY 
//***********************************************************
function getLlamadoActivo() {
   let evento_habilitado = "No";
   let evento_codigo = "";
   let calendario_id = "";
   let cantidad_llamados = "";
   let datos;
   for (i=1020;i<=1021;i++) {
         datos_evento = getDatosEventoPorCodigo(i);
         if (datos_evento.codigo==100) {
            if (datos_evento.habilitado=='Si') {
               evento_habilitado = 'Si';
               evento_codigo = i;
               calendario_id = datos_evento.data[0].id;
               break;
            } else {
               evento_habilitado = 'No';
            }
         
      };
   };

   datos = {"habilitado":evento_habilitado, "evento_codigo":evento_codigo, "calendario_id":calendario_id, "cantidad_llamados":cantidad_llamados};
   return datos;
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