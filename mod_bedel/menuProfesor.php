<?php 
set_include_path('./');
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
let entidad_nombre = "profesor";
let entidad_titulo1 = "PROFESORES";
let entidad_titulo2 = "Profesores";
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
  
//****************************************** */
// CARGA EL LISTADO DE TODOS LAS ENTIDADES   */
//****************************************** */
function load(page) {
    let valor = $("#inputFiltroValor").val();
    let per_page = 10;
    let parametros = {"action": "listar","page": page,"per_page": per_page, "valor":valor};
    let titulo = "<h1><i><u>Profesores</u></i></h1>";
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

// function entidadGuardar() {
//     let accion = $("#inputAccion").val();
//     if (accion=='nuevo') {
//         entidadGuardarNuevo();
//     } else if (accion=='editar') {
//         entidadGuardarEditado();
//     }
// }


//************************************************************************************************ 
//************************************************************************************************ 
//*********************************** VER DATOS DE LA ENTIDAD ************************************ 
//************************************************************************************************ 
//************************************************************************************************ 
//************************************************* 
// NOS PERMITE VER UNA ENTIDAD                   
//************************************************* 
function entidadVer(entidad_id){
    let datos_entidad = "";
    let url = "html/profesor.html";
    let url_obtener_entidad = "funciones/localidadObtener.php?token=<?=$_SESSION['token'];?>";
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
          $('#profesor_editar').addClass('d-none');
          $("#profesor_asignar_carrera").addClass("d-none");
          $('#profesor_ver').removeClass('d-none');
          //******************************************************************** 
          //**************************** CAMBIAR ******************************* 
          //$("#inputIdProfesor").val(entidad_id)
          $("#spn_nombres").html(datos_entidad.datos.apellido+', '+datos_entidad.datos.nombre);
          $("#spn_fecha_nacimiento").html(datos_entidad.datos.fecha_nacimiento);
          $("#spn_documento").html(datos_entidad.datos.dni);
          $("#spn_domicilio").html(datos_entidad.datos.domicilio);
          $("#inputId").val(entidad_id);
          if (datos_entidad.datos.telefono_caracteristica!=null && datos_entidad.datos.telefono_numero!=null) {
                let wsp = `<a href="https://api.whatsapp.com/send/?phone=549`+datos_entidad.datos.telefono_caracteristica+datos_entidad.datos.telefono_numero+`&text=Hola&type=phone_number&app_absent=0" target="_blank">
                                    <img src="../public/img/icons/WhatsApp.png" width="20">
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
            $('#profesor_editar').removeClass('d-none');
            $('#profesor_ver').addClass('d-none');
            $("#profesor_asignar_carrera").addClass("d-none");
            //*************************************************
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
            $("#profesor_ver").addClass("d-none");
            $("#profesor_asignar_carrera").addClass("d-none");
            $("#profesor_editar").removeClass("d-none");
            $("#resultado_accion").html("");
            $("#principal").slideDown("slow").html(data);
            $('#profesor_editar').removeClass('d-none');
            $('#profesor_ver').addClass('d-none');
            $('#telefono_viejo').removeClass('d-none');
            //******************************************************************** 
            //**************************** CAMBIAR ******************************* 
            $("#inputId").val(entidad_id);
            $("#inputApellido").val(datos_entidad.datos.apellido);
            $("#inputNombre").val(datos_entidad.datos.nombre);
            $("#inputDocumento").val(datos_entidad.datos.dni);
            $("#inputDomicilio").val(datos_entidad.datos.domicilio);
            $("#inputTelefono").attr('type','text');
            $("#inputTelefonoCaracteristica").val(datos_entidad.datos.telefono_caracteristica);
            $("#inputTelefonoNumero").val(datos_entidad.datos.telefono_numero);
            $("#inputEmail").val(datos_entidad.datos.email);
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
                language: {
                    noResults: function() {

                    return "No hay resultado";        
                    },
                    searching: function() {

                    return "Buscando..";
                    }
                },
                ajax: {
                    url: url_obtener_entidad,
                    dataType: 'json',
                    delay: 250,
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



//************************************************* 
// GRABA LA ENTIDAD NUEVO EN LA BASE DE DATOS       
//************************************************* 
function entidadGuardar(){
    let id = $("#inputId").val();
    let apellido = $("#inputApellido").val();
    let nombres = $("#inputNombre").val();
    let dni = $("#inputDocumento").val();
    let domicilio = $("#inputDomicilio").val();
    let telefono_caracteristica = $("#inputTelefonoCaracteristica").val();
    let telefono_numero = $("#inputTelefonoNumero").val();
    let email = $("#inputEmail").val();
    let localidad_id = $("#inputLocalidad").val();
    let fecha_nacimiento = $("#inputFechaNacimiento").val();
    let parametros = {"id":id,"apellido":apellido, "nombres":nombres, "dni":dni, "domicilio":domicilio, "telefono_caracteristica":telefono_caracteristica, "telefono_numero":telefono_numero ,"email":email, "localidad_id":localidad_id, "fecha_nacimiento":fecha_nacimiento};
    let url = "funciones/"+entidad_nombre+"Guardar.php?token=<?=$_SESSION['token'];?>";
    if (apellido!="" && nombres!=="" && dni!="" && email!="" && localidad_id!="") {
        $.post(url,parametros, function(response) {
                    $("#resultado_accion").html(`
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-`+response.alert+`">
                                                <span style="color: #000000;">
                                                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                                    &nbsp;<strong>Atenci&oacute;n:</strong> `+response.mensaje+`
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>   
                                                </span>    
                                            </div>`);
            
            $("#inputId").val("");
            $("#inputApellido").val("");
            $("#inputNombre").val("");
            $("#inputDocumento").val("");
            $("#inputDomicilio").val("");
            $("#inputTelefono").val("");
            $("#inputTelefonoCaracteristica").val("");
            $("#inputTelefonoNumero").val("");
            $("#inputEmail").val("");
            $("#inputLocalidad").val("");
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
            $('#profesor_editar').addClass('d-none');
            $('#profesor_ver').addClass('d-none');
            $('#profesor_asignar_carrera').removeClass('d-none');
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
    let profesor_id = $("#inputId").val();
    let carrera_id = $("#inputCarrera").val();
    let anio = $("#inputAnioCarrera").val();
    let parametro = {"profesor_id":profesor_id,"carrera_id":carrera_id,"anio":anio};
    $.post("./funciones/profesorVincularCarrera.php?token=<?=$_SESSION['token'];?>",parametro,function(response){
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



</script>

</body>
</html>