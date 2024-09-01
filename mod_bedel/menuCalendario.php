<?php 
set_include_path('../app/lib/'.PATH_SEPARATOR.'../conexion/'.PATH_SEPARATOR.'./funciones/'.PATH_SEPARATOR.'./');
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
let entidad_nombre = "calendario";
let entidad_titulo1 = "CALENDARIO";
let entidad_titulo2 = "Calendario";
let campo1 = "Id";
let campo2 = "Anio";
let campo3 = "Evento";
let campo4 = "FechaInicio";
let campo5 = "FechaFinalizacion";

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
      let id = $("#inputFiltroId").val();
      let anio = $("#inputFiltroAnio").val();
      let evento = $("#inputFiltroEvento").val();
      let fechaInicio = $("#inputFiltroFechaInicio").val();
      let fechaFinalizacion = $("#inputFiltroFechaFinalizacion").val();
      let busqueda = $("#inputBusquedaRapida").val();
      let per_page = 10;
      let parametros = {"action": "listar","page": page,"per_page": per_page, "id":id, "anio":anio, "evento":evento, "fechaInicio":fechaInicio, "fechaFinalizacion":fechaFinalizacion,"busqueda_rapida":busqueda};
      let titulo = "<h1><i><u>Calendario de Eventos</u></i></h1><h2>Listado</h2>";
      $("#titulo").html(titulo);
      $.ajax({
          url: 'funciones/'+entidad_nombre+'Listar.php',
          data: parametros,
          method: 'POST',
          beforeSend: function () {
            $("#principal").html("<img src='../public/assets/img/load_icon.gif' width='50' >");  
          },
          success: function (data) {
              $("#principal").fadeIn(100).html(data);
          }
      });
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
        $("#inputFiltroId").val("");
        $("#inputFiltroAnio").val("");
        $("#inputFiltroEvento").val("");
        $("#inputFiltroFechaInicio").val("");
        $("#inputFiltroFechaFinalizacion").val("");
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
          $("#inputId").val(entidad_id);
          
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
            $("#inputFechaInicio").datepicker({
                dateFormat: 'dd/mm/yy',
                //startDate: '-3d'
            });
            $("#inputFechaFinalizacion").datepicker({
                dateFormat: 'dd/mm/yy',
                //startDate: '-3d'
            });
            $("#inputAccion").val('nuevo');
            
            $('#inputEvento').select2({
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
            $("#inputAnio").val(datos_entidad.datos[0].AnioLectivo);
            //$("#inputCuatrimestre").val(datos_entidad.datos[0].AnioLectivo);
            //$('#inputCuatrimestre option[value="'+datos_entidad.datos[0].idPeriodoCuatrimestreActivo+'"]').attr("selected", "selected");

            $("#inputId").val(entidad_id);

            $("#inputFechaInicio").datepicker({
                dateFormat: 'dd/mm/yy',
            });
            if (datos_entidad.datos[0].fechaInicioEvento!=null) {
                let anio = (datos_entidad.datos[0].fechaInicioEvento).substr(0,4);
                let mes = (datos_entidad.datos[0].fechaInicioEvento).substr(5,2);
                let dia = (datos_entidad.datos[0].fechaInicioEvento).substr(8,2);
                $("#inputFechaInicio").datepicker('setDate',dia+'/'+mes+'/'+anio);
            }

            $("#inputFechaFinalizacion").datepicker({
                dateFormat: 'dd/mm/yy',
            });
            if (datos_entidad.datos[0].fechaFinalEvento!=null) {
                let anio = (datos_entidad.datos[0].fechaFinalEvento).substr(0,4);
                let mes = (datos_entidad.datos[0].fechaFinalEvento).substr(5,2);
                let dia = (datos_entidad.datos[0].fechaFinalEvento).substr(8,2);
                $("#inputFechaFinalizacion").datepicker('setDate',dia+'/'+mes+'/'+anio);
            }

            $('#inputEvento').select2({
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
                id: datos_entidad.datos[0].idEvento,
                text: datos_entidad.datos[0].descripcion + ' (' + datos_entidad.datos[0].codigo + ')'
            };
            var newOption = new Option(data.text, data.id, false, false);
            $('#inputEvento').append(newOption).trigger('change');
            $("#inputEvento option[value="+ datos_entidad.datos[0].idEvento +"]").attr("selected",true);
      });
}


//************************************************************************************************ 
//************************************************************************************************ 
//******* GRABA LA ENTIDAD QUE SE CREA (NUEVA) O LA QUE SE EDITA EN LA BASE DE DATOS *************
//************************************************************************************************ 
//************************************************************************************************ 
function entidadGuardar(){
    let accion = $("#inputAccion").val();
    let id = $("#inputId").val();
    let anio = $("#inputAnio").val();
    let evento = $("#inputEvento").val();
    let fecha_inicio = $("#inputFechaInicio").val();
    let fecha_finalizacion = $("#inputFechaFinalizacion").val();
    let parametros = {"accion":accion, "id":id, "anio":anio, "evento":evento, "fecha_inicio":fecha_inicio, "fecha_finalizacion":fecha_finalizacion};
    let url = "funciones/"+entidad_nombre+"Guardar.php";
    console.log(parametros);
    if (accion!="" && anio!="" && evento!=="" && fecha_inicio!="" && fecha_finalizacion!="") {
        $.post(url,parametros, function(data) {
            if (data.codigo==100) {
                    $("#resultado_accion").html(`
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-success">
                                                
                                                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                    &nbsp;<strong>Atención:</strong> `+data.mensaje+`
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>   
                                            </div>`);
            } else {
                    $("#resultado_accion").html(`
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-danger">
                                                <span style="color: #000000;">
                                                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                    &nbsp;<strong>Atención:</strong> `+data.mensaje+`
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>   
                                                </span>    
                                            </div>`);
            };
            $("#inputId").val("");
            $("#inputAnio").val("");
            $("#inputEvento").val("");
            $("#inputFechaInicio").val("");
            $("#inputFechaFinalizacion").val("");
            $("#inputAccion").val("");
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
    }    
}


//************************************************************************************************ 
//************************************************************************************************ 
//************************ ELIMINACION DEL REGISTRO DEL CALENDARIO   ***************************** 
//************************************************************************************************ 
//************************************************************************************************ 


//***************************************************************** 
// MANEJA LA SELECCION / DESELECCION DE TODOS LOS CHECKBOX          
//***************************************************************** 
$("body").on("click","#seleccionar_todos", function() {
    if( $(this).is(':checked') ){
          // Hacer algo si el checkbox ha sido seleccionado
          $('.check').prop('checked',true);
      } else {
          // Hacer algo si el checkbox ha sido deseleccionado
          $('.check').prop('checked',false);
      }
  });
  
 
  //******************************************************************************************** 
  // VERFICICA SI HAY ENTIDADES SELECCIONADOS/CHECKBOX Y PIDE CONFIRMACION PARA SU ELIMINACION   
  //******************************************************************************************** 
  function entidadEliminarSeleccionados(){
      let arreglo="";
      let cantidad_seleccionados = 0;
      $('.check:checked').each(
                  function() {
                      arreglo += ','+$(this).val();
                      cantidad_seleccionados++;
                  }
      );
      if (cantidad_seleccionados>0) {
              $("#confirmarEliminarTodosModal").modal("show");
      } else {
              $("#sinElementosModal").modal("show");
              $("#resultado_accion").html(`
                                          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-danger">
                                              <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                  &nbsp;<strong>Atención:</strong> No hay Registros de `+entidad_titulo2+` seleccionados.
                                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                  </button>   
                                          </div>
              `);
      };
  }
  

  //*************************************************************************** 
  // ELIMINA TODOS LAS ENTIDADES CUYOS CHECKBOX ESTAN SELECCIONADOS             
  //*************************************************************************** 
function entidadEliminarSeleccionadosConfirmar(){
      let arreglo="";
      let parametros = "";
      let url = "funciones/"+entidad_nombre+"Eliminar.php";
      let cantidad_seleccionados = 0;
      $('.check:checked').each(
                  function() {
                      arreglo += ','+$(this).val();
                  }
      );
      arreglo = arreglo.substr(1,arreglo.length-1);
      console.log(arreglo)
      parametros = {"id":arreglo};
      $.post(url, parametros, function (data) {
              $("#resultado_accion").html(`
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-success">
                                                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                    &nbsp;<strong>Atención:</strong> `+data.mensaje+`
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>   
                            </div>
              `);
              load(1);
      },"json");
  };
  
  //******************************************************* 
  // CONFIRMA LA ELIMINACION DE LA ENTIDAD DESDE EL MODAL   
  //******************************************************* 
  $("body").on("shown.bs.modal","#confirmarModal", function(e) {
       let button = $(e.relatedTarget); // BUTTON QUE DISPARO EL MODAL
       let id=button.data("id");
      $("#inputEliminarId").val(id);
  })
  
  //************************************************
  // NOS PERMITE ELIMINAR UNA ENTIDAD ESPECIFICA     
  //************************************************ 
  function entidadEliminarSeleccionado(entidad_id){
        let arreglo="";
        let parametros = "";
        let url = "funciones/"+entidad_nombre+"Eliminar.php";
        parametros = {"id":entidad_id};
                $.post(url, parametros, function (data) {
                        $("#resultado_accion").html(`
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-success">
                                                
                                                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                    &nbsp;<strong>Atención:</strong> `+data.mensaje+`
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>   
                                </div>
                        `);
                        load(1);
                },"json");
  };


</script>

</body>
</html>