<?php
set_include_path('./app/models/'.PATH_SEPARATOR.'./app/lib/'.PATH_SEPARATOR.'./');
session_start();
header("X-Frame-Options: DENY");

require_once "Carrera.php";
require_once "Parameters.php";

$_SESSION['token'] = bin2hex(random_bytes(35));
$ARREGLO_CARRERAS = [];
$objCarrera = new Carrera();
$ARREGLO_CARRERAS = $objCarrera->getCarrerasHabilitadasRegistracion();
if (!isset($_SESSION['token'])) {
  headers("location: index.php");
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
  <link rel="stylesheet" href="./public/css/select2.min.css">
  <link rel="stylesheet" href="./public/css/select2-bootstrap.css">
</head>

<body class="">


  <header class=""> <!--Encabezado-->
    <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary" data-bs-theme="light"> <!--Navegación-->
      <div class="container-fluid">
        <a class="navbar-brand" href="./index.php">
          <img src="./public/img/logo_623x781.png" alt="Logo" width="40" class="d-inline-block align-text-center">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            
            <li class="nav-item">
              <!--Disparador del MODAL para acceder al sistema-->
              <a class="nav-link" aria-current="page" href="#" data-bs-toggle="modal" data-bs-target="#modalAccederSistema">Acceder al sistema</a>
            </li>
            
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Inscripción a carreras
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">¡Inscribirse ahora!</a></li>
                <li><a class="dropdown-item disabled" href="#">Oferta académica</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item" aria-current="page" href="#" data-bs-toggle="modal" data-bs-target="#modalDescargarInscripcion">Comprobante Inscripción</a>
                </li>
              </ul>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Capacitaciones
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item disabled" href="#">Registrarse</a></li>
                <li><a class="dropdown-item disabled" href="#">Ver ofertas disponibles</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item disabled" href="#">Descargar comprobante</a></li>
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


  <section class="container-fluid pb-4 pt-5"> <!--Cuerpo de página-->
    <section class="container-fluid pb-4 pt-5">
      <h1 class="" style="color:darkblue;">Registro de Ingresante</h1>
      <h3>Escuela Normal Superior 40 "“Mariano Moreno"</h3>

      <div class="row g-3 align-items-center">
        <div class="col-auto">
          <a class="btn" href="./index.php">Inicio</a>
        </div>
        <div class="col-auto">
          <span id="passwordHelpInline" class="form-text">
            / Registro de Ingresante
          </span>
        </div>
      </div>
      
      <div class="container mt-2 mb-5">
        <div class="text-center mt-5 mb-2" style="font-family: 'Fredoka', sans-serif;">
          <h2>FORMULARIO DE INSCRIPCIÓN A CARRERAS</h2>
        </div>	
        
        <div class="text-black text-center" style="font-family: 'Fredoka', sans-serif;">
          <p>Datos Obligatorios <span style="color: red;">*</span></p>
          <font color='red'><strong>Nota:</strong> La carrera de Profesorado de Biología está sujeta a la creación de horas por parte del Ministerio de Educación.</font>
        </div>
        
        <form class="row g-3" action="" method="POST"> 
          <div class="row">
            <div class="col-12">
              <hr>
              <label for="inputCarrera" class="form-label" style="font-family: 'Fredoka', sans-serif;">Carrera a la que se Inscribe<span style="color: red;">*</span></label>
              <select class="form-select" aria-label="Default select example" name="inputCarrera" id="inputCarrera" style="font-family: 'Fredoka', sans-serif;">
              <?php
                                 if (count($ARREGLO_CARRERAS)>0) {
                                     foreach($ARREGLO_CARRERAS as $item) {
                                         echo "<option value='" . $item['id'] . "'>" . $item['descripcion'] . "(" . $item['id'] . ")</option>";
                                     }
                                 }
                            ?>
              </select>
              <input type="hidden" class="form-control" name="inputToken" id="inputToken" value="<?=$_SESSION['token']?>">
            </div>
            
            <div class="col-sm-6">
              <label for="inputApellido" class="form-label pt-2" style="font-family: 'Fredoka', sans-serif;">Apellido <span style="color: red;">*</span></label>
              <input type="text" class="form-control" name="inputApellido" id="inputApellido" style="font-family: 'Fredoka', sans-serif;" placeholder="Ingrese Apellido" maxlength="45">
            </div>
            
            <div class="col-sm-6 mb-3">
              <label for="inputNombres" class="form-label pt-2" style="font-family: 'Fredoka', sans-serif;">Nombre<span style="color: red;">*</span></label>
              <input type="text" class="form-control" name="inputNombres" id="inputNombres" style="font-family: 'Fredoka', sans-serif;" placeholder="Ingresa Nombre" maxlength="45">
            </div>
            
            <div class="col-sm-6">
              <label for="inputDni" class="form-label" style="font-family: 'Fredoka', sans-serif;">DNI (Sin puntos)<span style="color: red;">*</span></label>
              <input type="text" class="form-control" name="inputDni" id="inputDni" maxlength=8 style="font-family: 'Fredoka', sans-serif;" placeholder="Ingresa DNI">
            </div>
            
            <div class="col-sm-6 mb-3">
              <label for="fechaNac" class="form-label" style="font-family: 'Fredoka', sans-serif;">Fecha Nacimiento<span style="color: red;">*</span></label>
              <input type="date" class="form-control" name="inputFechaNacimiento" id="inputFechaNacimiento">
            </div>

            <div class="col-md-6 ">
              <label for="inputLocalidad" class="form-label" style="font-family: 'Fredoka', sans-serif;">Localidad<span style="color: red;">*</span></label>
              <select id="inputLocalidad" class="form-control select2" placeholder="Localidad" required>
                <option selected="selected" value="1408">SAN CRISTOBAL (PCIA. SANTA FE)</option>
              </select>
              
              <div class="row g-3 align-items-center pt-2">
                <div class="col-3">
                  <label for="inputCaracteristicaTelefono" class="form-label" style="font-family: 'Fredoka', sans-serif;">Area<span style="color: red;">*</span></label>
                  <input type="tel" name="inputCaracteristicaTelefono" id="inputCaracteristicaTelefono" class="form-control" style="font-family: 'Fredoka', sans-serif;" placeholder="sin el 0" maxlength="5">
                </div>
                
                <div class="col-9">
                  <label for="inputNumeroTelefono" class="form-label" style="font-family: 'Fredoka', sans-serif;">Celular<span style="color: red;">*</span></label>
                  <input type="tel" name="inputNumeroTelefono" id="inputNumeroTelefono" class="form-control" style="font-family: 'Fredoka', sans-serif;" placeholder="sin el 15" maxlength="10">
                </div>
              </div>
            </div>
            
            <div class="col-md-6">
              <label for="inputGenero" class="form-label" style="font-family: 'Fredoka', sans-serif;">Género<span style="color: red;"></span></label>
              <select class="form-select" aria-label="Default select example" name="inputGenero" id="inputGenero" style="font-family: 'Fredoka', sans-serif;"git>
                <option selected>Seleccione Género</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
                <option value="O">No binario</option>
              </select>
              
              <label for="inputEmail" class="form-label  pt-2" style="font-family: 'Fredoka', sans-serif;">Email<span style="color: red;">*</span></label>
              <input type="email" class="form-control" name="inputEmail" id="inputEmail" placeholder="correo@mail.com" maxlength="45">
            </div>
            
            <div class="col-12">
              <label for="domicilio" class="form-label pt-2" style="font-family: 'Fredoka', sans-serif;">Domicilio<span style="color: red;">*</span></label>
              <input type="text" class="form-control" name="inputDomicilio" id="inputDomicilio" placeholder="San Martin 1122" maxlength="50">
            </div>
            
            <div class="col-sm-6">
              <label for="inputEstadoCivil" class="form-label pt-2" style="font-family: 'Fredoka', sans-serif;">Estado Civil<span style="color: red;">*</span></label>
              <select class="form-select" aria-label="Default select example" name="inputEstadoCivil" id="inputEstadoCivil" style="font-family: 'Fredoka', sans-serif;">
                  <option value="1">Soltero/a</option>
                  <option value="2">Casado/a</option>
                  <option value="3">Unión libre o unión de hecho</option>
                  <option value="4">Divorciado/a</option>
                  <option value="5">Separado/a</option>
                <option value="6">Viudo/a</option>
              </select>
            </div>

            <div class="col-sm-6 mb-3">
              <label for="inputOcupacion" class="form-label pt-2" style="font-family: 'Fredoka', sans-serif;">Ocupacion </label>
              <input type="text" class="form-control" name="inputOcupacion" id="inputOcupacion" maxlength="50">
            </div>
            
            <div class="col-sm-6">
              <label for="inputTitulo" class="form-label" style="font-family: 'Fredoka', sans-serif;">Titulo Secundario</label>
              <input type="text" class="form-control" name="inputTitulo" id="inputTitulo" maxlength="100">
            </div>
            
            <div class="col-sm-6 mb-3">
              <label for="inputEscuela" class="form-label" style="font-family: 'Fredoka', sans-serif;">Escuela de la que egresó</label>
              <input type="text" class="form-control" name="inputEscuela" id="inputEscuela" maxlength="50">
            </div>

            <div id="mensaje" class="d-grid gap-2 pt-2 d-none">
                  
            </div>

            <div class="d-grid gap-2 pt-2">
              <button type="button" id="btnRegistrar" class="btn btn-success" name="enviar" id="enviar">Enviar</button>
              <button type="button" class="btn btn-primary" onclick="location.href='index.php'">Volver</button>
            </div>
          </div>


        </form>
      </div>


    </section>

    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
      <symbol id="check-circle-fill" viewBox="0 0 16 16">
          <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
      </symbol>
      <symbol id="info-fill" viewBox="0 0 16 16">
          <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
      </symbol>
      <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16">
          <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
      </symbol>
    </svg>

    <!-- Modal: Acceder al sistema-->
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

            <div id="msg_ingreso" class="mb-3 d-none">
              </div>
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

    <!-- Modal: Olvidé la contraseña-->
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


<!-- Modal: Descargar Inscripcion -->
<div class="modal fade" id="modalDescargarInscripcion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

          <div class="modal-header"> <!--Encabezado del modal-->
            <h1 class="modal-title fs-5" id="exampleModalLabel">Descargar Inscripción</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body"> <!--Cuerpo del modal-->
            <form action="" method="POST"> <!--Aquí iría el recuperarCuenta.php-->
 
              
              <div class="mb-3"> <!--Seleccionar perfil (Bedel, profesor, alumno/a)-->
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

              <div class="mb-3"> <!--Ingresar correo electrónico -->
                <label for="inputDescargarInscripcionDni" class="form-label">Número de DNI</label>
                <input type="text" id="inputDescargarInscripcionDni" class="form-control" placeholder="Ingrese DNI" maxlength=8 aria-describedby="passwordHelpBlock">
              </div>

              <div class="mb-3"> <!--Ingresar correo electrónico -->
                <img src="./app/lib/CaptchaSecurityImages.php?width=90&height=30&characters=5&token=<?=$_SESSION['token'];?>" alt="Captcha" id='img_captcha'/>&nbsp;
                <input class="form-control mr-sm-2" type="text" placeholder="Ingrese Codigo" aria-label="Search" id='inputDescargarInscripcionCaptcha' maxlength="5" required >&nbsp;
              </div>       
              <!--Aquí iría el Captcha-->

              <div id="msg_descargar_inscripcion" class="mb-3 d-none"> <!--Ingresar correo electrónico -->
              </div> 

          </div>

          <div class="modal-footer"> <!--Pie del modal-->
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick="location.href='./index.php'">Volver al inicio</button>
            <button type="button" id="btnDescargarInscripcion" class="btn btn-primary" >Descargar</button>
          </div>
        </div>
      </div>
    </div>

  </section>

  <footer class="pt-4 pb-4 bg-body-tertiary text-center"> <!--Pie de página-->
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
<script src="./public/js/select2.min.js"></script>

<script>


let txtInputArea = document.querySelector('#inputCaracteristicaTelefono');

txtInputArea.addEventListener('keyup',()=>{
    let str = txtInputArea.value;
    if (str.charAt(0)==0) {
       txtInputArea.value = "";
    }
});


$(document).ready(function() {
    let url_select2_localidad = "API/findLocalidad.php?token=<?=$_SESSION['token'];?>";// Esto puede cambiar 
    $('#inputLocalidad').select2({
                                theme: "bootstrap",
                                placeholder: "Ingrese la Localidad",
                                allowClear: true,
                                language: {
                                            noResults: function() {
                                              return "No hay resultado";        
                                            },
                                            searching: function() {
                                              return "Buscando..";
                                            }
                                          },
                                ajax: {
                                    url: url_select2_localidad,
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

$('#btnIngresar').click(function(event) {
      let usuario = $('#inputUsuario').val();
      let pwd = $('#inputPassword').val();
      let token = $('#inputToken').val();

      let parametros = {'inputUsuario':usuario,'inputPassword':pwd,'token':token}
      let link = "API/auth.php";

      $.post(link,parametros,function(response) {
        if (response.codigo==200) {
                if (response.datos=='Alumno') {
                    $(location).attr('href','mod_alumno/home.php?token=<?=$_SESSION['token'];?>');
                } else if (response.datos=='Profesor') {
                    $(location).attr('href','mod_profesor/home.php?token=<?=$_SESSION['token'];?>');
                } else if (response.datos=='Bedel') {
                    $(location).attr('href','mod_bedel/home.php?token=<?=$_SESSION['token'];?>');
                    
                } 
        } else {
            $("#msg_ingreso").removeClass('d-none');
            $("#msg_ingreso").html('<div class="alert alert-'+response.class+'" role="alert">'+response.mensaje+'.</div>');
        }
      },"json")

});

$("#btnRegistrar").click(function(event) {
    let car = $('#inputCarrera').val();
    let ape = $('#inputApellido').val();
    let nom = $('#inputNombres').val();
    let dni = $('#inputDni').val();
    let f_nac = $('#inputFechaNacimiento').val();
    let loc = $('#inputLocalidad').val();
    let cel_car = $('#inputCaracteristicaTelefono').val();
    let cel_num = $('#inputNumeroTelefono').val();
    let genero = $('#inputGenero').val();
    let email = $('#inputEmail').val();
    let dom = $('#inputDomicilio').val();
    let estado_civil = $('#inputEstadoCivil').val();
    let ocupacion = $('#inputOcupacion').val();
    let titulo = $('#inputTitulo').val();
    let escuela = $('#inputEscuela').val();
    let token = $('#inputToken').val();
    let alert  = "";

    $("#mensaje").removeClass('d-none');
    if (car && ape && nom && dni && f_nac && loc && cel_car && cel_num && genero && email && dom && estado_civil && ocupacion && titulo && escuela && token) {
        let url = "API/insertIngresante.php?token=<?=$_SESSION['token'];?>";
        let parametros = {'inputCarrera':car, 'inputApellido':ape,'inputNombres':nom,'inputDni':dni,
                          'inputFechaNacimiento':f_nac,'inputLocalidad':loc, 'inputCelularCar':cel_car,'inputCelularNum':cel_num,
                          'inputGenero':genero,'inputEmail':email,'inputDomicilio':dom,'inputEstadoCivil':estado_civil,
                          'inputOcupacion':ocupacion,'inputTitulo':titulo,'inputEscuela':escuela,'token':token};

        $.post(url,parametros,function(response){
              if (response.codigo==200) {
                  $('#inputCarrera').prop('disabled',true);
                  $('#inputApellido').prop('disabled',true);
                  $('#inputNombres').prop('disabled',true);
                  $('#inputDni').prop('disabled',true);
                  $('#inputFechaNacimiento').prop('disabled',true);
                  $('#inputLocalidad').prop('disabled',true);
                  $('#inputCaracteristicaTelefono').prop('disabled',true);
                  $('#inputNumeroTelefono').prop('disabled',true);
                  $('#inputGenero').prop('disabled',true);
                  $('#inputEmail').prop('disabled',true);
                  $('#inputDomicilio').prop('disabled',true);
                  $('#inputEstadoCivil').prop('disabled',true);
                  $('#inputOcupacion').prop('disabled',true);
                  $('#inputTitulo').prop('disabled',true);
                  $('#inputEscuela').prop('disabled',true);
                  $('#inputToken').prop('disabled',true);
                  $('#btnRegistrar').prop('disabled', true);
                  alert = `<div class="alert alert-`+response.alert+` d-flex align-items-center" role="alert">
                              <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Success:" style="width: 16px; height: 16px;"><use xlink:href="#check-circle-fill"/></svg>
                              <div>
                                  `+response.mensaje+`
                              </div>
                              <div>
                                  Se ha enviado un correo electrónico con instrucciones de como acceder al sistema.
                              </div>
                      </div>`;
              } else {
                  alert = `<div class="alert alert-`+response.alert+` d-flex align-items-center text-center justify-content-center" role="alert" style="font-size: 14px; padding: 4px 10px; margin: 5px 0; border-radius: 4px; min-height: 30px;">
                              <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Danger:" style="width: 16px; height: 16px;"><use xlink:href="#exclamation-triangle-fill"/></svg>
                              <div>
                                  `+response.mensaje+`
                              </div>
                          </div>`;
              }

              $("#mensaje").removeClass('d-none');    
              $("#mensaje").html(alert);         
        },"json");
    } else {
        alert = `<div class="alert alert-danger d-flex align-items-center text-center justify-content-center" role="alert" style="font-size: 14px; padding: 4px 10px; margin: 5px 0; border-radius: 4px; min-height: 30px;">
                      <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Danger:" style="width: 16px; height: 16px;"><use xlink:href="#exclamation-triangle-fill"/></svg>
                      <div>
                          No se han completado algunos datos Obligatorios.
                      </div>
                </div>`;
        $("#mensaje").removeClass('d-none');    
        $("#mensaje").html(alert);        
    }
  


});


$('#btnRestablecer').click(function(event) {
      let email = $('#inputRestablecerEmail').val();
      let captcha = $('#inputCaptcha').val();
      let token = $('#inputToken').val();

      let parametros = {'inputEmail':email, "inputCodigo":captcha}
      let link = "API/restablecerPassword.php?token=<?=$_SESSION['token'];?>";

      $.post(link,parametros,function(response) {
               console.info(response);
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