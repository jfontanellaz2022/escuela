<?php 
set_include_path('../app/lib/'.PATH_SEPARATOR.'./funciones/'.PATH_SEPARATOR.'./');
require_once 'verificarCredenciales.php';
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
<?php
    include_once('../app/views/footer.html');
?>

<!-- JAVASCRIPT LIBRARIES-->
<?php 
    include("../app/views/script_jquery.html");
?>


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
          url: 'funciones/'+entidad_nombre+'Listar.php?token=<?=$_SESSION['token'];?>',
          data: parametros,
          method: 'POST',
          beforeSend: function () {
            $("#principal").html("<img src='../public/img/icons/load_icon.png' width='50' >");  
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
            url: 'funciones/'+entidad_nombre+'Listar.php?token=<?=$_SESSION['token'];?>',
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
    let url = "funciones/"+entidad_nombre+"ObtenerPorId.php?token=<?=$_SESSION['token'];?>";
    let resultado;
    
    $.ajaxSetup({
        async: false
      });
    $.post(url, {"id":entidad_id}, function (data) {
          resultado = data.datos;
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
    let url_obtener_entidad = "funciones/eventoObtener.php?token=<?=$_SESSION['token'];?>";
    let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page"><a href="#" onclick="load(1)">`+entidad_titulo2+`</></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Ver</li>
                            </ol>
                        </nav>`;
    $("#breadcrumb").slideDown("slow").html(breadcrumb);  

    datos_entidad = entidadObtenerPorId(entidad_id);
    if (datos_entidad!=null) {
        $.get(url,function(data) {
            $("#resultado_accion").html("");
            $("#principal").slideDown("slow").html(data);
            $('#calendario_ver').removeClass('d-none');
            $('#calendario_editar').addClass('d-none');
            //******************************************************************** 
            //**************************** CAMBIAR ******************************* 
            $("#spn_id").html(datos_entidad.id);
            $("#spn_anio").html(datos_entidad.anio_lectivo);
            $("#spn_evento").html(datos_entidad.nombre+' ('+datos_entidad.codigo+')');
            $("#spn_fecha_inicio").html(datos_entidad.fecha_inicio);
            $("#spn_fecha_finalizacion").html(datos_entidad.fecha_final);
            $("#inputId").val(datos_entidad.id);
            
            //******************************************************************** 
            //******************************************************************** 
        });
    }
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
      let url_select2_obtener_eventos = "funciones/eventoObtener.php?token=<?=$_SESSION['token'];?>";// Esto puede cambiar
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
      let url_select2_obtener_eventos = "funciones/eventoObtener.php?token=<?=$_SESSION['token'];?>";
      let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                  <li class="breadcrumb-item" aria-current="page"><a href="#" onclick="load(1)">`+entidad_titulo2+`</></a></li>
                                  <li class="breadcrumb-item active" aria-current="page">Editar</li>
                              </ol>
                          </nav>`;
      $("#breadcrumb").slideDown("slow").html(breadcrumb);   
      datos_entidad = entidadObtenerPorId(entidad_id);
      if (datos_entidad!=null) {
      $.get(url,function(data) {
            $("#resultado_accion").html("");
            $("#principal").slideDown("slow").html(data);
            $('#calendario_editar').removeClass('d-none');
            $('#calendario_ver').addClass('d-none');
            //******************************************************************** 
            //**************************** CAMBIAR ******************************* 
            $("#inputAccion").val('editar');
            $("#inputAnio").val(datos_entidad.anio_lectivo);
            //$("#inputCuatrimestre").val(datos_entidad.datos[0].AnioLectivo);
            //$('#inputCuatrimestre option[value="'+datos_entidad.datos[0].idPeriodoCuatrimestreActivo+'"]').attr("selected", "selected");

            $("#inputId").val(entidad_id);

            $("#inputFechaInicio").datepicker({
                dateFormat: 'dd/mm/yy',
            });
            if (datos_entidad.fecha_inicio!=null) {
                let anio = (datos_entidad.fecha_inicio).substr(0,4);
                let mes = (datos_entidad.fecha_inicio).substr(5,2);
                let dia = (datos_entidad.fecha_inicio).substr(8,2);
                $("#inputFechaInicio").datepicker('setDate',dia+'/'+mes+'/'+anio);
            }

            $("#inputFechaFinalizacion").datepicker({
                dateFormat: 'dd/mm/yy',
            });
            if (datos_entidad.fecha_final!=null) {
                let anio = (datos_entidad.fecha_final).substr(0,4);
                let mes = (datos_entidad.fecha_final).substr(5,2);
                let dia = (datos_entidad.fecha_final).substr(8,2);
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
                id: datos_entidad.idTipificacion,
                text: datos_entidad.nombre + ' (' + datos_entidad.codigo + ')'
            };
            var newOption = new Option(data.text, data.id, false, false);
            $('#inputEvento').append(newOption).trigger('change');
            $("#inputEvento option[value="+ datos_entidad.idTipificacion +"]").attr("selected",true);
      });
    };
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
    let url = "funciones/"+entidad_nombre+"Guardar.php?token=<?=$_SESSION['token'];?>";
    if (accion!="" && anio!="" && evento!=="" && fecha_inicio!="" && fecha_finalizacion!="") {
        $.post(url,parametros, function(data) {
            if (data.codigo==200) {
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
      let url = "funciones/"+entidad_nombre+"Eliminar.php?token=<?=$_SESSION['token'];?>";
      let cantidad_seleccionados = 0;
      $('.check:checked').each(
                  function() {
                      arreglo += ','+$(this).val();
                  }
      );
      arreglo = arreglo.substr(1,arreglo.length-1);
      parametros = {"id":arreglo};
      $.post(url, parametros, function (data) {
              $("#resultado_accion").html(`
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-`+data.alert+`">
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
        let url = "funciones/"+entidad_nombre+"Eliminar.php?token=<?=$_SESSION['token'];?>";
        parametros = {"id":entidad_id};
                $.post(url, parametros, function (data) {
                        $("#resultado_accion").html(`
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-`+data.alert+`">
                                                
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