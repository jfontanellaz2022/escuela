<?php 
set_include_path('../app/lib/'.PATH_SEPARATOR.'../conexion/'.PATH_SEPARATOR.'./funciones/'.PATH_SEPARATOR.'./');
require_once('config.php');
require_once('seguridad.php');
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
              <li class="breadcrumb-item active" aria-current="page">Calendario Académico</li>
          </ol>
      </nav>
    </div>
</article>


<article class="container-fluid">
    <div id="titulo"></div>
</article>
  
<article  class="container">
    <section id="principal">
    </section>  
    <section id="resultado_accion">
    </section>         
</article>

<span id="modalEliminar">

</span>  

  

<!-- FOOTER -->
<?php include("componente_footer.html"); ?>




<!-- JAVASCRIPT CUSTOM -->
<script src="./js/funciones.js"></script>
<script>

//************************************************************************************************ */
//************************************************************************************************ */
//*********************************** LISTADO DE ENTIDADES *************************************** */
//************************************************************************************************ */
//************************************************************************************************ */
let entidad_nombre = "carrera";
let entidad_titulo1 = "CARRERA";
let entidad_titulo2 = "Carrera";
let campo1 = "Id";
let campo2 = "Codigo";
let campo3 = "Descripcion";
let campo4 = "Habilitada";

$(function () {
    $.get("./html/modalEliminar.html",function(data){
      $("#modalEliminar").html(data);
    })
    load(1);
});
  
//****************************************** */
// CARGA EL LISTADO DE TODOS LAS ENTIDADES   */
//****************************************** */
function load(page) {

      let codigo = $("#inputFiltroCodigo").val();
      let descripcion = $("#inputFiltroDescripcion").val();
      let habilitada = $("#inputFiltroHabilitada").val();
    
      let busqueda = $("#inputBusquedaRapida").val();
      let per_page = 10;
      let parametros = {"action": "listar","page": page,"per_page": per_page, "codigo":codigo, "descripcion":descripcion, "habilitada":habilitada,"busqueda_rapida":busqueda};
      let titulo = "<h1><i><u>Carreras</u></i></h1><h2>Listado</h2>";
      $("#titulo").html(titulo);
      $.post('funciones/'+entidad_nombre+'Listar.php', parametros, function (data) {
              $("#principal").fadeIn(100).html(data);}
      );
};

//************************************************************************************************ 
//************************************************************************************************ 
//************************************* GESTION DE  FILTROS  ************************************* 
//************************************************************************************************ 
//************************************************************************************************ 

//********************************************* 
// APLICA EL FILTRO AL LISTADO DE ENTIDADES     
//********************************************* 

function aplicarFiltro() {
    let codigo = $("#inputFiltroCodigo").val();
      let descripcion = $("#inputFiltroDescripcion").val();
      let habilitada = $("#inputFiltroHabilitada").val();
    
      let busqueda = $("#inputBusquedaRapida").val();
      let per_page = 10;
      let parametros = {"action": "listar","page": 1,"per_page": per_page, "codigo":codigo, "descripcion":descripcion, "habilitada":habilitada,"busqueda_rapida":busqueda};
      let titulo = "<h1><i><u>Carreras</u></i></h1><h2>Listado</h2>";
      $("#titulo").html(titulo);
      $.post('funciones/'+entidad_nombre+'Listar.php', parametros, function (data) {
              $("#principal").fadeIn(100).html(data);}
      );
  };

//******************************************* 
// APLICA EL FILTRO AL LISTADO DE ENTIDADES   
//******************************************* 
function aplicarBusquedaRapida() {
    let id = $("#inputFiltroId").val();
    let anio = $("#inputFiltroAnio").val();
    let evento = $("#inputFiltroEvento").val();
    let fechaInicio = $("#inputFiltroFechaInicio").val();
    let fechaFinalizacion = $("#inputFiltroFechaFinalizacion").val();
    let busqueda = $("#inputBusquedaRapida").val();
    let per_page = 10;
    let parametros = {"action": "listar","page": 1,"per_page": per_page, "id":id, "anio":anio, "evento":evento, "fechaInicio":fechaInicio, "fechaFinalizacion":fechaFinalizacion,"busqueda_rapida":busqueda};
    let titulo = "<h1><i><u>"+entidad_titulo1+"</u></i></h1>";
    $("#titulo").html(titulo);
        $.ajax({
            url: 'funciones/'+entidad_nombre+'Listar.php',
            data: parametros,
            method: 'POST',
            success: function (data) {
                $("#principal").slideDown("slow").html(data);
            }
        });       
  };
 
//******************************************* 
// QUITA EL FILTRO DEL LISTADO DE ENTIDADES   
//******************************************* 
function quitarFiltro() {
        $("#inputBusquedaRapida").val(""); 
        $("#inputFiltroDescripcion").val("");
        $("#inputFiltroCodigo").val("");
        $("#inputFiltroHabilitada").val("");

        load(1);  
};

//************************************************** 
// NOS PERMITE BUSCAR UNA ENTIDAD POR ID       
//************************************************** 
function entidadObtenerPorId(entidad_id){
    let url = "funciones/"+entidad_nombre+"ObtenerPorId.php";
    let resultado;
    
    $.ajaxSetup({
        async: false
      });
    $.post(url, {"id":entidad_id}, function (data) {
          resultado = data;
    },"json")
    return resultado;
};


//************************************************************************************************ 
//************************************************************************************************ 
//*********************************** VER DATOS DE LA ENTIDAD ************************************ 
//************************************************************************************************ 
//************************************************************************************************ 
function entidadVer(entidad_id){
    let datos_entidad = "";
    let url = "html/"+entidad_nombre+".html";
    let url_obtener_entidad = "funciones/eventoObtener.php";
    let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page"><a href="#" onclick="load(1)">`+entidad_titulo2+`</></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Ver</li>
                            </ol>
                        </nav>`;
    $("#breadcrumb").slideDown("slow").html(breadcrumb);  

    datos_entidad = entidadObtenerPorId(entidad_id);
    $.get(url,function(data) {
          $("#resultado_accion").html("");
          $("#principal").slideDown("slow").html(data);
          $('#calendario_ver').removeClass('d-none');
          $('#calendario_editar').addClass('d-none');
          //******************************************************************** 
          //**************************** CAMBIAR ******************************* 
          $("#spn_id").html(datos_entidad.datos[0].id);
          $("#spn_anio").html(datos_entidad.datos[0].AnioLectivo);
          $("#spn_evento").html(datos_entidad.datos[0].descripcion+' ('+datos_entidad.datos[0].codigo+')');
          $("#spn_fecha_inicio").html(datos_entidad.datos[0].fechaInicioEvento);
          $("#spn_fecha_finalizacion").html(datos_entidad.datos[0].fechaFinalEvento);
          $('#btnVerEditar').attr('onclick', 'entidadEditar('+entidad_id+')');
          
           //******************************************************************** 
           //******************************************************************** 
    });
}


//************************************************************************************************ 
//************************************************************************************************ 
//*********************************** CREACION DE UNA ENTIDAD ************************************ 
//************************************************************************************************ 
//************************************************************************************************ 

//************************************************ 
// NOS PERMITE CREAR UNA ENTIDAD                   
//************************************************ 
function entidadCrear(){
      let arreglo="";
      let parametros = "";
      let url = "html/calendario.html";
      let url_select2_obtener_eventos = "funciones/eventoObtener.php";// Esto puede cambiar
      let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                  <li class="breadcrumb-item" aria-current="page"><a href="#" onclick="load(1)">`+entidad_titulo2+`</></a></li>
                                  <li class="breadcrumb-item active" aria-current="page">Nuevo</li>
                              </ol>
                          </nav>`;
      $("#breadcrumb").slideDown("slow").html(breadcrumb);                    
      
      $.get(url,function(data) {
            $("#resultado_accion").html("");
            $("#principal").slideDown("slow").html(data);
            $('#calendario_editar').removeClass('d-none');
            $('#calendario_ver').addClass('d-none');
            //******************************************************************** 
            //******************************************************************** 
            $("#inputAltaFechaInicio").datepicker({
                dateFormat: 'dd/mm/yy',
                maxDate: new Date()
                //startDate: '-3d'
            });
            $("#inputAltaFechaFinalizacion").datepicker({
                dateFormat: 'dd/mm/yy',
                maxDate: new Date()
                //startDate: '-3d'
            });
            $("#inputAccion").val('nuevo');
            
            $('#inputAltaEvento').select2({
                    theme: "bootstrap",
                    placeholder: "Buscar",
                    ajax: {
                        url: url_select2_obtener_eventos,
                        dataType: 'json',
                        delay: 250,
                        data: function (data) {
                            return {
                                searchTerm: data.term // search term
                            };
                        },
                        processResults: function (response) {
                            return {
                                results:response
                            };
                        },
                        cache: true
                    }
            });

      });
}
  
//************************************************* 
// GRABA LA ENTIDAD NUEVO EN LA BASE DE DATOS  falta     
//************************************************* 
  function entidadGuardarNuevo(){
    let accion = $("#inputAccion").val();
    let apellido = $("#inputApellido").val();
    let nombres = $("#inputNombre").val();
    let dni = $("#inputDocumento").val();
    let domicilio = $("#inputDomicilio").val();
    let telefono_caracteristica = $("#inputCaracteristicaTelefono").val();
    let telefono_numero = $("#inputNumeroTelefono").val();
    let email = $("#inputEmail").val();
    let localidad_id = $("#inputLocalidad").val();
    let fecha_nacimiento = $("#inputFechaNacimiento").val();
    let parametros = {"accion":accion, "apellido":apellido, "nombres":nombres, "dni":dni, "domicilio":domicilio, "telefono_caracteristica":telefono_caracteristica, "telefono_numero":telefono_numero ,"email":email, "localidad_id":localidad_id, "fecha_nacimiento":fecha_nacimiento};
    let url = "funciones/"+entidad_nombre+"Guardar.php";
    if (accion!="" && apellido!="" && nombres!=="" && dni!="" && domicilio!=""  && telefono_caracteristica!="" && telefono_numero!="" && email!="" && localidad_id!="" && fecha_nacimiento!="") {
            $.post(url,parametros, function(data) {
                if (data.codigo==100) {
                        $("#resultado_accion").html(`
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-success">
                                                    <span style="color: #000000;">
                                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                        &nbsp;<strong>Atenci&oacute;n:</strong> `+data.mensaje+`
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>   
                                                    </span>    
                                                </div>`);
                } else {
                        $("#resultado_accion").html(`
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-danger">
                                                    <span style="color: #000000;">
                                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                        &nbsp;<strong>Atenci&oacute;n:</strong> `+data.mensaje+`
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>   
                                                    </span>    
                                                </div>`);
                }
            },"json"); 
            load(1);
    } else {
        $("#resultado_accion").html(`
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-danger">
            <span style="color: #000000;">
            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                &nbsp;<strong>Atenci&oacute;n:</strong> Debe completar todos los datos.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>   
            </span>    
        </div>`);
    };
}


//************************************************************************************************ 
//************************************************************************************************ 
//************************************* EDICION DE LA ENTIDAD ************************************ 
//************************************************************************************************ 
//************************************************************************************************ 

//************************************************* 
// NOS PERMITE EDITAR UNA ENTIDAD                   
//************************************************* 
function entidadEditar(entidad_id){
      let datos_entidad = "";
      let url = "html/"+entidad_nombre+".html";
      let url_select2_obtener_eventos = "funciones/eventoObtener.php";
      let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                  <li class="breadcrumb-item" aria-current="page"><a href="#" onclick="load(1)">`+entidad_titulo2+`</></a></li>
                                  <li class="breadcrumb-item active" aria-current="page">Editar</li>
                              </ol>
                          </nav>`;
      $("#breadcrumb").slideDown("slow").html(breadcrumb);   
      datos_entidad = entidadObtenerPorId(entidad_id);


      $.get(url,function(data) {
            $("#resultado_accion").html("");
            $("#principal").slideDown("slow").html(data);
            $('#calendario_editar').removeClass('d-none');
            $('#calendario_ver').addClass('d-none');
            //******************************************************************** 
            //**************************** CAMBIAR ******************************* 
            $("#inputAccion").val('editar');
            $("#inputAltaAnio").val(datos_entidad.datos[0].AnioLectivo);
            //$("#inputAltaEvento").val(datos_entidad.datos[0].evento);
            //$("#inputAltaFechaInicio").val(datos_entidad.datos[0].fechaInicioEvento);
            //$("#inputAltaFechaFinalizacion").val(datos_entidad.datos[0].fechaFinalEvento);

            $("#inputAltaFechaInicio").datepicker({
                dateFormat: 'dd/mm/yy',
            });
            if (datos_entidad.datos[0].fechaInicioEvento!=null) {
                let anio = (datos_entidad.datos[0].fechaInicioEvento).substr(0,4);
                let mes = (datos_entidad.datos[0].fechaInicioEvento).substr(5,2);
                let dia = (datos_entidad.datos[0].fechaInicioEvento).substr(8,2);
                $("#inputAltaFechaInicio").datepicker('setDate',dia+'/'+mes+'/'+anio);
            }

            $("#inputAltaFechaFinalizacion").datepicker({
                dateFormat: 'dd/mm/yy',
            });
            if (datos_entidad.datos[0].fechaFinalEvento!=null) {
                let anio = (datos_entidad.datos[0].fechaFinalEvento).substr(0,4);
                let mes = (datos_entidad.datos[0].fechaFinalEvento).substr(5,2);
                let dia = (datos_entidad.datos[0].fechaFinalEvento).substr(8,2);
                $("#inputAltaFechaFinalizacion").datepicker('setDate',dia+'/'+mes+'/'+anio);
            }

            $('#inputAltaEvento').select2({
                    theme: "bootstrap",
                    placeholder: "Buscar",
                    ajax: {
                        url: url_select2_obtener_eventos,
                        dataType: 'json',
                        delay: 250,
                        data: function (data) {
                            return {
                                searchTerm: data.term // search term
                            };
                        },
                        processResults: function (response) {
                            return {
                                results:response
                            };
                        },
                        cache: true
                    }
            });

            var data = {
                id: datos_entidad.datos[0].id,
                text: datos_entidad.datos[0].descripcion + ' (' + datos_entidad.datos[0].codigo + ')'
            };
            var newOption = new Option(data.text, data.id, false, false);
            $('#inputAltaEvento').append(newOption).trigger('change');
            $("#inputAltaEvento option[value="+ datos_entidad.datos[0].id +"]").attr("selected",true);
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
         $("#principal").html(datos);
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
            $("#resultado_accion").append(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../public/assets/img/icons/ok_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                           `+datos.data+`</span></i>
                                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                           <span aria-hidden="true">&times;</span>
                                         </button></div></div>`);
        } else {
            
            $("#resultado_accion").html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../public/assets/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
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
            $("#resultado_accion").append(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../public/assets/img/icons/ok_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                           `+datos.data+`</span></i>
                                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                           <span aria-hidden="true">&times;</span>
                                         </button></div></div>`);
        } else {
            
            $("#resultado_accion").html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../public/assets/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
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
                $("#resultado_accion").append(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../public/assets/img/icons/ok_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                            `+datos.data+`</span></i>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button></div></div>`);
            } else {
                $("#resultado_accion").html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../public/assets/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                            `+datos.data+`</span></i>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button></div></div>`);
            }
        },"json");
    }
   
}



</script>

</body>
</html>