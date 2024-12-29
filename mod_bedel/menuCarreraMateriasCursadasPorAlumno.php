<?php
set_include_path('../lib/'.PATH_SEPARATOR.'../conexion/'.PATH_SEPARATOR.'./funciones/');

require_once 'seguridad.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
include_once 'ArrayHash.class.php';
include_once 'getFnCarreraPorId.php';
include_once 'getFnMateriaPorId.php';
include_once 'getFnAlumnoPorId.php';

$idCarrera = isset($_GET['carrera_id'])?SanitizeVars::INT($_GET['carrera_id']):FALSE;
$hashCarrera = $carrera_id_hash = $hash = ArrayHash::encode(array($MY_SECRET=>$_GET['carrera_id'])); 
$idAlumno = isset($_GET['alumno_id'])?SanitizeVars::INT($_GET['alumno_id']):FALSE;
$hash = isset($_GET['hash'])?SanitizeVars::STRING($_GET['hash']):FALSE;
//die;
//die($idCarrera.'*'.$idAlumno.'*'.$hash);
//var_dump($_GET);die;


if(!$idCarrera || !$idAlumno /*|| !ArrayHash::check($hash, array($MY_SECRET=>$idAlumno))*/){
    header("location: menuCarreraAlumnos.php");
};

$ARRAY_DATOS_CARRERA = getCarreraPorId($idCarrera,$conex);
$carrera_nombre = $ARRAY_DATOS_CARRERA['data'][0]['descripcion'];

$ARRAY_DATOS_AlUMNO = getAlumnoPorId($idAlumno,$conex);  //var_dump($ARRAY_DATOS_AlUMNO);die;
$alumno_nombre = $ARRAY_DATOS_AlUMNO['data'][0]['apellido'].', '.$ARRAY_DATOS_AlUMNO['data'][0]['nombre'].'('.$ARRAY_DATOS_AlUMNO['data'][0]['id'].')';

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
              <li class="breadcrumb-item" aria-current="page"><a href="menuCarrera.php">Carreras</a></li>
              <li class="breadcrumb-item active" aria-current="page"><a href="menuCarreraAlumnos.php?id=<?=$idCarrera?>&hash=<?=$hashCarrera?>"><?=$carrera_nombre;?></a></li>
              <li class="breadcrumb-item active" aria-current="page"><strong>Mat.Cursadas de: </strong><?=$alumno_nombre;?></li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="container">
    <div id="titulo"></div>
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
<script src="./js/funciones.js"></script>
<script>

$(function () {
    let alumno, carrera;
    let datos_alumno = "<?=$alumno_nombre?>";
    let datos_carrera = "<?=$carrera_nombre?>";
    cargarModulos();
    let titulo1 = "<h4><i><strong><u>CARRERA:</u></strong> "+datos_carrera+ "</i></h4>";
    let titulo2 = "<h4><i><strong><u>ALUMNO:</u></strong> "+datos_alumno+ "</i></h4><h5>Materias Cursadas</h5>";
    $("#titulo").html(titulo1+titulo2);
});

function cargarModulos() {
    //loadFiltros();
  	load(1);
};

function cargarDatosAlumno() {
   var datos = {"alumno_id":<?=$idAlumno?>};
   var datos_alumno;
   $.ajax({
      url:"./funciones/getAlumnoPorId.php",
      type:"POST",
      data: datos,
      dataType : 'json',
      async: false,
      success: function(datos){
         datos_alumno = datos;
      }
});
return datos_alumno;
}

function cargarDatosCarrera() {
   var datos = {"carrera_id":<?=$idCarrera?>};
   var datos_carrera;
   $.ajax({
      url:"./funciones/getCarreraPorId.php",
      type:"POST",
      data: datos,
      dataType : 'json',
      async: false,
      success: function(datos){
         datos_carrera = datos;
      }
});
return datos_carrera;
}


// CARGA EL LISTADO DE EVENTOS
function load(page) {
    
      let per_page = 10;
      let parametros = {"action":"listar", "page":page, "per_page":per_page, "alumno_id":<?=$idAlumno;?> ,"carrera_id":<?=$idCarrera;?>, "hash":"<?=$hash;?>" };
      $.ajax({
          url: './funciones/carreraListarMateriasCursadasPoridAlumno.php',
          data: parametros,
          method: 'POST',
          beforeSend: function () {
            $("#resultado").html("<img src='../assets/img/load_icon.gif' width='50' >");  
          },
          success: function (data) {
              $("#resultado").fadeIn(100).html(data);
              $("#tabla_calendario>tfoot").prepend(`<tr>
                                                      <td colspan="7">
                                                          <button class="btn btn-primary" onclick="carreraAgregar()">Nuevo</button>
                                                      </td>
                                                  </tr>`);
          }
      });
};


//EDITAR
function cursadoEditar(val){
    let arr = val.split("&");
    let alumno_id = arr[0];
    let materia_id = arr[1];
    let materia_nombre = arr[2];
    let cursado_anio = arr[3];
    let cursado_id = arr[4];
    let nota = Math.round(arr[5]);
    let estado = arr[6];
    let FechaVencimientoRegularidad = arr[7].substr(0,10);

    let breadcrumble = `<nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page"><a href="menuCarrera.php">Carreras</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="menuCarreraAlumnos.php?id=<?=$idCarrera?>&hash=<?=$hashCarrera?>"><?=$carrera_nombre;?></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href='menuCarreraMateriasCursadasPorAlumno.php?alumno_id=<?=$idAlumno?>&carrera_id=<?=$idCarrera?>&hash=<?=$hash?>'><strong>Mat.Cursadas de:</strong> <?=$alumno_nombre;?></a></li>
                                <li class="breadcrumb-item active" aria-current="page">`+materia_nombre+`(`+materia_id+`)</li>
                            </ol>
                        </nav>`;
    $("#breadcrumb").html(breadcrumble);                    
    //console.info(arr);
    $.get("html/materiaCursadaPorAlumnoEditar.html",function(data){
        $("#resultado").fadeIn(100).html(data);
        $("#inputEditarIdAlumno").val(alumno_id);
        $("#inputEditarIdMateria").val(materia_id);
        $("#inputEditarAnioCursado").val(cursado_anio);

        $("#inputEditarCursado option[value="+ cursado_id +"]").attr("selected",true);
        $("#inputEditarNota option[value="+ nota +"]").attr("selected",true);
        $("#inputEditarEstado option[value="+ estado +"]").attr("selected",true);
        $("#inputEditarFechaExpiracion").val(FechaVencimientoRegularidad);
        $("#inputEditarFechaExpiracion").datepicker({dateFormat: "yy-mm-dd"});

    });
}

function cursadoEditarGuardar() {
    let materia_id = $("#inputEditarIdMateria").val();
    let alumno_id = $("#inputEditarIdAlumno").val();
    let cursado_anio = $("#inputEditarAnioCursado").val();
    let cursado_id = $("#inputEditarCursado").val();
    let nota = $("#inputEditarNota").val();
    let estado_final = $("#inputEditarEstado").val();
    let fecha_expiracion = $("#inputEditarFechaExpiracion").val();

    let parametros = {"materia_id":materia_id,"alumno_id":alumno_id,"cursado_anio":cursado_anio,"cursado_id":cursado_id,"nota":nota,"estado_final":estado_final,"fecha_expiracion":fecha_expiracion};
    //console.info(parametros);
    $.post("./funciones/setModificarMateriaCursadaAlumno.php",parametros,function(datos){
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