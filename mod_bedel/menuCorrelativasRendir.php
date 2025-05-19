<?php 
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

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
              <li class="breadcrumb-item active" aria-current="page">Correlativas para cursar</li>
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
let entidad_nombre = "correlativasRendir";
let entidad_titulo1 = "CORRELATIVAS PARA RENDIR";
let entidad_titulo2 = "Correlativas para rendir";
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
      let carrera = $("#inputFiltroCarrera").val();
      let materia = $("#inputFiltroMateria").val();
      let materia_requerida = $("#inputFiltroMateriaRequerida").val();
      let condicion = $("#inputFiltroMateriaCondicion").val();
      let per_page = 10;
      let parametros = {"action": "listar","page": page,"per_page": per_page, "carrera":carrera, "materia":materia, "materia_requerida":materia_requerida, "condicion":condicion};
      let titulo = "<h1><i><u>"+entidad_titulo2+"</u></i></h1>";
      let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page"><a href="home.php?token=<?=$_SESSION['token'];?>">Home</a></li>
                                <li class="breadcrumb-item" active aria-current="page">`+entidad_titulo2+`</li>
                            </ol>
                        </nav>`;
    $("#breadcrumb").slideDown("slow").html(breadcrumb);  
    $("#titulo").html(titulo);
    $.post("funciones/"+entidad_nombre+"Listar.php?token=<?=$_SESSION['token'];?>", parametros, function (data) {
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
    let carrera = $("#inputFiltroCarrera").val();
    let materia = $("#inputFiltroMateria").val();
    let materia_requerida = $("#inputFiltroMateriaRequerida").val();
    let condicion = $("#inputFiltroMateriaCondicion").val();
    let per_page = 10;
    let parametros = {"action": "listar","page": 1,"per_page": per_page, "carrera":carrera, "materia":materia, "materia_requerida":materia_requerida, "condicion":condicion};
    let titulo = "<h1><i><u>"+entidad_titulo2+"</u></i></h1>";
    $("#titulo").html(titulo);
        $.ajax({
            url: "funciones/"+entidad_nombre+"Listar.php?token=<?=$_SESSION['token'];?>",
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
        $("#inputFiltroCarrera").val(""); 
        $("#inputFiltroMateria").val("");
        $("#inputFiltroMateriaRequerida").val("");
        $("#inputFiltroMateriaCondicion").val("");
        load(1);  
};  

//************************************************** 
// NOS PERMITE BUSCAR UNA ENTIDAD POR ID       
//************************************************** 
function entidadObtenerPorId(entidad, entidad_id){
    let url = "funciones/"+entidad+"ObtenerPorId.php?token=<?=$_SESSION['token'];?>";
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
    let url = "html/correlativa.html";
    let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page"><a href="home.php?token=<?=$_SESSION['token'];?>">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page"><a href="#" onclick="load(1)">`+entidad_titulo2+`</></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Ver</li>
                            </ol>
                        </nav>`;
    $("#breadcrumb").slideDown("slow").html(breadcrumb);  

    datos_entidad = entidadObtenerPorId(entidad_nombre,entidad_id);
    datos_entidad_condicion = entidadObtenerPorId("condicionMateria",datos_entidad.datos.idCondicionMateriaRequerida);
    
    $.get(url,function(data) {
          $("#resultado_accion").html("");
          $("#principal").slideDown("slow").html(data);
          $("#correlativas_save").hide();
          $("#correlativas_ver").show();
          //******************************************************************** 
          //**************************** CAMBIAR ******************************* 
          $("#inputId").val(entidad_id);
          $("#subtitulo_ver").html("Ver "+entidad_titulo2);
          $("#spn_id").html(datos_entidad.datos.id);
          $("#spn_carrera").html(datos_entidad.datos.carrera);
          $("#spn_materia").html(datos_entidad.datos.materia+' ('+datos_entidad.datos.materia_id+')');
          $("#spn_materia_requerida").html(datos_entidad.datos.materia_requerida+' ('+datos_entidad.datos.materia_requerida_id+')');
          $("#spn_condicion").html(datos_entidad_condicion.datos.descripcion);
          
          
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
      let url = "html/correlativa.html";
      let url_select2_obtener_materias = "funciones/materiaObtener.php?token=<?=$_SESSION['token'];?>";// Esto puede cambiar
      let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item" aria-current="page"><a href="home.php?token=<?=$_SESSION['token'];?>">Home</a></li>
                                  <li class="breadcrumb-item" aria-current="page"><a href="#" onclick="load(1)">`+entidad_titulo2+`</></a></li>
                                  <li class="breadcrumb-item active" aria-current="page">Nuevo</li>
                              </ol>
                          </nav>`;


      $("#breadcrumb").slideDown("slow").html(breadcrumb);                    

      $.get(url,function(data) {
            $("#resultado_accion").html("");
            $("#principal").slideDown("slow").html(data);
            $("#subtitulo_formulario").html("Crear "+entidad_titulo2);
            $("#correlativas_ver").hide();
            $("#correlativas_save").show();
            
            $('#inputMateria').select2({
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

            $('#inputMateriaRequerida').select2({
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
//************************************* EDICION DE LA ENTIDAD ************************************ 
//************************************************************************************************ 
//************************************************************************************************ 

//************************************************* 
// NOS PERMITE EDITAR UNA ENTIDAD                   
//************************************************* 
function entidadEditar(entidad_id){
      let datos_entidad = "";
      let url = "html/correlativa.html";
      let url_obtener_entidad = "funciones/materiaObtenerPorClave.php?token=<?=$_SESSION['token'];?>";
      let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item" aria-current="page"><a href="home.php?token=<?=$_SESSION['token'];?>">Home</a></li>
                                  <li class="breadcrumb-item" aria-current="page"><a href="#" onclick="load(1)">`+entidad_titulo2+`</></a></li>
                                  <li class="breadcrumb-item active" aria-current="page">Editar</li>
                              </ol>
                          </nav>`;
      $("#breadcrumb").slideDown("slow").html(breadcrumb);   
      datos_entidad = entidadObtenerPorId(entidad_nombre,entidad_id);

      $.get(url,function(data) {
            $("#resultado_accion").html("");
            $("#principal").slideDown("slow").html(data);
            $("#subtitulo_formulario").html("Crear "+entidad_titulo2);
            $("#correlativas_ver").hide();
            $("#correlativas_save").show();
            //******************************************************************** 
            //**************************** CAMBIAR ******************************* 
            $("#inputId").val(datos_entidad.datos.id);
            
            $('#inputMateria').select2({
                theme: "bootstrap",
                placeholder: "Materia",
                ajax: {
                    url: url_obtener_entidad,
                    dataType: 'json',
                    delay: 250,
                    language: {
                                noResults: function() {
                                          return "No hay resultado";        
                                },
                                searching: function() {
                                          return "Buscando..";
                                }
                              },
                    data: function (datos) {
                        return {
                            searchTerm: datos.term // search term
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
                id: datos_entidad.datos.materia_id,
                text: datos_entidad.datos.materia + ' (' + datos_entidad.datos.materia_id + ') - ' + datos_entidad.datos.carrera
            };
            var newOption = new Option(data.text, data.id, false, false);
            $('#inputMateria').append(newOption).trigger('change');
            $("#inputMateria option[value="+ datos_entidad.datos.materia_id +"]").attr("selected",true);

            $('#inputMateriaRequerida').select2({
                theme: "bootstrap",
                placeholder: "Materia",
                ajax: {
                    url: url_obtener_entidad,
                    dataType: 'json',
                    delay: 250,
                    language: {
                                noResults: function() {
                                          return "No hay resultado";        
                                },
                                searching: function() {
                                          return "Buscando..";
                                }
                              },
                    data: function (datos) {
                        return {
                            searchTerm: datos.term // search term
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
                id: datos_entidad.datos.materia_requerida_id,
                text: datos_entidad.datos.materia_requerida + ' (' + datos_entidad.datos.materia_requerida_id + ') - ' + datos_entidad.datos.carrera_requerida
            };
            var newOption = new Option(data.text, data.id, false, false);
            $('#inputMateriaRequerida').append(newOption).trigger('change');
            $("#inputMateriaRequerida option[value="+ datos_entidad.datos.materia_requerida_id +"]").attr("selected",true);

            $('#inputCondicion option[value='+ datos_entidad.datos.idCondicionMateriaRequerida+']').attr('selected','selected');


      });
}







//************************************************* 
// GRABA LA ENTIDAD NUEVO EN LA BASE DE DATOS       
//************************************************* 
function guardarEntidad(){
    let url = "funciones/"+entidad_nombre+"Guardar.php?token=<?=$_SESSION['token'];?>";
   
    let id = $("#inputId").val();
    let materia_id = $("#inputMateria").val();
    let materia_requerida_id = $("#inputMateriaRequerida").val();
    let condicion_id = $("#inputCondicion").val();
    
    let parametros = "";

    if (materia_id && materia_requerida_id && condicion_id) {
        parametros = {"id":id, "materia_id":materia_id, "materia_requerida_id":materia_requerida_id, "condicion_id":condicion_id};
   
        
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
                        Swal.fire({
                                    title: "Actualización realizada!",
                                    text: "La Registración se ha realizado correctamente.",
                                    icon: "success"
                        });     
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
                        Swal.fire({
                            title: "Atención!",
                            text: data.mensaje,
                            icon: "success"
                        });                     
                }
        },"json"); 
        $("#inputId").val("");
        $("#inputMateria").val("");
        $("#inputMateriaRequerida").val("");
        $("#inputCondicion").val("");
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
            Swal.fire({
                title: "No se pudo Realizar la actualizacion!",
                text: "Debe completar todos los datos.",
                icon: "success"
            });

    }
          

}




//************************************************************************************************ 
//************************************************************************************************ 
//************************************* ELIMINACION DEL ALUMNO   ********************************* 
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
                                          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-warning">
                                              <span style="color: #000000;">
                                              <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                  &nbsp;<strong>Atenci&oacute;n:</strong> No hay `+entidad_titulo2+` seleccionados.
                                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                  </button>   
                                              </span>    
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
        if (data.codigo==200) {
                $("#resultado_accion").html(`
                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-success">
                          <span style="color: #000000;">
                          <i class="fa fa-info-circle" aria-hidden="true"></i>
                              <strong>Atención:</strong>&nbsp;`+data.mensaje+`
                          </span>
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                          </button>       
                      </div>
                `);
                Swal.fire({
                                title: "ATENCIÓN!",
                                text: "El Registro se ha eliminado con éxito.",
                                icon: "success"
                });
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
                Swal.fire({
                    title: "ATENCIÓN!",
                    text: "El Registro NO se ha eliminado.",
                    icon: "error"
                });   
        }                     
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
  





function entidadEliminar(val){
    let url = "funciones/"+entidad_nombre+"Eliminar.php?token=<?=$_SESSION['token'];?>";
    let id = val;
    
    let parametros = {"accion":'eliminar',"id":id};
    if (confirm("Desea Eliminar el Registro de Fecha de la Materia?.")) {
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
                            Swal.fire({
                                title: "ATENCIÓN!",
                                text: "El Registro se ha eliminado con éxito.",
                                icon: "success"
                            });                        
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
                            Swal.fire({
                                title: "ATENCIÓN!",
                                text: "El Registro NO se ha eliminado.",
                                icon: "error"
                            });                          
                    }
            },"json"); 

            load(1);
    }

}



</script>

</body>
</html>