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
              <li class="breadcrumb-item active" aria-current="page">Carreras</li>
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
  
<span id="modalEliminar"></span>

<!-- Modal -->
<?php include_once('./html/cambiarPassword.html');?>

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
<script src="../public/js/sweetalert2.all.min.js"></script>
<script>

function valideKeySoloNumerosSinPrimerCero(evt,val){
    var inputValue = val;
    //alert(val);
    // Verificar si el primer carácter es cero
    if (inputValue.length === 0 && evt.which === 48) {
        evt.preventDefault();
        return false;
    }
    // code is the decimal ASCII representation of the pressed key.
    var code = (evt.which) ? evt.which : evt.keyCode;
    
    if(code==8) { // backspace.
      return true;
    } else if(code>=48 && code<=57 && inputValue.charAt(0)!== '0') { // is a number.
      return true;
    } else{ // other keys.
      return false;
    }
}

function valideKeySoloNumeros(evt){
    // code is the decimal ASCII representation of the pressed key.
    var code = (evt.which) ? evt.which : evt.keyCode;
    
    if(code==8) { // backspace.
      return true;
    } else if(code>=48 && code<=57) { // is a number.
      return true;
    } else{ // other keys.
      return false;
    }
}
   
   
//************************************************************************************************ */
//************************************************************************************************ */
//*********************************** LISTADO DE ENTIDADES *************************************** */
//************************************************************************************************ */
//************************************************************************************************ */
let entidad_nombre = "alumno";
let entidad_titulo1 = "ALUMNOS";
let entidad_titulo2 = "Alumnos";
let campo1 = "Id";
let campo2 = "Dni";
let campo3 = "Apellido";
let campo4 = "Nombre";
let campo5 = "Telefono";
let campo6 = "Email";

$(function () {
    $.get("./html/modalEliminar.html",function(data){
      $("#modalEliminar").html(data);
    })
    load(1);
});
  
$('#btnCambiarPassword').click(function(event) {
      let password = $('#inputPasswordNueva').val();
      let rePassword = $('#inputRePasswordNueva').val();
      let captcha = $('#inputCaptcha').val();

      let parametros = {'password':password, "repassword": rePassword, "captcha":captcha}
      let link = "../API/cambiarPassword.php?token=<?=$_SESSION['token'];?>";

      if (password!="" && rePassword!="" && captcha!="") {
          if (password==rePassword) {
                  $.post(link,parametros,function(response) {
                       $("#msg_restablecer").removeClass("d-none");
                       $("#msg_restablecer").html('<div class="alert alert-'+response.class+'" role="alert"><strong>Atención:</strong>&nbsp;'+response.mensaje+'</div>');
                       if (response.codigo==200) {
                          $('#inputPasswordNueva').prop("disabled",true);
                          $('#inputRePasswordNueva').prop("disabled",true);
                          $('#inputCaptcha').prop("disabled",true);
                          $('#btnCambiarPassword').prop("disabled",true);
                        }
                  },"json");
         } else {
              alert("no coinciden las contraseñas");
         }
      } else {
          alert("existen campos nulos");
      }
});

$( "#idCambioPwd" ).on('shown.bs.modal', function (e) {
     $("#img_captcha").attr('src', '../app/lib/CaptchaSecurityImages.php?width=90&height=30&characters=5');
});

$("#idCambioPwd").on('hide.bs.modal', function(){
     $("#msg_restablecer").addClass("d-none");
     $('#inputPasswordNueva').prop("disabled",false); $('#inputPasswordNueva').val("");
     $('#inputRePasswordNueva').prop("disabled",false); $('#inputRePasswordNueva').val("");
     $('#inputCaptcha').prop("disabled",false); $('#inputCaptcha').val("");
});

//****************************************** */
// CARGA EL LISTADO DE TODOS LAS ENTIDADES   */
//****************************************** */
function load(page) {
    let valor = $("#inputFiltroValor").val();
    let per_page = 10;
    let parametros = {"action": "listar","page": page,"per_page": per_page, "valor":valor};
    let titulo = "<h1><i><u>Alumnos</u></i></h1>";
    let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                  <li class="breadcrumb-item active" aria-current="page">`+entidad_titulo2+`</li>
                              </ol>
                          </nav>`;
  
    $("#titulo").html(titulo);
    $("#breadcrumb").html(breadcrumb);
    $.ajax({
            url: 'funciones/'+entidad_nombre+'Listar.php?token=<?=$_SESSION['token'];?>',
            data: parametros,
            method: 'POST',
            beforeSend: function () {
              //$("#resultado").html("<img src='../public/img/icons/load_icon.png' width='50' >");  
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
    let valor = $("#inputFiltroValor").val();
    let per_page = 10;
    let parametros = {"action": "listar","page": 1,"per_page": per_page, "valor":valor};
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
        $("#inputFiltroValor").val("");
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
function entidadVer(entidad_id,alumno_descripcion){
    let datos_entidad = "";
    let url = "html/"+entidad_nombre+".html";
    let url_obtener_entidad = "funciones/localidadObtener.php?token=<?=$_SESSION['token'];?>";
    let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page"><a href="#" onclick="load(1)">`+entidad_titulo2+`</></a></li>
                                <li class="breadcrumb-item active" aria-current="page">`+alumno_descripcion+` <strong>|</strong> Ver</li>
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
          $("#spn_nombres").html(datos_entidad.datos.apellido+', '+datos_entidad.datos.nombre);
          $("#spn_fecha_nacimiento").html(datos_entidad.datos.fecha_nacimiento);
          $("#spn_documento").html(datos_entidad.datos.dni);
          $("#spn_domicilio").html(datos_entidad.datos.domicilio);
          $("#inputId").val(entidad_id);
          
          if (datos_entidad.datos.telefono_caracteristica!=null && datos_entidad.datos.telefono_numero!=null) {
                let wsp = `<a href="https://api.whatsapp.com/send/?phone=549`+datos_entidad.datos.telefono_caracteristica+datos_entidad.datos.telefono_numero+`&text=Hola&type=phone_number&app_absent=0" target="_blank">
                                    <img src="../public/img/icons/wsp_icon.png" width="20">
                            </a>`;
                $("#spn_celular").html(wsp+' ('+datos_entidad.datos.telefono_caracteristica+') '+datos_entidad.datos.telefono_numero);
          }
          $("#spn_email").html(datos_entidad.datos.email);
          $("#spn_localidad").html(datos_entidad.datos.localidad_nombre + ' | Pcia. ' + datos_entidad.datos.provincia_nombre + ' | CP. '+datos_entidad.datos.codigo_postal);
          
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
      let url_select2_obtener = "../API/findLocalidad.php?token=<?=$_SESSION['token'];?>";// Esto puede cambiar
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
                        url: '../API/findLocalidad.php?token=<?=$_SESSION['token'];?>',
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
      let url_obtener_entidad = "../API/findLocalidad.php?token=<?=$_SESSION['token'];?>";
      /*let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                  <li class="breadcrumb-item" aria-current="page"><a href="#" onclick="load(1)">`+entidad_titulo2+`</></a></li>
                                  <li class="breadcrumb-item active" aria-current="page">`+alumno_descripcion+` <strong>|</strong> Editar</li>
                              </ol>
                          </nav>`;
      $("#breadcrumb").slideDown("slow").html(breadcrumb);   */
      datos_entidad = entidadObtenerPorId(entidad_id);
      $.get(url,function(data) {
            $("#resultado_accion").html("");
            $("#principal").slideDown("slow").html(data);
            $('#alumno_editar').removeClass('d-none');
            $('#alumno_ver').addClass('d-none');
            $('#alumno_asignar_carrera').addClass('d-none');
            $('#alumno_asignar_mesa').addClass('d-none');
            //******************************************************************** 
            //**************************** CAMBIAR ******************************* 
            $("#inputAccion").val('editar');
            $("#inputId").val(datos_entidad.datos.id);
            $("#inputApellido").val(datos_entidad.datos.apellido);
            $("#inputNombre").val(datos_entidad.datos.nombre);
            $("#inputDocumento").val(datos_entidad.datos.dni);
            $("#inputDomicilio").val(datos_entidad.datos.domicilio);
            $("#inputTelefono").val(datos_entidad.datos.telefono);
            $("#inputTelefono").attr('type','text');
            $("#inputTelefonoCaracteristica").val(datos_entidad.datos.telefono_caracteristica);
            $("#inputTelefonoNumero").val(datos_entidad.datos.telefono_numero);
            $("#inputEmail").val(datos_entidad.datos.email);
            $("#inputAnioIngreso option[value="+ datos_entidad.datos.anio_ingreso +"]").attr("selected",true);
            $("#inputDebeTitulo option[value="+ datos_entidad.datos.debe_titulo +"]").attr("selected",true);

            let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                  <li class="breadcrumb-item" aria-current="page"><a href="#" onclick="load(1)">`+entidad_titulo2+`</></a></li>
                                  <li class="breadcrumb-item active" aria-current="page">`+datos_entidad.datos.apellido+', '+datos_entidad.datos.nombre+' <strong>('+entidad_id+`)</strong> <strong>|</strong> Editar</li>
                              </ol>
                          </nav>`;
            $("#breadcrumb").slideDown("slow").html(breadcrumb);   

            $("#inputFechaNacimiento").datepicker({
                dateFormat: 'dd/mm/yy',
                maxDate: new Date()
            });

            if (datos_entidad.datos.fecha_nacimiento!=null) {
                let anio = (datos_entidad.datos.fecha_nacimiento).substr(0,4);
                let mes = (datos_entidad.datos.fecha_nacimiento).substr(5,2);
                let dia = (datos_entidad.datos.fecha_nacimiento).substr(8,2);
                $("#inputFechaNacimiento").val(datos_entidad.datos.fecha_nacimiento);
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
                id: datos_entidad.datos.localidad_id,
                text: datos_entidad.datos.localidad_nombre + ' (Pcia. ' + datos_entidad.datos.provincia_nombre + ')'
            };
            
            var newOption = new Option(data.text, data.id, false, false);
            $('#inputLocalidad').append(newOption).trigger('change');
            $("#inputLocalidad option[value="+ datos_entidad.datos.localidad_id +"]").attr("selected",true);

      });
}


//************************************************************************************************ 
//************************************************************************************************ 
//******* GRABA LA ENTIDAD QUE SE CREA (NUEVA) O LA QUE SE EDITA EN LA BASE DE DATOS *************
//************************************************************************************************ 
//************************************************************************************************ 
function entidadGuardar(){
    let accion = $("#inputAccion").val();
    let persona_id = $("#inputId").val();
    let apellido = $("#inputApellido").val();
    let nombres = $("#inputNombre").val();
    let dni = $("#inputDocumento").val();
    let domicilio = $("#inputDomicilio").val();
    let telefono_caracteristica = $("#inputTelefonoCaracteristica").val();
    let telefono_numero = $("#inputTelefonoNumero").val();
    let email = $("#inputEmail").val();
    let localidad_id = $("#inputLocalidad").val();
    let fecha_nacimiento = $("#inputFechaNacimiento").val();
    let anio_ingreso = $("#inputAnioIngreso").val();
    let debe_titulo = $("#inputDebeTitulo").val();
    let parametros = {"accion":accion, "apellido":apellido, "nombres":nombres, "dni":dni, "domicilio":domicilio, "telefono_caracteristica":telefono_caracteristica, "telefono_numero":telefono_numero ,"email":email, "localidad_id":localidad_id, "fecha_nacimiento":fecha_nacimiento,"anio_ingreso":anio_ingreso,"debe_titulo":debe_titulo,"persona_id":persona_id};
    let url = "funciones/"+entidad_nombre+"Guardar.php?token=<?=$_SESSION['token'];?>";
    if (accion!="" && apellido!="" && nombres!=="" && dni!="" && email!="" && localidad_id!="" ) {
        $.post(url,parametros, function(response) {
                    $("#resultado_accion").html(`
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-`+response.alert+`">
                                                
                                                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                    &nbsp;<strong>Atenci&oacute;n:</strong> `+response.mensaje+`
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>   
                                            </div>`);
            
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
    let url_select2_obtener = "funciones/carreraObtener.php?token=<?=$_SESSION['token'];?>";// Esto puede cambiar
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
    let anio = $("#inputAnioCarrera").val();
    let parametro = {"alumno_id":alumno_id,"carrera_id":carrera_id,"anio":anio};
    $.post("./funciones/alumnoVincularCarrera.php?token=<?=$_SESSION['token'];?>",parametro,function(response){
            $("#resultado_accion").html(`
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-`+response.alert+`">
                                                
                                                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                    &nbsp;<strong>Atenci&oacute;n:</strong> `+response.mensaje+`
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>   
                                            </div>`);
           
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
    let url_select2_obtener = "funciones/carreraObtener.php?token=<?=$_SESSION['token'];?>";// Esto puede cambiar ACA SERIA OBTENER MESA EXAMENES
    let breadcrumb = `<nav aria-label="breadcrumb" role="navigation">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                  <li class="breadcrumb-item" aria-current="page"><a href="#" onclick="load(1)">`+entidad_titulo2+`</></a></li>
                                  <li class="breadcrumb-item active" aria-current="page">Inscribir a Mesa</li>
                              </ol>
                      </nav>`;
    $("#breadcrumb").slideDown("slow").html(breadcrumb);       
    /****************** INICIALIZAMOS SELECT2 MATERIAS CON LOS ESTILOS CSS ADECUADOS *************************/
    /*$('#inputMateriaInscribirExamen').select2({
                    theme: "bootstrap"
                });    
    */

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

/*            var item = {
                id: 1,
                text: 'Barn owl'
            };

var newOption = new Option(item.text, item.id, false, false);
$('#inputLlamadoInscribirExamen').append(newOption).trigger('change');*/



            //******************************************************************** 
            //******************************************************************** 
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
                /****** DESDE ESTA LLAMADA AJAX SE CARGAN CON LOS DATOS DE LAS MATERIAS DE LA CARRERA SELECCIONADA **********/    
                $.post("./funciones/getMateriasPorIdCarrera.php?token=<?=$_SESSION['token'];?>",{"carrera_id":carrera_id},function(data){
                    
                    if (data.codigo==100) {
                        data.datos.forEach(materia => {
                                $("#inputMateriaInscribirExamen").append($('<option/>', {
                                        text: materia.nombre + ' ('+materia.id+') ',
                                        value: materia.id,
                                }));
                            });
                    };
                },"json");
                /*************************************************************************************************************/
            })


            /****** DESDE ESTA LLAMADA AJAX SE CARGAN CON LOS DATOS DE LAS MATERIAS DE LA CARRERA SELECCIONADA **********/    
            $.post("./funciones/getUltimoTurno.php?token=<?=$_SESSION['token'];?>",function(data){
                
                if (data.codigo==100) {
                    data.datos.forEach(turno => {
                            $("#inputTurnoInscribirExamen").append($('<option/>', {
                                    text: turno.descripcion + ' - ' + turno.AnioLectivo + ' ('+turno.id+') ',
                                    value: turno.id,
                            }));
                        });

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
            /*************************************************************************************************************/

  });    
}


function guardarInscripcionMesa() {
    let alumno_id = $("#inputId").val();
    let materia_id = $("#inputMateriaInscribirExamen").val();
    let evento_academico_id = $("#inputTurnoInscribirExamen").val();
    let llamado = $("#inputLlamadoInscribirExamen").val();
    
    if  (alumno_id && materia_id && evento_academico_id && llamado) {
        let parametro = {"alumno_id":alumno_id,"materia_id":materia_id,"evento_academico_id":evento_academico_id,"llamado":llamado};
        $.post("./funciones/setAlumnoMesa.php?token=<?=$_SESSION['token'];?>",parametro,function(data){
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
        let url = "funciones/"+entidad_nombre+"Eliminar.php?token=<?=$_SESSION['token'];?>";
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

  

//*********************************************************************************************
//***************** CARGA LA HISTORIA ACADEMICA DEL ALUMNO PARA ESA CARRERA *******************
//*********************************************************************************************
function cargarHistoriaPorCarrera(carrera_id,alumno_id,carrera_descripcion,alumno_descripcion) {
      let titulo;
      let bread;
      bread = `<nav aria-label="breadcrumb" role="navigation">
                      <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="home.php?token=<?=$_SESSION['token'];?>">Home</a></li>
                      <li class="breadcrumb-item"><a href="menuAlumno.php?token=<?=$_SESSION['token'];?>">Alumnos</a></li>
                      <li class="breadcrumb-item active" aria-current="page">`+alumno_descripcion+` <strong>|</strong> `+carrera_descripcion+' <strong>('+carrera_id+`)</strong></li>
                      </ol>
                  </nav>`;
      titulo = `<h1><i>Historia Acad&eacute;mica</i></h1>
                    <h3>`+carrera_descripcion+` - Materias</h3>`;
      
      $("#breadcrumb").html(bread);
      $("#titulo").html(titulo);
      parametros = {"carrera_id":carrera_id,"alumno_id":alumno_id};

      $.ajax({
        url:'./funciones/getHistoriaAcademicaPorCarrera.php?token=<?=$_SESSION['token'];?>',
        method: "POST",
        data: parametros,
        success:function(data){
          $("#principal").html(data).fadeIn('slow');
        }
      });
} 


</script>

</body>
</html>