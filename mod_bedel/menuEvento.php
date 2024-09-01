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
              <li class="breadcrumb-item active" aria-current="page">Carreras</li>
          </ol>
      </nav>
    </div>
</article>

<article class="container-fluid">
    <div id="titulo"></div>
</article>
  
<article  class="container-fluid">
    <section id="principal">
    </section>  
    <section id="resultado_accion">
    </section>         
</article>
  
<span id="modalEliminar"></span>

<!-- FOOTER -->
<?php include("componente_footer.html"); ?>

<!-- JAVASCRIPT CUSTOM -->
<script src="./js/funciones.js"></script>
<script src="../public/assets/js/sweetalert2.all.min.js"></script>
<script>

//************************************************************************************************ */
//************************************************************************************************ */
//*********************************** LISTADO DE ENTIDADES *************************************** */
//************************************************************************************************ */
//************************************************************************************************ */
let entidad_nombre = "evento";
let entidad_titulo1 = "EVENTO";
let entidad_titulo2 = "Eventos";
let campo1 = "Id";
let campo2 = "Codigo";
let campo3 = "Descripcion";

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

    let per_page = 10;
    let parametros = {"action": "listar","page": page,"per_page": per_page, "codigo":codigo, "materia":descripcion};
    let titulo = "<h1><i><u>"+entidad_titulo2+"</u></i></h1>";
    let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                  <li class="breadcrumb-item active" aria-current="page">`+entidad_titulo2+`</li>
                              </ol>
                          </nav>`;
  
    $("#titulo").html(titulo);
    $("#breadcrumb").html(breadcrumb);
    $.ajax({
            url: 'funciones/'+entidad_nombre+'Listar.php',
            data: parametros,
            method: 'POST',
            beforeSend: function () {
              //$("#resultado").html("<img src='../assets/img/load_icon.gif' width='50' >");  
            },
            success: function (data) {
                $("#principal").slideDown("slow").html(data);
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
    let codigo = $("#inputFiltroCodigo").val();
    let descripcion = $("#inputFiltroDescripcion").val();

    let per_page = 10;
    let parametros = {"action": "listar","page": 1,"per_page": per_page, "codigo":codigo, "descripcion":descripcion};
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
        $("#inputFiltroCodigo").val(""); 
        $("#inputFiltroDescripcion").val("");
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
/*

//************************************************************************************************ 
//************************************************************************************************ 
//*********************************** VER DATOS DE LA ENTIDAD ************************************ 
//************************************************************************************************ 
//************************************************************************************************ 
function entidadVer(entidad_id){
    let datos_entidad = "";
    let url = "html/"+entidad_nombre+".html";
    let url_obtener_entidad = "funciones/localidadObtener.php";
    let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page"><a href="#" onclick="load(1)">`+entidad_titulo2+`</></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Ver</li>
                            </ol>
                        </nav>`;
    $("#breadcrumb").slideDown("slow").html(breadcrumb);  

    //$('#alumno_ver').css('display', '');
    //$('#alumno_editar').css('display', 'none !important');
    
     
    datos_entidad = entidadObtenerPorId(entidad_id);
    $.get(url,function(data) {
          $("#resultado_accion").html("");
          $("#principal").slideDown("slow").html(data);
          $('#alumno_ver').removeClass('d-none');
          $('#alumno_editar').addClass('d-none');
          $('#alumno_asignar_carrera').addClass('d-none');
          $('#alumno_asignar_mesa').addClass('d-none'); 
          //******************************************************************** 
          //**************************** CAMBIAR ******************************* 
          $("#spn_nombres").html(datos_entidad.datos[0].apellido+', '+datos_entidad.datos[0].nombre);
          $("#spn_fecha_nacimiento").html(datos_entidad.datos[0].fechaNacimiento);
          $("#spn_documento").html(datos_entidad.datos[0].dni);
          $("#spn_domicilio").html(datos_entidad.datos[0].domicilio);
          $("#inputId").val(entidad_id);
          
          if (datos_entidad.datos[0].telefono_caracteristica!=null && datos_entidad.datos[0].telefono_numero!=null) {
                let wsp = `<a href="https://api.whatsapp.com/send/?phone=549`+datos_entidad.datos[0].telefono_caracteristica+datos_entidad.datos[0].telefono_numero+`&text=Hola&type=phone_number&app_absent=0" target="_blank">
                                    <img src="../public/img/icons/whatsapp.png" width="20">
                            </a>`;
                $("#spn_celular").html(wsp+' ('+datos_entidad.datos[0].telefono_caracteristica+') '+datos_entidad.datos[0].telefono_numero);
          }
          $("#spn_email").html(datos_entidad.datos[0].email);
          $("#spn_localidad").html(datos_entidad.datos[0].localidad_nombre + ' | Pcia. ' + datos_entidad.datos[0].provincia_nombre + ' | CP. '+datos_entidad.datos[0].codigo_postal);
          
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
      let url = "html/"+entidad_nombre+".html";
      let url_select2_obtener = "funciones/localidadObtener.php";// Esto puede cambiar
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
            $('#alumno_editar').removeClass('d-none');
            $('#alumno_ver').addClass('d-none');
            $('#telefono_viejo').addClass('d-none');
            $('#alumno_asignar_carrera').addClass('d-none');
            $('#alumno_asignar_mesa').addClass('d-none');
            //******************************************************************** 
            //******************************************************************** 
            $("#inputFechaNacimiento").datepicker({
                dateFormat: 'dd/mm/yy',
                maxDate: new Date()
                //startDate: '-3d'
            });
            $("#inputTelefono").attr('type','hidden');
            $("#inputAccion").val('nuevo');
            $('#inputLocalidad').select2({
                    theme: "bootstrap",
                    placeholder: "Buscar",
                    ajax: {
                        url: url_select2_obtener,
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
      let url_obtener_entidad = "funciones/localidadObtener.php";
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
            $('#alumno_editar').removeClass('d-none');
            $('#alumno_ver').addClass('d-none');
            $('#telefono_viejo').removeClass('d-none');
            $('#alumno_asignar_carrera').addClass('d-none');
            $('#alumno_asignar_mesa').addClass('d-none');
            //******************************************************************** 
            //**************************** CAMBIAR ******************************* 
            $("#inputAccion").val('editar');
            $("#inputId").val(entidad_id);
            $("#inputApellido").val(datos_entidad.datos[0].apellido);
            $("#inputNombre").val(datos_entidad.datos[0].nombre);
            $("#inputDocumento").attr('disabled',true)
            $("#inputDocumento").val(datos_entidad.datos[0].dni);
            $("#inputDomicilio").val(datos_entidad.datos[0].domicilioCalle+' '+datos_entidad.datos[0].domicilioDpto);
            $("#inputTelefono").val(datos_entidad.datos[0].telefono);
            $("#inputTelefono").attr('type','text');
            $("#inputTelefonoCaracteristica").val(datos_entidad.datos[0].telefono_caracteristica);
            $("#inputTelefonoNumero").val(datos_entidad.datos[0].telefono_numero);
            $("#inputEmail").val(datos_entidad.datos[0].email);
            $("#inputFechaNacimiento").datepicker({
                dateFormat: 'dd/mm/yy',
                maxDate: new Date()
            });
            if (datos_entidad.datos[0].fechaNacimiento!=null) {
                let anio = (datos_entidad.datos[0].fechaNacimiento).substr(0,4);
                let mes = (datos_entidad.datos[0].fechaNacimiento).substr(5,2);
                let dia = (datos_entidad.datos[0].fechaNacimiento).substr(8,2);
                $("#inputFechaNacimiento").val(datos_entidad.datos[0].fechaNacimiento);
                let realDate = new Date(anio+'/'+mes+'/'+dia);  
                $("#inputFechaNacimiento").datepicker('setDate',realDate);
            }
            $('#inputLocalidad').select2({
                theme: "bootstrap",
                placeholder: "Localidad",
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
                id: datos_entidad.datos[0].localidad_id,
                text: datos_entidad.datos[0].localidad_nombre + ' (Pcia. ' + datos_entidad.datos[0].provincia_nombre + ')'
            };
            
            var newOption = new Option(data.text, data.id, false, false);
            $('#inputLocalidad').append(newOption).trigger('change');
            $("#inputLocalidad option[value="+ datos_entidad.datos[0].localidad_id +"]").attr("selected",true);

      });
}


//************************************************************************************************ 
//************************************************************************************************ 
//******* GRABA LA ENTIDAD QUE SE CREA (NUEVA) O LA QUE SE EDITA EN LA BASE DE DATOS *************
//************************************************************************************************ 
//************************************************************************************************ 
function entidadGuardar(){
    let accion = $("#inputAccion").val();
    let apellido = $("#inputApellido").val();
    let nombres = $("#inputNombre").val();
    let dni = $("#inputDocumento").val();
    let domicilio = $("#inputDomicilio").val();
    let telefono_caracteristica = $("#inputTelefonoCaracteristica").val();
    let telefono_numero = $("#inputTelefonoNumero").val();
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
                                                
                                                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                    &nbsp;<strong>Atenci&oacute;n:</strong> `+data.mensaje+`
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>   
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
            };
            $("#inputApellido").val("");
            $("#inputNombre").val("");
            $("#inputDocumento").val("");
            $("#inputDomicilio").val("");
            $("#inputTelefono").val("");
            $("#inputCaracteristicaTelefono").val("");
            $("#inputNumeroTelefono").val("");
            $("#inputEmail").val("");
            $("#inputLocalidad").val("");
            $("#inputAccion").val("");
            let hoy = new Date();  
            let fecha_hoy = hoy.getDate() + '/' + ( hoy.getMonth() + 1 ) + '/' + hoy.getFullYear();
            $("#inputFechaNacimiento").datepicker('setDate',fecha_hoy);
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
//*********************************** VINCULAR ALUMNO A CARRERA  ********************************* 
//************************************************************************************************ 
//************************************************************************************************ 

function vincularCarrera(entidad_id) {
    let arreglo="";
    let parametros = "";
    let url = "html/"+entidad_nombre+".html";
    let url_select2_obtener = "funciones/carreraObtener.php";// Esto puede cambiar
    let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                  <li class="breadcrumb-item" aria-current="page"><a href="#" onclick="load(1)">`+entidad_titulo2+`</></a></li>
                                  <li class="breadcrumb-item active" aria-current="page">Vincular Carrera</li>
                              </ol>
                      </nav>`;
    $("#breadcrumb").slideDown("slow").html(breadcrumb);                    
      
    $.get(url,function(data) {
            $("#resultado_accion").html("");
            $("#principal").slideDown("slow").html(data);
            $('#alumno_editar').addClass('d-none');
            $('#alumno_ver').addClass('d-none');
            $('#telefono_viejo').addClass('d-none');
            $('#alumno_asignar_carrera').removeClass('d-none');
            $('#alumno_asignar_mesa').addClass('d-none');
            $("#inputId").val(entidad_id);
            //******************************************************************** 
            //******************************************************************** 
            $('#inputCarrera').select2({
                    theme: "bootstrap",
                    placeholder: "Buscar",
                    language: {
                                noResults: function() {
                                          return "No hay resultado";        
                                },
                                searching: function() {
                                          return "Buscando..";
                                }
                              },
                    ajax: {
                        url: url_select2_obtener,
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


function guardarVinculo() {
    let alumno_id = $("#inputId").val();
    let carrera_id = $("#inputCarrera").val();
    let parametro = {"alumno_id":alumno_id,"carrera_id":carrera_id};
    $.post("./funciones/alumnoVincularCarrera.php",parametro,function(data){
        if (data.codigo==100) {
                    $("#resultado_accion").html(`
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-success">
                                                
                                                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                    &nbsp;<strong>Atenci&oacute;n:</strong> `+data.mensaje+`
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>   
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
            };
            $("#inputId").val("");
            $("#inputCarrera").val("");
            load(1);
    },"json");


}


//************************************************************************************************ 
//************************************************************************************************ 
//*********************************** INSCRIBIR A MESA DE EXAMEN ********************************* 
//************************************************************************************************ 
//************************************************************************************************ 

function inscribirExamen(entidad_id) {
    let arreglo="";
    let parametros = "";
    let url = "html/"+entidad_nombre+".html";
    let url_select2_obtener = "funciones/carreraObtener.php";// Esto puede cambiar ACA SERIA OBTENER MESA EXAMENES
    let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                  <li class="breadcrumb-item" aria-current="page"><a href="#" onclick="load(1)">`+entidad_titulo2+`</></a></li>
                                  <li class="breadcrumb-item active" aria-current="page">Inscribir a Mesa</li>
                              </ol>
                      </nav>`;
    $("#breadcrumb").slideDown("slow").html(breadcrumb);       
    
    $.get(url,function(data) {
            $("#resultado_accion").html("");
            $("#principal").slideDown("slow").html(data);
            $('#alumno_editar').addClass('d-none'); //Hacemos invisible a Editar Alumno
            $('#alumno_ver').addClass('d-none'); //Hacemos invisible a Ver Alumno
            $('#telefono_viejo').addClass('d-none');//Hacemos invisible a Telefono Formato Vieja
            $('#alumno_asignar_carrera').addClass('d-none'); //Hacemos invisible a Ver Alumno
            $('#alumno_asignar_mesa').removeClass('d-none'); //Hacemos VISIBLE a Ver Alumno
            $("#inputId").val(entidad_id);
            
            $('#inputMateriaInscribirExamen').select2({
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
            });

            $('#inputTurnoInscribirExamen').select2({
                            theme: "bootstrap",
                            placeholder: "Buscar Turno",
                            language: {
                                noResults: function() {
                                          return "No hay resultado";        
                                },
                                searching: function() {
                                          return "Buscando..";
                                }
                              },
            });

            $('#inputLlamadoInscribirExamen').select2({
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





            $('#inputCarreraInscribirExamen').select2({
                    theme: "bootstrap",
                    placeholder: "Buscar carrera",
                    language: {
                                noResults: function() {
                                          return "No hay resultado";        
                                },
                                searching: function() {
                                          return "Buscando..";
                                }
                              },
                    ajax: {
                        url: url_select2_obtener,
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

            $("#inputCarreraInscribirExamen").change(function(){
                let carrera_id = $("#inputCarreraInscribirExamen").val();

                $.post("./funciones/getMateriasPorIdCarrera.php",{"carrera_id":carrera_id},function(data){
                    
                    if (data.codigo==100) {
                        data.datos.forEach(materia => {
                                $("#inputMateriaInscribirExamen").append($('<option/>', {
                                        text: materia.nombre + ' ('+materia.id+') ',
                                        value: materia.id,
                                }));
                            });
                    };
                },"json");
            })


            $.post("./funciones/getUltimoTurno.php",function(data){
                
                if (data.codigo==100) {
                    data.datos.forEach(turno => {
                            $("#inputTurnoInscribirExamen").append($('<option/>', {
                                    text: turno.descripcion + ' - ' + turno.AnioLectivo + ' ('+turno.id+') ',
                                    value: turno.id,
                            }));
                        });

                    //console.info(data.datos[0].llamados);
                    if (data.datos[0].llamados==1) { 
                            var item = {
                                id: 1,
                                text: 'Llamado 1'
                            };
                            var newOption = new Option(item.text, item.id, false, false);
                            $('#inputLlamadoInscribirExamen').append(newOption).trigger('change');


                    } else {
                            var item = {
                                    id: 1,
                                    text: 'Llamado 1'
                                };
                            var newOption = new Option(item.text, item.id, false, false);
                            $('#inputLlamadoInscribirExamen').append(newOption).trigger('change');

                            var item2 = {
                                    id: 2,
                                    text: 'Llamado 2'
                                };
                            var newOption2 = new Option(item2.text, item2.id, false, false);
                            $('#inputLlamadoInscribirExamen').append(newOption2).trigger('change');

                            var item3 = {
                                    id: 3,
                                    text: 'Ambos Llamados'
                                };
                            var newOption3 = new Option(item3.text, item3.id, false, false);
                            $('#inputLlamadoInscribirExamen').append(newOption3).trigger('change');
                    }
                };
            },"json");
            

        
                    $("#inputCarreraInscribirExamen").change(function(){
                    let carrera_id = $("#inputCarrera").val();
                    //Carga las Materias de una carrera en la select2
                    $.post("./funciones/getMateriasPorIdCarrera.php",{"carrera_id":carrera_id},function(data){
                        if (data.codigo==100) {
                            data.datos.forEach(materia => {
                                    $("#inputMateria").append($('<option/>', {
                                            text: '('+materia.id+') '+materia.nombre,
                                            value: materia.id,
                                    }));
                                });
                                $('#inputMateria').select2({
                                    theme: "bootstrap4",
                                });
                        };
                    },"json")

                    //Carga los Alumnos de una carrera en la select2
                    $.post("./funciones/getAlumnosPorIdCarrera.php",{"carrera_id":carrera_id},function(data){
                        if (data.codigo==100) {
                            data.datos.forEach(alumno => {
                                    $("#inputAlumno").append($('<option/>', {
                                            text: '('+alumno.id+') '+alumno.apellido+', '+alumno.nombre,
                                            value: alumno.id,
                                    }));
                                });
                                $('#inputAlumno').select2({
                                    theme: "bootstrap4",
                                    allowClear: false,
                                    theme: "custom-option-select"
                                });
                        };
                    },"json")
                })





  });    
}


function guardarInscripcionMesa() {
    let alumno_id = $("#inputId").val();
    let materia_id = $("#inputMateriaInscribirExamen").val();
    let evento_academico_id = $("#inputTurnoInscribirExamen").val();
    let llamado = $("#inputLlamadoInscribirExamen").val();
    
    if  (alumno_id && materia_id && evento_academico_id && llamado) {
        let parametro = {"alumno_id":alumno_id,"materia_id":materia_id,"evento_academico_id":evento_academico_id,"llamado":llamado};
        console.info(parametro);
        $.post("./funciones/setAlumnoMesa.php",parametro,function(data){
            if (data.codigo==100) {
                        Swal.fire({
                                icon: 'success',
                                title: 'Atención',
                                text: 'La Inscripción se ha Realizado.'
                                //footer: '<a href="">Why do I have this issue?</a>'
                        })
                        $("#resultado_accion").html(`
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-success">
                                                    
                                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                        &nbsp;<strong>Atenci&oacute;n:</strong> `+data.mensaje+`
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>   
                                                </div>`);
                } else {
                        Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.mensaje
                                    //footer: '<a href="">Why do I have this issue?</a>'
                        })
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
                };
                $("#inputId").val("");
                $("#inputCarrera").val("");
                load(1);
        },"json");
    } else {
        Swal.fire({
                          icon: 'error',
                          title: 'Error',
                          text: 'Debe completar todos los datos.'
                          //footer: '<a href="">Why do I have this issue?</a>'
                        })
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
                          <span style="color: #000000;">
                          <i class="fa fa-info-circle" aria-hidden="true"></i>
                              <strong>Atención:</strong>&nbsp;`+data.mensaje+`
                          </span>
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
                                      <span style="color: #000000;">
                                      <i class="fa fa-info-circle" aria-hidden="true"></i>
                                          <strong>Atención:</strong>&nbsp;`+data.mensaje+`
                                      </span>
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                      </button>       
                                </div>
                        `);
                        load(1);
                },"json");
  };

  /*

  //************************************************ 
  // NOS PERMITE ENVIAR UN EMAIL A UNA INTERESADO    
  //************************************************ 
  function enviarEmail(id) {
    if (confirm("Desea enviar un Email con el QR y sus datos al Interesado?")) {
    let url = "./funciones/interesadoEnviarEmail.php";
    let parametros = {"interesado_id":id};
    $.post(url, parametros, function(data) {
        if (data.codigo==100) {
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
        } else {
            $("#resultado_accion").html(`
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-danger">
                                      <span style="color: #000000;">
                                      <i class="fa fa-info-circle" aria-hidden="true"></i>
                                          <strong>Atención:</strong>&nbsp;`+data.mensaje+`
                                      </span>
                                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                      </button>       
                                </div>
                        `);
        }
    },"json");
    };
  } */

</script>

</body>
</html>