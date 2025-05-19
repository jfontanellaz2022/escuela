<?php
set_include_path('./app/models/'.PATH_SEPARATOR.'./app/lib'.PATH_SEPARATOR.'./');
session_start();
header("X-Frame-Options: DENY");

require_once 'Parameters.php';
require_once "Carrera.php";
$_SESSION['token'] = bin2hex(random_bytes(35));
$ARREGLO_CARRERAS = [];
$objCarrera = new Carrera();
$ARREGLO_CARRERAS = $objCarrera->getCarrerasHabilitadas();

if (!isset($_SESSION['token'])) {
  header("location: index.php");
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de Gestión - E.N.S. N° 40</title>
  <link rel="icon" href="./public/img/favicon.ico">

  <!--CDN de Bootstrap-->
  <link rel="stylesheet" href="./public/sass/style.css">
</head>

<body class="">

 
  <header class=""> <!--Encabezado-->
    <nav class="navbar navbar-expand-lg bg-body-tertiary text-center" data-bs-theme="light"> <!--Navegación-->
      <div class="container-fluid">
        <a class="navbar-brand" href="./index.php">
          <img src="./public/img/logo_623x781.png" alt="Logo" width="40" class="d-inline-block align-text-center">
        </a>

        <button class="navbar-toggler bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            
            <li class="nav-item">
              <a class="nav-link" aria-current="page" href="#" data-bs-toggle="modal" data-bs-target="#modalAccederSistema">Acceder al sistema</a>
            </li>
            
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Inscripción a carreras
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="./inscribirseCarrera.php">¡Inscribirse ahora!</a></li>
                <li><a class="dropdown-item disabled" href="#">Oferta académica</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item" aria-current="page" href="#" data-bs-toggle="modal" data-bs-target="#modalDescargarInscripcion">Comprobante Inscripción</a>
                </li>
              </ul>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link disabled dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Capacitaciones
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item disabled" href="#">Registrarse</a></li>
                <li><a class="dropdown-item disabled" href="#">Ver ofertas disponibles</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item disabled" href="modalDescargarInscripcion">Descargar comprobante</a></li>
                <a class="nav-link" aria-current="page" href="#" data-bs-toggle="modal" data-bs-target="#modalAccederSistema">Acceder al sistema</a>
              </ul>
            </li>

            <li class="nav-item">
              <a class="nav-link" aria-disabled="true" href="https://ens40-sfe.infd.edu.ar/sitio/">Institucional</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" aria-disabled="true" href="https://ens40-sfe.infd.edu.ar/aula/acceso.cgi">Campus Virtual</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <section class="container-fluid pb-4 pt-2 pt-xl-3"> <!--Cuerpo de página-->
    <div> <!--Carrusel-->
      <div id="carouselExampleAutoplaying" class="carousel slide " data-bs-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="./public/img/Esc40_1.png" class="d-block w-100" alt="...">
          </div>
          <div class="carousel-item">
            <img src="./public/img/Esc40_2.png" class="d-block w-100" alt="...">
          </div>
          <div class="carousel-item">
            <img src="./public/img/Esc40_3.png" class="d-block w-100" alt="...">
          </div>
          <div class="carousel-item">
            <img src="./public/img/Esc40_4.png" class="d-block w-100" alt="...">
          </div>
          
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
      </div>

      <hr>
      
      <div class="accordion" id="accordionExample">
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              ¿Para qué sirve el sistema de gestión de alumnado?
            </button>
          </h2>
          <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              <p>Podrás consultar tus avances en la carrera que estás cursando, así como ver el progreso en tiempo real de tus cursadas aprobadas, consultar tu condición en la carrera, revisar calificaciones y además, podrás inscribirte a exámenes finales.</p>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
              ¿Cómo creo mi usuario en la plataforma?
            </button>
          </h2>
          <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              Para crear un usuario en la plataforma, primero deberás inscribirte a alguna de nuestras <a>Ofertas Académicas</a> consultando previamente las fechas de inscripción. Luego de enviar tu inscripción, deberás esperar a la aprobación de la misma, donde se te enviará por tu correo electrónico los pasos a seguir.
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
              ¿Que hago si olvido/pierdo mis datos personales de acceso?
            </button>
          </h2>
          <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body">
              En caso de que hayas olvidado tu contraseña, podrás recuperarla haciendo uso de tu correo electrónico (vease '<a class="" aria-current="page" href="#" data-bs-toggle="modal" data-bs-target="#modalOlvideContrasenia">Olvide mi contraseña</a>'). Como dato adicional, tu usuario corresponderá siempre a tu número de documento (DNI). Recuerda seleccionar el perfil que se adapte a tu condición Institucional (Ej. Si eres un estudiante regular, tu perfil es "Alumno").

              Si haz seguido todos los pasos anteriores y aún así no puedes acceder al sistema, contacta a <a href="mailto:jfontanellaz@gmail.com">Soporte técnico</a>.
            </div>
          </div>
        </div>
      </div>
    </div>
    <input type="hidden" class="form-control" name="inputToken" id="inputToken" value="<?=$_SESSION['token']?>">
   
    <div class="modal fade" id="modalAccederSistema" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

          <div class="modal-header"> 
            <h1 class="modal-title fs-5" id="exampleModalLabel">Acceder al sistema</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body"> 
            <form action="" method="POST"> 
               
              <div class="form-row">
                <div class="form-group col-xs-6 col-sm-12 col-md-12">
                    <label class="text-white" for="inputUsuario"><strong>Usuario</strong></label>
                    <div class="input-group mb-3">
                          <input type="text" class="form-control" name="inputUsuario" id="inputUsuario" maxlength="15" placeholder="Usuario">
                          <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">
                                    <img id="imgUsuario" src="./public/img/icons/user.png" width="30">
                                </span>
                          </div>
                    </div>
                </div>
              </div>
              
              <div class="form-row">
                <div class="form-group col-xs-6 col-sm-12 col-md-12">
                    <label class="text-white" for="inputPassword"><strong>Contraseña</strong></label>
                    <div class="input-group mb-3">
                          <input type="password" class="form-control" name="inputPassword" id="inputPassword" maxlength="15" placeholder="Contraseña">
                          <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">
                                    <a href="#"><img id="imgPassword" src="./public/img/icons/eye_closed.png" width="30"></a>
                                </span>
                          </div>
                    </div>
                </div>
              </div> 


            </form>

            <div id="msg_ingreso" class="mb-3 d-none"></div>
            
          </div>

          <div class="modal-footer"> 
            
            <div class="col d-grid gap-2">
               <button type="button" id="btnIngresar" class="btn btn-primary btn-block">Ingresar</button>
               <a class="" aria-current="page" href="#" data-bs-toggle="modal" data-bs-target="#modalOlvideContrasenia">¿Olvidaste tu contraseña?</a>
            </div>

            </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalOlvideContrasenia" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

          <div class="modal-header"> 
            <h1 class="modal-title fs-5" id="exampleModalLabel">Restablecer Contraseña</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body"> 
            <form action="" method="POST"> 
              
 
              <div class="mb-3"> 
                <label for="inputRestablecerEmail" class="form-label">E-mail</label>
                <input type="text" id="inputRestablecerEmail" class="form-control" maxlength="45" placeholder="Ingrese Email" aria-describedby="passwordHelpBlock">
              </div>

              <div class="mb-3"> 
                <img src="./app/lib/CaptchaSecurityImages.php?width=90&height=30&characters=5&token=<?=$_SESSION['token'];?>" alt="Captcha" id='img_captcha'/>&nbsp;
                <input class="form-control mr-sm-2" type="text" placeholder="Ingrese Codigo" aria-label="Search" id='inputCaptcha' maxlength="5" required >&nbsp;
              </div>       

              <div id="msg_restablecer" class="mb-3 d-none">
              </div> 
          </div>

          <div class="modal-footer"> 
            <div class="col d-grid gap-2">
               <button type="button" id="btnRestablecer" class="btn btn-primary btn-block">Reestablecer</button>
            </div>
          </div>

        </div>
      </div>
    </div>

   <!-- ***********************************************  PERFIL   ******************************************************************** -->

    <div class="modal fade" id="modalPerfilSelection" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

          <div class="modal-header"> 
            <h1 class="modal-title fs-5" id="exampleModalLabel">Seleccione Perfil</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body"> 
                <div class="row">
                                 <div class="col-md-6">
                                          <div class="card">
                                                <div class="card-body">
                                                      <h5 class="card-title">Bedel</h5>
                                                      <p class="card-text"></p>
                                                      <button class="btn btn-primary btn-block" onclick="location.href='./mod_bedel/home.php'">
                                                        <img src="./public/img/icons/secretario_icon.png" width="120">
                                                      </button>
                                                </div>
                                            </div>
                                  </div>
                                    
                                  <div class="col-md-6">
                                          <div class="card">
                                                <div class="card-body">
                                                      <h5 class="card-title">Profesor</h5>
                                                      <p class="card-text"></p>
                                                      <button class="btn btn-primary btn-block" onclick="location.href='./mod_profesor/home.php'">
                                                        <img src="./public/img/icons/icon_teacher.png" width="120">
                                                      </button>
                                                </div>
                                            </div>
                                    </div>

                                    <div class="col-md-6">
                                          <div class="card">
                                                <div class="card-body">
                                                      <h5 class="card-title">Alumno</h5>
                                                      <p class="card-text"></p>
                                                      <button class="btn btn-primary btn-block" onclick="location.href='./mod_alumno/home.php'">
                                                        <img src="./public/img/icons/icon_student.png" width="120">
                                                      </button>
                                                </div>
                                            </div>
                                    </div> 


                </div>
          </div>

          <div class="modal-footer"> 
            <div class="col d-grid gap-2">
              
            </div>
          </div>

        </div>
      </div>
    </div>

   <!-- ************************************************************************************************************************ -->


    <div class="modal fade" id="modalDescargarInscripcion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

          <div class="modal-header"> 
            <h1 class="modal-title fs-5" id="exampleModalLabel">Descargar Inscripción</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <form action=""> 
 
              
              <div class="mb-3">
                <label for="inputDescargarInscripcionCarrera" class="form-label">Carrera</label>
                <select id="inputDescargarInscripcionCarrera" class="form-select" aria-label="Default select example">
                <option value="">** Seleccionar Carrera **</option>
                <?php
                                 if (count($ARREGLO_CARRERAS)>0) {
                                     foreach($ARREGLO_CARRERAS as $item) {
                                         echo "<option value='" . $item['id'] . "'>" . $item['descripcion'] . "(" . $item['id'] . ")</option>";
                                     }
                                 }
                            ?>
                </select>
              </div>

              <div class="mb-3"> 
                <label for="inputDescargarInscripcionDni" class="form-label">Número de DNI</label>
                <input type="text" id="inputDescargarInscripcionDni" class="form-control" placeholder="Ingrese DNI" maxlength=8 aria-describedby="passwordHelpBlock">
              </div>

              <div class="mb-3"> 
                <img src="./app/lib/CaptchaSecurityImages.php?width=90&height=30&characters=5&token=<?=$_SESSION['token'];?>" alt="Captcha" id='img_captcha'/>&nbsp;
                <input class="form-control mr-sm-2" type="text" placeholder="Ingrese Codigo" aria-label="Search" id='inputDescargarInscripcionCaptcha' maxlength="5" required >&nbsp;
              </div>       

              <div id="msg_descargar_inscripcion" class="mb-3 d-none">
              </div> 

          </div>

          <div class="modal-footer"> 
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="location.href='./index.php'">Volver al inicio</button>
            <button type="button" id="btnDescargarInscripcion" class="btn btn-primary" >Descargar</button>
          </div>
        </div>
      </div>
    </div>

  </section>

 
  <footer class="pt-4 pb-4 bg-body-tertiary text-center"> 
    <p>Todos los derechos reservados</p>
    <p class="datoContacto">Escuela Normal Superior Nº 40 “Mariano Moreno” | Direccion: J.M. Bullo 1402- 3070 San Cristóbal | Tel. Fax 03408-422447. Horario 18:00 a 21:00hs</p>

    <!--Icono de facebook-->
    <a href="https://m.facebook.com/ens40NivelSuperior" target="_blank" title="Facebook ENS. 40">
    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
      <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
    </svg>
    </a>


    <!--Icono de instagram-->
    <a href="https://www.instagram.com/ens40_nivelsuperior/?hl=es" target="_blank" title="Instagram ENS. 40">
    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
      <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
    </svg>
    </a>
    <!--Icono de whatsapp-->
    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
      <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
    </svg> -->

    <!--Contactar por correo electrónico-->


  </footer>
</body>

<!--Script JQUERY-->
<script src="./public/js/jquery-3.4.1.min.js"></script>
<!--Script JS de Bootstrap-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>

  
$('#btnIngresar').click(function(event) {
      let usuario = $('#inputUsuario').val();
      let pwd = $('#inputPassword').val();
      let token = $('#inputToken').val();

      let parametros = {'inputUsuario':usuario,'inputPassword':pwd,'token':token}
      let link = "API/auth.php";

      $.post(link,parametros,function(response) {
            if (response.codigo==200) {
                let perfiles = response.datos;
                let arr_perfiles = perfiles.split(',');
                let cant_items = arr_perfiles.length;

                //console.info(cant_items,perfiles);

                if (cant_items==1) {
                    if (response.datos=='Alumno') {
                      $(location).attr('href','mod_alumno/home.php?token=<?=$_SESSION['token'];?>');
                    } else if (response.datos=='Profesor') {
                        $(location).attr('href','mod_profesor/home.php?token=<?=$_SESSION['token'];?>');
                    } else if (response.datos=='Bedel') {
                        $(location).attr('href','mod_bedel/home.php?token=<?=$_SESSION['token'];?>');
                    };
                } if (cant_items==2) {
                    $("#modalPerfilSelection").modal('show');
                }

                //arr_perfiles/

                
            } else {
                $("#msg_ingreso").removeClass('d-none');
                $("#msg_ingreso").html('<div class="alert alert-'+response.class+'" role="alert">'+response.mensaje+'.</div>');
            }
      },"json")

});

$('#btnRestablecer').click(function(event) {
      let email = $('#inputRestablecerEmail').val();
      let captcha = $('#inputCaptcha').val();
      let token = $('#inputToken').val();

      let parametros = {'inputEmail':email, "inputCodigo":captcha}
      let link = "API/restablecerPassword.php?token=<?=$_SESSION['token'];?>";

      $.post(link,parametros,function(response) {
               $("#msg_restablecer").removeClass("d-none");
               if (response.codigo==200) {
                    $("#msg_restablecer").html('<div class="alert alert-'+response.class+'" role="alert"><img src="./public/img/icons/ok_icon.png" width="20">&nbsp;'+response.mensaje+'</div>');
                    $('#inputRestablecerEmail').prop("disabled",true);
                    $('#inputCaptcha').prop("disabled",true);
                    $('#btnRestablecer').prop("disabled",true);
               } else {
                    $("#msg_restablecer").html('<div class="alert alert-'+response.class+'" role="alert"><img src="./public/img/icons/error_icon1.png" width="20">&nbsp;'+response.mensaje+'</div>');
               } 
      },"json");
});


$('#btnDescargarInscripcion').click(function(event) {
      let carrera = $('#inputDescargarInscripcionCarrera').val();
      let dni = $('#inputDescargarInscripcionDni').val();
      let captcha = $('#inputDescargarInscripcionCaptcha').val();
      let token = $('#inputToken').val();
      let link = "API/findInscripcionPorDni.php?token=<?=$_SESSION['token'];?>";
      let parametros = {'carrera':carrera,'dni':dni,'codigo':captcha,'token':token}

      if (carrera && dni && captcha && token) {
            $.post(link,parametros,function(response) {
                    console.info(response);
                    $("#msg_descargar_inscripcion").removeClass("d-none");
                    if (response.codigo==200) {
                          $("#msg_restablecer").html('<div class="alert alert-'+response.alert+'" role="alert"><img src="./public/img/icons/ok_icon.png" width="20">&nbsp;'+response.mensaje+'</div>');
                          $('#inputDescargarInscripcionDni').prop("disabled",true);
                          $('#inputDescargarInscripcionCaptcha').prop("disabled",true);
                          $('#btnDescargarInscripcion').prop("disabled",true);
                          $("#msg_descargar_inscripcion").html('<div class="alert alert-'+response.alert+'" role="alert">Para descargar la Inscripción hacer click <a href="'+response.url+'" target="_blank">Aquí</a>.</div>');
                    } else {
                          $("#msg_descargar_inscripcion").html('<div class="alert alert-'+response.alert+'" role="alert"><img src="./public/img/icons/error_icon1.png" width="20">&nbsp;'+response.mensaje+'</div>');
                    } 
            },"json");
     } else {
      $("#msg_descargar_inscripcion").html('<div class="alert alert-danger" role="alert"><img src="./public/img/icons/error_icon1.png" width="20">&nbsp;No ha completado todos los campos.</div>');
     }
});


$("#modalOlvideContrasenia").on('hide.bs.modal', function(){
    $("#msg_restablecer").addClass("d-none");
    $('#inputRestablecerEmail').val("");
    $('#inputCaptcha').val("");
});

$("#imgPassword").click(function(e){
           e.preventDefault();
           if ( $('#inputPassword').attr('type')=='password') {
                $('#inputPassword').attr('type', 'text');
                $('#imgPassword').attr('width',"30");
                $('#imgPassword').attr('src',"./public/img/icons/eye_closed.png");
           } else if ($('#inputPassword').attr('type')=='text') {
                $('#inputPassword').attr('type', 'password');
                $('#imgPassword').attr('width',"30");
                $('#imgPassword').attr('src',"./public/img/icons/eye_open.png");

           }
})

</script>


</html>