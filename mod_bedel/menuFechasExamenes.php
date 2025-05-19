<?php 
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./funciones/'.PATH_SEPARATOR.'./');
require_once "verificarCredenciales.php";
require_once "CalendarioAcademico.php";

$obj = new CalendarioAcademico();
$arr_datos_ultima_inscripcion = $obj->getLastInscripcionExamen();
$arr_datos_ultimo_turno = $obj->getLastTurnoExamen();
$ultima_inscripcion_id = $arr_datos_ultima_inscripcion['id'];
$ultima_inscripcion_descripcion = $arr_datos_ultima_inscripcion['evento_descripcion'];
$ultimo_turno_id = $arr_datos_ultimo_turno['id'];
$ultimo_turno_descripcion = $arr_datos_ultimo_turno['evento_descripcion'].' - '.$arr_datos_ultimo_turno['anio_lectivo'];
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
              <li class="breadcrumb-item active" aria-current="page">Fechas de Exámenes</li>
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
let entidad_titulo1 = "FECHAS DE EXÁMENES";
let entidad_titulo2 = "Fechas de Exámenes";
let campo1 = "Id";
let campo2 = "Codigo";
let campo3 = "Descripcion";
let campo4 = "Habilitada";
let ultima_inscripcion_id = <?=$ultima_inscripcion_id;?>;
let ultima_inscripcion_descripcion = "<?=$ultima_inscripcion_descripcion;?>";
let ultimo_turno_id = <?=$ultimo_turno_id;?>;
let ultimo_turno_descripcion = "<?=$ultimo_turno_descripcion;?>";


function getDatosFechaExamen(fecha_examen_id) {
   let datos_resultados = "";
   let param = {"fecha_examen_id":fecha_examen_id};

   $.ajax({
      url:"./funciones/fechaExamenObtenerPorId.php?token=<?=$_SESSION['token'];?>",
      type:"POST",
      data: param,
      dataType : 'json',
      async: false,
      success: function(response){
         if (response.codigo==200) {
            datos_resultados = response.datos;
         } else {
            datos_resultados = "";
         }
      }
});

return datos_resultados;
}


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

    
      let busqueda = $("#inputBusquedaRapida").val();
      let per_page = 10;
      let parametros = {"action": "listar","page": page,"per_page": per_page, "busqueda_rapida":busqueda};
      let titulo = "<h1><i><u>Fechas de Exámenes</u></i></h1>";
      $("#titulo").html(titulo);
      $.post('funciones/fechaExamenListar.php?token=<?=$_SESSION['token'];?>', parametros, function (data) {
              $("#principal").fadeIn(100).html(data);}
      );
};

//************************************************************************************************ 
//************************************************************************************************ 
//************************************* GESTION DE  FILTROS  ************************************* 
//************************************************************************************************ 
//************************************************************************************************ 


//******************************************* 
// APLICA EL FILTRO AL LISTADO DE ENTIDADES   
//******************************************* 
function aplicarBusquedaRapida() {
    let busqueda = $("#inputBusquedaRapida").val();
    let per_page = 10;
    let parametros = {"action": "listar","page": 1,"per_page": per_page, "busqueda_rapida":busqueda};
    let titulo = "<h1><i><u>"+entidad_titulo1+"</u></i></h1>";
    $("#titulo").html(titulo);
        $.ajax({
            url: 'funciones/fechaExamenListar.php?token=<?=$_SESSION['token'];?>',
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
      let url = "html/fechaExamenes.html";
      let url_select2_obtener_materias = "funciones/materiaObtener.php?token=<?=$_SESSION['token'];?>";// Esto puede cambiar
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
            $("#subtitulo_formulario").html("Crear a Fecha de Materia");
            $("#inputTurnoId").val(ultimo_turno_id);
            $("#inputTurnoDescripcion").val(ultimo_turno_descripcion+ " ("+ultimo_turno_id+")");
            //******************************************************************** 
            //******************************************************************** 
            $("#inputFechaExamen").datepicker({
                dateFormat: 'dd/mm/yy',
                minDate: new Date()
                //startDate: '-3d'
            });
            


            $("#inputAccionFechaExamen").val('nuevo');

            
            $('#inputMateriaFechaExamen').select2({
                    theme: "bootstrap",
                    placeholder: "Buscar materia",
                    language: {
                                noResults: function() {
                                          return "No hay resultado";        
                                },
                                searching: function() {
                                          return "Buscando..";
                                }
                              },
                    ajax: {
                        url: url_select2_obtener_materias,
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

            $('#inputLlamadoFechaExamen').select2({
                            theme: "bootstrap",
                            placeholder: "Buscar Llamado",
                            language: {
                                noResults: function() {
                                          return "No hay resultado";        
                                },
                                searching: function() {
                                          return "Buscando..";
                                }
                              },
            });


      });
}

//************************************************************************************************ 
//************************************************************************************************ 
//************************************* DUPLICAR LA ENTIDAD ************************************** 
//************************************************************************************************ 
//************************************************************************************************ 

//************************************ 
// NOS PERMITE DUPLICAR UNA ENTIDAD                   
//************************************ 

function entidadDuplicar(fecha_examen_id){
      let arreglo="";
      let parametros = "";
      let url = "html/fechaExamenes.html";

      let datos_examen = getDatosFechaExamen(fecha_examen_id);

      let calendario_id = datos_examen.calendario_id;
      let materia_id = datos_examen.materia_id;
      let materia_nombre = datos_examen.materia_nombre;
      let llamado = datos_examen.llamado;
      let fecha_examen = datos_examen.fecha_examen;
      
      let url_select2_obtener_materias = "funciones/materiaObtener.php?token=<?=$_SESSION['token'];?>";// Esto puede cambiar
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
            $("#subtitulo_formulario").html("Duplicar Fecha de Materia");
            $("#inputFechaExamen").val(fecha_examen);
            $("#inputAccionFechaExamen").val('nuevo');
            $("#inputTurnoId").val(ultimo_turno_id);
            $("#inputTurnoDescripcion").val(ultimo_turno_descripcion+ " ("+ultimo_turno_id+")");
            //******************************************************************** 
            //******************************************************************** 

            $("#inputFechaExamen").datepicker({
                dateFormat: 'dd/mm/yy',
                minDate: new Date()
                //startDate: '-3d'
            });

            $('#inputMateriaFechaExamen').select2({
                    theme: "bootstrap",
                    placeholder: "Buscar materia",
                    language: {
                                noResults: function() {
                                          return "No hay resultado";        
                                },
                                searching: function() {
                                          return "Buscando..";
                                }
                              },
                    ajax: {
                        url: url_select2_obtener_materias,
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
                                    id: materia_id,
                                    text: materia_nombre
                                };

            var newOption = new Option(data.text, data.id, false, false);
            $('#inputMateriaFechaExamen').append(newOption).trigger('change');
      });
}


//************************************************************************************************ 
//************************************************************************************************ 
//************************************* EDITAR LA ENTIDAD **************************************** 
//************************************************************************************************ 
//************************************************************************************************ 

//************************************ 
// NOS PERMITE EDITAR  UNA ENTIDAD                   
//************************************ 

function entidadEditar(fecha_examen_id){
      let arreglo="";
      let parametros = "";
      let url = "html/fechaExamenes.html";

      let datos_examen = getDatosFechaExamen(fecha_examen_id);

      let calendario_id = datos_examen.calendario_id;
      let materia_id = datos_examen.materia_id;
      let materia_nombre = datos_examen.materia_nombre;
      let llamado = datos_examen.llamado;
      let fecha_examen = datos_examen.fecha_examen;
      
      let url_select2_obtener_materias = "funciones/materiaObtener.php?token=<?=$_SESSION['token'];?>";// Esto puede cambiar
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
            $("#subtitulo_formulario").html("Editar Fecha de Materia");
            $("#inputFechaExamen").val(fecha_examen);
            $("#inputAccionFechaExamen").val('editar');
            $("#inputIdFechaExamen").val(fecha_examen_id);
            $("#inputTurnoId").val(ultimo_turno_id);
            $("#inputTurnoDescripcion").val(ultimo_turno_descripcion+ " ("+ultimo_turno_id+")");
            //******************************************************************** 
            //******************************************************************** 

            $("#inputFechaExamen").datepicker({
                dateFormat: 'dd/mm/yy',
                minDate: new Date()
                //startDate: '-3d'
            });

            $('#inputMateriaFechaExamen').select2({
                    theme: "bootstrap",
                    placeholder: "Buscar materia",
                    language: {
                                noResults: function() {
                                          return "No hay resultado";        
                                },
                                searching: function() {
                                          return "Buscando..";
                                }
                              },
                    ajax: {
                        url: url_select2_obtener_materias,
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
                                    id: materia_id,
                                    text: materia_nombre
                                };

            var newOption = new Option(data.text, data.id, false, false);
            $('#inputMateriaFechaExamen').append(newOption).trigger('change');
      });
}






//************************************************* 
// GRABA LA ENTIDAD NUEVO EN LA BASE DE DATOS       
//************************************************* 
function guardarFechaMateria(){
    let url = "funciones/fechaExamenGuardar.php?token=<?=$_SESSION['token'];?>";
    let fecha_examen_id = $("#inputIdFechaExamen").val();
    let materia_id = $("#inputMateriaFechaExamen").val();
    let llamado = $("#inputLlamadoFechaExamen").val();
    let fecha_examen = $("#inputFechaExamen").val();
    let accion = $("#inputAccionFechaExamen").val();
    let calendario_id = ultimo_turno_id;
    let parametros = "";

    if (calendario_id && materia_id && llamado && fecha_examen) {
        if (fecha_examen_id) {
            parametros = {"calendario_id":calendario_id, "materia_id":materia_id, "llamado":llamado, "fecha_examen":fecha_examen, "fecha_examen_id":fecha_examen_id};
        } else if (accion=='nuevo') {
            parametros = {"calendario_id":calendario_id, "materia_id":materia_id, "llamado":llamado, "fecha_examen":fecha_examen};
        }
        
        $.post(url,parametros, function(data) {
                if (data.codigo==200) {
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
                $("#inputIdFechaExamen").val("");
                $("#inputMateriaFechaExamen").val("");
                $("#inputLlamadoFechaExamen").val("");
                $("#inputFechaExamen").val("");
                $("#inputAccionFechaExamen").val("");
                load(1);
        },"json"); 
        
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


function entidadEliminar(val){
    let url = "funciones/fechaExamenEliminar.php?token=<?=$_SESSION['token'];?>";
    let fecha_examen_id = val;
    
    let parametros = {"accion":'eliminar',"fecha_examen_id":fecha_examen_id};
if (confirm("Desea Eliminar el Registro de Fecha de la Fecha de Examen?.")) {
    $.post(url,parametros, function(data) {
                if (data.codigo==200) {
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
                load(1);
        },"json"); 

        
}

}



</script>

</body>
</html>