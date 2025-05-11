<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "verificarCredenciales.php";
require_once "Alumno.php";

$id_url = "menu_historia";

$idPersona = $_SESSION['arreglo_datos_usuario']['idPersona'];
$objAlumno = new Alumno();
$arr_datos_alumno = $objAlumno->getAlumnoByIdPersona($idPersona);

if (!empty($arr_datos_alumno)) {
  $idAlumno = $arr_datos_alumno['idAlumno'];
  $_SESSION['idAlumno'] = $idAlumno;
}

//var_dump($_SESSION['idAlumno']);exit;



//$_SESSION['arreglo_credenciales_usuario'] = $objUsuario->getCredencialesByIdPersona($idPersona);
?>

<!doctype html>
<html lang="es">
<head>

<?php include_once('../app/views/header.html');?>

<!-- CUSTOM CSS -->
<link rel="stylesheet" href="../public/css/footerProfesor.css" />
  
</head>


<body>
     <!-- NAVBAR -->
     <header>
    <?php include("navbar.php");?>
  </header>
  
  <article>
    <div id="breadcrumb">
      <nav aria-label="breadcrumb" role="navigation">
          <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page">/ Home</li>
          </ol>
      </nav>
    </div>
  </article>

  <article class="container">
    <div id="titulo"></div>
    <hr>
  </article>

  <article class="container">
       <section>
       <div id="grafica" class="col-xs-12 col-sm-6 col-lg-4 d-none">
        <div class="card">
                <div class="card-header bg-info">
                    <h4 class="title-2 m-b-40">Avance en la carrera</h4>
                </div>
                <div class="card-body">
                  <canvas id="avance" ></canvas>
                </div>
            </div>
        </div>
           </div><!-- Cierra Row-->
           <div class="row" id="resultado"></div><!-- Cierra Row-->
           <div class="row" id="controles"></div><!-- Cierra Row-->
        </section>
  </article>

<!-- Modal -->
<?php include_once('./html/cambiarPassword.html');?>

<!-- Modal -->
<?php include_once('./html/cambiarUsuario.html');?>

<!-- FOOTER -->
<?php include("../app/views/footer.html");?>

<!-- JAVASCRIPT LIBRARIES-->
<?php include("../app/views/script_jquery.html");?>
 <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- JAVASCRIPT CUSTOM -->
<script>
let idUsuario = <?=$_SESSION['arreglo_datos_usuario']['id'];?>; 

$("document").ready(function() {
    $('[data-toggle="popover"]').popover({
        html: true,
        sanitize: false,
    });
   cargarCarrerasHistoria(<?=$_SESSION['idAlumno'];?>);
})

//EXPIRED
function expired() {
  location.href = "../logout.php";
}

setTimeout(expired, 60000*20);

// CAMBIO CONTRASEÑA OPCIONAL
$( "#idCambioPwd" ).on('shown.bs.modal', function (e) {
     $('#img_captcha').attr('width',"100");
     $('#img_captcha').attr('height',"25");
     $("#img_captcha").attr('src', '../app/lib/CaptchaSecurityImages.php?width=90&height=30&characters=5');
});

$("#idCambioPwd").on('hide.bs.modal', function(){
     $("#msg_restablecer").addClass("d-none");
     $('#inputPasswordNueva').prop("disabled",false); $('#inputPasswordNueva').val("");
     $('#inputRePasswordNueva').prop("disabled",false); $('#inputRePasswordNueva').val("");
     $('#inputCaptcha').prop("disabled",false); $('#inputCaptcha').val("");
});

$("body").on("click",".img",function(e){
           e.preventDefault();
           if ( $('#inputPasswordNueva').attr('type')=='password') {
                $('#inputPasswordNueva').attr('type', 'text');
                $('#inputRePasswordNueva').attr('type', 'text');
                $('#imgPassword1').attr('width',"23");
                $('#imgPassword2').attr('width',"23");
                $('#imgPassword1').attr('src',"../public/img/icons/eye_closed.png");
                $('#imgPassword2').attr('src',"../public/img/icons/eye_closed.png");
           } else if ($('#inputPasswordNueva').attr('type')=='text') {
                $('#inputPasswordNueva').attr('type', 'password');
                $('#inputRePasswordNueva').attr('type', 'password');
                $('#imgPassword1').attr('width',"23");
                $('#imgPassword2').attr('width',"23");
                $('#imgPassword1').attr('src',"../public/img/icons/eye_open.png");
                $('#imgPassword2').attr('src',"../public/img/icons/eye_open.png");

           }
})

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
              $("#msg_restablecer").removeClass("d-none");
              $("#msg_restablecer").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong>&nbsp;No coinciden las contraseñas.</div>');
         }
      } else {
          $("#msg_restablecer").removeClass("d-none");
          $("#msg_restablecer").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong>&nbsp;Existen campos vacíos.</div>');
      }
});
// FIN CAMBIO CONTRASEÑA OPCIONAL


// JS CAMBIO DE USUARIO
$( "#idCambioUsuario" ).on('shown.bs.modal', function (e) {
     $('#img_captcha_usuario').attr('width',"90");
     $('#img_captcha_usuario').attr('height',"24");
     $("#img_captcha_usuario").attr('src', '../app/lib/CaptchaSecurityImages.php?width=90&height=30&characters=5');
});

$("#idCambioUsuario").on('hide.bs.modal', function(){
     $("#msg_restablecer_usuario").addClass("d-none");
     $('#inputUsuario').prop("disabled",false); $('#inputUsuario').val("");
     $('#inputCaptchaCambioUsuario').prop("disabled",false); $('#inputCaptchaCambioUsuario').val("");
     $('#btnCambiarUsuario').prop("disabled",false);
});

$('#btnCambiarUsuario').click(function(event) {
      let nombre = $('#inputUsuario').val();
      let captcha = $('#inputCaptchaCambioUsuario').val();

      let parametros = {'nombre':nombre, "idUsuario": idUsuario, "captcha":captcha}
      let link = "../API/setNombreUsuario.php?token=<?=$_SESSION['token'];?>";

      if (nombre!="" && idUsuario!="" && captcha!="") {
                  $.post(link,parametros,function(response) {
                       $("#msg_restablecer_usuario").removeClass("d-none");
                       $("#msg_restablecer_usuario").html('<div class="alert alert-'+response.class+'" role="alert"><strong>Atención:</strong>&nbsp;'+response.mensaje+'</div>');
                       if (response.codigo==200) {
                          $('#inputUsuario').prop("disabled",true);
                          $('#inputCaptchaCambioUsuario').prop("disabled",true);
                          $('#btnCambiarUsuario').prop("disabled",true);
                        }
                  },"json");
        
      } else {
         $("#msg_restablecer_usuario").removeClass("d-none");
         $("#msg_restablecer_usuario").html('<div class="alert alert-danger" role="alert"><strong>Error:</strong>&nbsp;Existen campos vacíos.</div>');
      }
});


</script>



<script>

//*************************************************************************************
//********************* CARGA LAS CARRERAS QUE TIENE EL ALUMNO ************************
//*************************************************************************************
let myPieChart;

function obtenerMateriasPorCarrera(idCarrera) {
  let parametros = {"carrera":idCarrera};
  let arr_materias;
  $.ajax({
    url: "../API/findAllMateriasPorCarrera.php?token=<?=$_SESSION['token'];?>",
    type: 'POST',
    data: parametros,
    dataType: "json",
    async: false,
    success: function (response) {
        if (response.codigo==200) {
          arr_materias = response.datos;
        }
    }
});
return arr_materias;
}

function obtenerMateriasAprobadasPorAlumnoPorCarrera(idAlumno, idCarrera) {
  let parametros = {"alumno":idAlumno,"carrera":idCarrera};
  let arr_materias;
  $.ajax({
    url: "../API/findMateriasAprobadasPorAlumnoPorCarrera.php?token=<?=$_SESSION['token'];?>",
    type: 'POST',
    data: parametros,
    dataType: "json",
    async: false,
    success: function (response) {
        if (response.codigo==200) {
          arr_materias = response.datos;
        }
    }
});
return arr_materias;
}



function cargarCarrerasHistoria(idAlumno) {
    //Activo del Menu la opcion Alumnos
    let titulo;
    let bread;
    bread = `<nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home.php?token=<?=$_SESSION['token'];?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Historial Academico</li>
                    </ol>
                </nav>`;
    titulo = `<h1><i>Historia Acad&eacute;mica</i></h1>
                  <h3>Seleccione Carrera</h3>`;

  //Remuevo la class que me deshabilita
  $("#grafica").addClass('d-none');
  $("#resultado").removeClass("disabledbutton");
  let parametros = {'alumno':idAlumno};
  $.post( "../API/findAllCarrerasPorAlumno.php?token=<?=$_SESSION['token'];?>", parametros, function( data ) {
    $("#breadcrumb").html(bread);
    $("#titulo").html(titulo);
    $("#resultado").html("");
    data.datos.forEach(carrera => {
       let resul = `<div class="col-md-4">
             <div class="card" style="width: 18rem;">
                     <img src="../public/img/`+carrera.imagen+`" class="card-img-top">
                     <div class="card-body">
                         <h2 class="card-title">`+carrera.descripcion+`</h2>
                             <h6 class="card-subtitle mb-2 text-muted"></h6>
                         <p class="card-text"></p>
                            <a href="#" class="carrera btn btn-primary btn-block" onclick="cargarHistoriaPorCarrera(`+carrera.id+`,`+idAlumno+`,'`+carrera.descripcion+`')" ><i class="fa fa-check"></i>&nbsp;Ingresar</a>
                     </div>
                   </div>
             </div>`;
        $("#resultado").append(resul);
     });
  },"json");
}


//*********************************************************************************************
//***************** CARGA LA HISTORIA ACADEMICA DEL ALUMNO PARA ESA CARRERA *******************
//*********************************************************************************************
function cargarHistoriaPorCarrera(carrera,idAlumno,descripcion) {
      let idCarrera = carrera;
      let titulo;
      let bread;
      let arr_materia_de_carrera = obtenerMateriasPorCarrera(carrera);
      let arr_materia_aprobada_alumno_en_carrera = obtenerMateriasAprobadasPorAlumnoPorCarrera(idAlumno,carrera);
      let cant_materia_carrera = arr_materia_de_carrera.length;
      let cant_materia_aprobada_carrera = arr_materia_aprobada_alumno_en_carrera.length;
      let diferencia = cant_materia_carrera-cant_materia_aprobada_carrera;
      let arr_grap = [{"descripcion":"Sin Aprobar","existencia":diferencia},{"descripcion":"Aprobadas","existencia":cant_materia_aprobada_carrera}];
      bread = `<nav aria-label="breadcrumb" role="navigation">
                      <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="menuEscritorio.php">Home</a></li>
                      <li class="breadcrumb-item"><a href="#" onclick="cargarCarrerasHistoria(`+idAlumno+`)">Historial Academico</a></li>
                      <li class="breadcrumb-item active" aria-current="page">`+descripcion+`</li>
                      </ol>
                  </nav>`;
      titulo = `<h1><i>Historia Acad&eacute;mica</i></h1>
                    <h3>`+descripcion+` - Materias</h3>`;
      
      $("#breadcrumb").html(bread);
      $("#titulo").html(titulo);
      
      if (document.getElementById("avance")) {
        
                $("#grafica").removeClass('d-none');
                $("#carrera_descripcion").html(descripcion);
                
                if (cant_materia_aprobada_carrera > 0) {
                    var nombre = [];
                    var cantidad = [];
                    for (var i = 0; i < arr_grap.length; i++) {
                        nombre.push(arr_grap[i]['descripcion']);
                        cantidad.push(arr_grap[i]['existencia']);
                    }
                    
                    var ctx = document.getElementById("avance");
                    if (myPieChart) {
                      myPieChart.destroy();
                    }
                    myPieChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: nombre,
                            datasets: [{
                                data: cantidad,
                                backgroundColor: ['#EF280F', '#26D854'],
                            }],
                        },
                    });
                    
                  }
    
      parametros = {'idCarrera':idCarrera};
      $.ajax({
        url:'./funciones/getHistoriaAcademicaPorCarrera.php',
        method: "POST",
        data: parametros,
        success:function(data){
          $("#resultado").html(data).fadeIn('slow');
        }
      });
}
}   




</script>

</body>
</html>
