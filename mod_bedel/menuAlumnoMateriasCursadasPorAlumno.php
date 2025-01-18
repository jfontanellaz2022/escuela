<?php
set_include_path('../app/lib/'.PATH_SEPARATOR.'../conexion/'.PATH_SEPARATOR.'./funciones/'.PATH_SEPARATOR.'./');
require_once 'config.php';
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
//$hash = isset($_GET['hash'])?SanitizeVars::STRING($_GET['hash']):FALSE;
//die;
//die($idCarrera.'*'.$idAlumno.'*'.$hash);
//var_dump($_GET);die;


if(!$idCarrera || !$idAlumno /*|| !ArrayHash::check($hash, array($MY_SECRET=>$idAlumno))*/){
    header("location: menuCarreraAlumnos.php");
};

$ARRAY_DATOS_CARRERA = getCarreraPorId($idCarrera,$conex);
$carrera_nombre = $ARRAY_DATOS_CARRERA['data'][0]['descripcion'];
$carrera_id = $idCarrera;

$ARRAY_DATOS_AlUMNO = getAlumnoPorId($idAlumno,$conex);  //var_dump($ARRAY_DATOS_AlUMNO);die;
$alumno_nombre = $ARRAY_DATOS_AlUMNO['data'][0]['apellido'].', '.$ARRAY_DATOS_AlUMNO['data'][0]['nombre'].'<strong> ('.$ARRAY_DATOS_AlUMNO['data'][0]['id'].')</strong>';
$alumno_id = $idAlumno;

//die($alumno_nombre);


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
              <li class="breadcrumb-item" aria-current="page"><a href="menuAlumno.php">Alumnos</a></li>
              <li class="breadcrumb-item active" aria-current="page"><?=$alumno_nombre;?><strong> | </strong><?=$carrera_nombre;?> <strong>(<?=$idCarrera;?>)</strong><strong> | </strong> Materias Cursadas</li>
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
    let datos_carrera = "<?=$carrera_nombre?> (<?=$carrera_id?>)";
    cargarModulos();
    let datos_alumnos_jumbo = `<div class="jumbotron">
                            <h3 class="display-6">Datos del Alumno</h3>
                                <p class="lead">`+datos_alumno+ `<br>
                                `+datos_carrera+ `</p>
                                 </div>`;
    $("#titulo").html(datos_alumnos_jumbo);
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
      let parametros = {"action":"listar", "page":page, "per_page":per_page, "alumno_id":<?=$idAlumno;?> ,"carrera_id":<?=$idCarrera;?>, "hash":"<?=$hash;?>" };
      $.ajax({
          url: './funciones/carreraListarMateriasCursadasPoridAlumno.php',
          data: parametros,
          method: 'POST',
          beforeSend: function () {
            $("#resultado").html("<img src='../public/assets/img/load_icon.gif' width='50' >");  
          },
          success: function (data) {
              $("#resultado").fadeIn(100).html(data);
              $("#tabla_calendario>tfoot").prepend(`<tr>
                                                      <td colspan="9">
                                                          <button class="btn btn-primary" onclick="cursadoNuevo()">Nuevo</button>
                                                      </td>
                                                  </tr>`);
          }
      });
};



//NUEVO
function cursadoNuevo(){
    let carrera_id = <?=$carrera_id;?>;
    let alumno_id = <?=$alumno_id;?>;

    let breadcrumble = `<nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page"><a href="menuAlumno.php">Alumnos</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                     <a href="menuAlumnoMateriasCursadasPorAlumno.php?carrera_id=`+carrera_id+`&alumno_id=`+alumno_id+`">
                                     <?=$alumno_nombre;?> <strong>|</strong> <?=$carrera_nombre?>  <strong> (`+carrera_id+`) </strong>
                                     </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                     Nuevo
                                </li>
                            </ol>
                        </nav>`;

    $("#breadcrumb").html(breadcrumble);   
                 
    $.get("html/materiaCursadaPorAlumno.html",function(data){
        $("#resultado").fadeIn(100).html(data);
        $("#inputAccion").val('Nuevo');
        $("#inputId").val('');
        $("#inputIdAlumno").val(alumno_id);

        $("#inputCarrera").val('<?=$carrera_nombre?> ('+carrera_id+')');
        $("#inputAnioCursado").val();
        $('#inputMateria').select2({
                    theme: "bootstrap4",
        });
        
        $("#inputFechaExpiracion").datepicker({dateFormat: "yy-mm-dd"});

        /*$.post("./funciones/getMateriasPorIdCarrera.php",{"carrera_id":carrera_id},function(data){
          if (data.codigo==100) {
            data.datos.forEach(materia => {
                      $("#inputMateria").append($('<option/>', {
                            text: materia.nombre+' ('+materia.id+')',
                            value: materia.id,
                      }));
                });
                
            };
        },"json")*/

        $('#inputMateria').select2({
                theme: "bootstrap",
                placeholder: "Materia",
                ajax: {
                    url: 'funciones/materiaPorIdCarreraSelect2.php', 
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
                            "searchTerm": datos.term, // search term
                            "carrera_id": carrera_id // search term
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

        $('#inputCursado').prepend("<option value='' selected>** Llamado **</option>");
        $('#inputNota').prepend("<option value='' selected>** Nota **</option>");
        $('#inputEstado').prepend("<option value='' selected>** Estado **</option>");

    });
}


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
    let id = arr[8];
    let carrera_id = <?=$carrera_id;?>


    let breadcrumble = `<nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page"><a href="home.php">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page"><a href="menuAlumno.php">Alumnos</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                     <a href="menuAlumnoMateriasCursadasPorAlumno.php?carrera_id=`+carrera_id+`&alumno_id=`+alumno_id+`">
                                     <?=$alumno_nombre;?> <strong>|</strong> <?=$carrera_nombre?>  <strong> (`+carrera_id+`) |</strong> `+materia_nombre+` <strong>(`+materia_id+`)</strong>
                                     </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                     Editar
                                </li>
                            </ol>
                        </nav>`;
    $("#breadcrumb").html(breadcrumble);     
                 
    $.get("html/materiaCursadaPorAlumno.html",function(data){
        $("#resultado").fadeIn(100).html(data);
        $("#inputAccion").val('Editar');
        $("#inputId").val(id);
        $("#inputIdAlumno").val(alumno_id);
        $("#inputCarrera").val('<?=$carrera_nombre?> ('+carrera_id+')');
        $('#inputMateria').prepend("<option value='"+materia_id+"' >"+materia_nombre+' ('+materia_id+")</option>");
        $('select[name="inputMateria"]').attr('disabled', 'disabled');
        $("#inputAnioCursado").val(cursado_anio);
        $("#inputCursado option[value="+ cursado_id +"]").attr("selected",true);
        $("#inputNota option[value="+ nota +"]").attr("selected",true);
        $("#inputEstado option[value="+ estado +"]").attr("selected",true);
        $("#inputFechaExpiracion").val(FechaVencimientoRegularidad);
        $("#inputFechaExpiracion").datepicker({dateFormat: "yy-mm-dd"});
    });
}

function cursadoGuardar() {
    let accion = $("#inputAccion").val();
    let id = $("#inputId").val();
    let alumno_id = $("#inputIdAlumno").val();
    let materia_id = $("#inputMateria").val();
    let cursado_anio = $("#inputAnioCursado").val();
    let cursado_nombre = $('select[id="inputCursado"] option:selected').text();
    let cursado_id = $("#inputCursado").val();
    let nota = $("#inputNota").val();
    let estado_final = $("#inputEstado").val();
    let fecha_expiracion = $("#inputFechaExpiracion").val();
    let parametros;

    if (accion!="" && alumno_id!=null &&materia_id!=null && cursado_id!=null && cursado_nombre!="" && cursado_anio!="" && nota!="" && estado_final!="")  {
        parametros = {"accion":accion,"id":id,"alumno_id":alumno_id,"materia_id":materia_id,"cursado_anio":cursado_anio,"cursado_nombre":cursado_nombre,"cursado_id":cursado_id,"nota":nota,"estado_final":estado_final,"fecha_expiracion":fecha_expiracion};
        $.post("./funciones/materiaCursadaGuardar.php",parametros,function(datos){
            if (datos.codigo == 100) {
                cargarModulos();
                $("#resultado_accion").html();
                $("#resultado_accion").append(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../public/assets/img/icons/ok_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                            Los datos han sido modificados.</span></i>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button></div></div>`);
            } else {
                $("#resultado_accion").html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../public/assets/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                                            <strong>Error </strong>No se han modificado los datos.</span></i>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button></div></div>`);
            }
        },"json");
    } else {
            alert('tiene valores nulos');
    }

    console.log(parametros);
};    


function cursadoEliminar(id){
    if (confirm("Desea Eliminar el Registro?")) {
            $.post("./funciones/materiaCursadaEliminar.php",{"id":id,"accion":'Eliminar'},function(datos){
                    if (datos.codigo == 100) {
                        cargarModulos();
                        $("#resultado_accion").html();
                        $("#resultado_accion").append(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../public/assets/img/icons/ok_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
                        `+datos.datos+`</span></i>
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button></div></div>`);
                    } else {
                        $("#resultado_accion").html(`<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 "><div class="alert alert-warning alert-dismissible fade show" role="alert"><img src="../public/assets/img/icons/error_icon.png" width="22">&nbsp;<i><span style="color: #000000;">
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