<?php
set_include_path('../app/lib/'.PATH_SEPARATOR.'../app/models/'.PATH_SEPARATOR.'./');

require_once 'verificarCredenciales.php';
require_once 'Sanitize.class.php';
require_once 'Alumno.php';
require_once 'Carrera.php';

$idCarrera = isset($_GET['carrera_id'])?SanitizeVars::INT($_GET['carrera_id']):FALSE;
$idAlumno = isset($_GET['alumno_id'])?SanitizeVars::INT($_GET['alumno_id']):FALSE;

if(!$idCarrera || !$idAlumno){
    header("location: home.php?token=".$_SESSION['token']);
};


$objCarrera = new Carrera();
$ARRAY_DATOS_CARRERA = $objCarrera->getCarreraById($idCarrera);
$carrera_nombre = $ARRAY_DATOS_CARRERA['descripcion'];
$carrera_id = $idCarrera;


$objAlumno = new Alumno();
$ARRAY_DATOS_AlUMNO = $objAlumno->getById($idAlumno);
$alumno_nombre = $ARRAY_DATOS_AlUMNO['apellido'].', '.$ARRAY_DATOS_AlUMNO['nombre'].'<strong> ('.$ARRAY_DATOS_AlUMNO['idAlumno'].')</strong>';
$alumno_id = $idAlumno;


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
              <li class="breadcrumb-item" aria-current="page"><a href="menuAlumno.php?token=<?=$_SESSION['token'];?>">Alumnos</a></li>
              <li class="breadcrumb-item active" aria-current="page"><?=$alumno_nombre;?><strong> | </strong><?=$carrera_nombre;?> <strong>(<?=$idCarrera;?>)</strong><strong> | </strong> Materias Rendidas</li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="container">
    <div id="titulo">
        
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

$(function () {
    let alumno, carrera;
    let datos_alumno = "<?=$alumno_nombre?>";
    let datos_carrera = "<?=$carrera_nombre?> (<?=$carrera_id?>)";
    cargarModulos();
    let datos_alumnos_jumbo = `<div class="jumbotron">
                            <h3 class="display-6">Materia Rendidas del Alumno</h3>
                                <p class="lead">`+datos_alumno+ `<br>
                                `+datos_carrera+ `</p>
                                 </div>`;
    $("#titulo").append(datos_alumnos_jumbo);
});

function cargarModulos() {
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
      let parametros = {"action":"listar", "page":page, "per_page":per_page, "alumno_id":<?=$idAlumno;?> ,"carrera_id":<?=$idCarrera;?> };
      $.ajax({
          url: './funciones/materiasRendidasPorCarreraListar.php?token=<?=$_SESSION['token'];?>',
          data: parametros,
          method: 'POST',
          beforeSend: function () {
            $("#resultado").html("<img src='../public/img/icons/load_icon.png' width='50' >");  
          },
          success: function (data) {
              $("#resultado").fadeIn(100).html(data);
              $("#tabla_calendario>tfoot").prepend(`<tr>
                                                      <td colspan="9">
                                                          <button class="btn btn-primary" onclick="rendidaNuevo()">Nuevo</button>
                                                      </td>
                                                  </tr>`);
          }
      });
};



//RENDIDA NUEVO
function rendidaNuevo(){
    let carrera_id = <?=$carrera_id;?>;
    let alumno_id = <?=$alumno_id;?>;

    let breadcrumble = `<nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page"><a href="home.php?token=<?=$_SESSION['token'];?>">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page"><a href="menuAlumno.php?token=<?=$_SESSION['token'];?>">Alumnos</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                     <a href="menuAlumnoMateriasRendidasPorAlumno.php?token=<?=$_SESSION['token'];?>&carrera_id=`+carrera_id+`&alumno_id=`+alumno_id+`">
                                     <?=$alumno_nombre;?> <strong>|</strong> <?=$carrera_nombre?>  <strong> (`+carrera_id+`) </strong>
                                     </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                     Nuevo
                                </li>
                            </ol>
                        </nav>`;

    $("#breadcrumb").html(breadcrumble);   
                 
    $.get("html/materiaRendidaPorAlumno.html",function(data){
        $("#resultado").fadeIn(100).html(data);

        $("#inputAccion").val('Nuevo');
        $("#inputIdAlumno").val(alumno_id);
        $("#inputId").val('');
        $("#inputCarrera").val('<?=$carrera_nombre?> ('+carrera_id+')');
        $('#inputMateria').val(null).trigger('change');
        $.post("./funciones/getMateriasPorIdCarrera.php?token=<?=$_SESSION['token'];?>",{"carrera_id":carrera_id},function(response){
            if (response.codigo==200) {
                $("#inputMateria").empty(); 
                response.datos.forEach(materia => {
                            $("#inputMateria").append($('<option/>', {
                                    text: materia.nombre + ' ('+materia.id+') ',
                                    value: materia.id,
                            }));
                        });
                        $('#inputMateria').select2({
                            theme: "bootstrap",
                        });
            };
        },"json");


        $('#inputCalendario').val(null).trigger('change');
        $.post("./funciones/getCalendarioInscripciones.php?token=<?=$_SESSION['token'];?>",function(response){
            if (response.codigo==200) {
                $("#inputCalendario").empty(); 
                response.datos.forEach(calendario => {
                            $("#inputCalendario").append($('<option/>', {
                                    text: calendario.anio_lectivo +' | '+ calendario.evento_nombre + ' ('+calendario.calendario_id+') ',
                                    value: calendario.calendario_id,
                            }));
                        });
                        $('#inputCalendario').select2({
                            theme: "bootstrap",
                        });
            };
        },"json");

        $('#inputLlamado').prepend("<option value='' selected>** Llamado **</option>");
        $('#inputNota').prepend("<option value='' selected>** Nota **</option>");
        $('#inputEstado').prepend("<option value='' selected>** Estado **</option>");
        $('#inputCondicion').prepend("<option value='' selected>** Condicion **</option>");    
        $("#inputFecha").datepicker({dateFormat: "yy-mm-dd"});

    });
}


//RENDIDA EDITAR
function rendidaEditar(val){
    let arr = val.split("&");
    let alumno_id = arr[0];
    let materia_id = arr[1];
    let materia_nombre = arr[2];
    let materia_anio = arr[3];
    let calendario_id = arr[4];
    let llamado_nro = arr[5];
    let nota = Math.round(arr[6]);
    let estado = arr[7];
    let condicion = arr[8];
    let fecha = arr[9].substr(0,10);
    let descripcion_evento = arr[10];
    let id = arr[11];
    let carrera_id = <?=$carrera_id;?>
    
    let breadcrumble = `<nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page"><a href="home.php?token=<?=$_SESSION['token'];?>">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page"><a href="menuAlumno.php?token=<?=$_SESSION['token'];?>">Alumnos</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                     <a href="menuAlumnoMateriasRendidasPorAlumno.php?token=<?=$_SESSION['token'];?>&carrera_id=`+carrera_id+`&alumno_id=`+alumno_id+`">
                                     <?=$alumno_nombre;?> <strong>|</strong> <?=$carrera_nombre?>  <strong> (`+carrera_id+`) |</strong> `+materia_nombre+` <strong>(`+materia_id+`)</strong>
                                     </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                     Editar
                                </li>
                            </ol>
                        </nav>`;
    $("#breadcrumb").html(breadcrumble);     
    $.get("html/materiaRendidaPorAlumno.html",function(data){
        $("#resultado").fadeIn(100).html(data);
        $("#inputAccion").val('Editar');
        $("#inputIdAlumno").val(alumno_id);
        $("#inputId").val(id);
        $("#inputCarrera").val('<?=$carrera_nombre?> ('+carrera_id+')');
        $('#inputMateria').prepend("<option value='"+materia_id+"' >"+materia_nombre+' ('+materia_id+")</option>");
        $('select[name="inputMateria"]').attr('disabled', 'disabled');
        $("#inputLlamado option[value="+ llamado_nro +"]").attr("selected",true);
        $("#inputNota option[value="+ nota +"]").attr("selected",true);
        $("#inputEstado option[value="+ estado +"]").attr("selected",true);
        $("#inputCondicion option[value="+ condicion +"]").attr("selected",true);
        $("#inputFecha").val(fecha);
        $("#inputFecha").datepicker({dateFormat: "yy-mm-dd"});
        $('#inputCalendario').select2({
                theme: "bootstrap",
                placeholder: "Calendario",
                ajax: {
                    url: 'funciones/eventoObtener.php?token=<?=$_SESSION['token'];?>',
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
                id: calendario_id,
                text: descripcion_evento+' ('+calendario_id+')'
            };
            var newOption = new Option(data.text, data.id, false, false);
            $('#inputCalendario').append(newOption).trigger('change');
            
    });
}


//RENDIDA GUARDAR
function rendidaGuardar() {
        let accion = $("#inputAccion").val();        
        let alumno_id = $("#inputIdAlumno").val();    
        let id = $("#inputId").val();    

        let materia_id = $("#inputMateria").val();
        let calendario_id = $("#inputCalendario").val();
        let llamado_nro = $("#inputLlamado").val();
        let nota = $("#inputNota").val();
        let estado_final = $("#inputEstado").val();
        let condicion = $("#inputCondicion").val();
        let fecha = $("#inputFecha").val();
        let parametros = "";
        if (accion!="" && alumno_id!=null &&materia_id!=null && calendario_id!=null && llamado_nro!="" && nota!="" && estado_final!="" && condicion!="" && fecha!="")  {
            parametros = {"accion":accion,"id":id,"alumno_id":alumno_id,"materia_id":materia_id,"calendario_id":calendario_id,"llamado_nro":llamado_nro,"nota":nota,"estado_final":estado_final,"condicion":condicion,"fecha":fecha};
            //console.info(parametros);
        } else {
            alert('tiene valores nulos');
        }

        $.post("./funciones/materiaRendidaGuardar.php?token=<?=$_SESSION['token'];?>",parametros,function(datos){
            if (datos.codigo == 200) {
                cargarModulos();
                $("#resultado_accion").html();
                $("#resultado_accion").append(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../public/img/icons/ok_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                            Los datos han sido modificados.</span></i>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button></div></div>`);
            } else {
                $("#resultado_accion").html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../public/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                            <strong>Error</strong>No se han modificado los datos.</span></i>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button></div></div>`);
            }
        },"json");

}


function rendidaEliminar(id){
    if (confirm("Desea Eliminar el Registro?")) {
            $.post("./funciones/materiaRendidaEliminar.php?token=<?=$_SESSION['token'];?>",{"id":id,"accion":'Eliminar'},function(datos){
                    if (datos.codigo == 200) {
                        cargarModulos();
                        $("#resultado_accion").html();
                        $("#resultado_accion").append(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../public/img/icons/ok_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                        `+datos.datos+`</span></i>
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button></div></div>`);
                    } else {
                        $("#resultado_accion").html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../public/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                                    <strong>Error</strong> `+datos.datos+`</span></i>
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button></div></div>`);
                    }
                },"json");
    };

}





</script>

</body>
</html>