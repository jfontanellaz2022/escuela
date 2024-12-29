<?php
session_start();
set_include_path("../lib/".PATH_SEPARATOR."../conexion/");
define("RECAPTCHA_V3_SECRET_KEY", '6Ld6Y6oaAAAAAIsgbDUIvOKDK3-auQeQeqYTHC4-');
//require_once('conexion.php');
$link = mysqli_connect("127.0.0.1", "uiakkdaq_usuario", "1q2w3e", "uiakkdaq_escuela");
mysqli_set_charset($link, "utf8");
require_once('sanitize.class.php');

//Retornos de esta funcionalidad
// 100 (Atención: El Ingresante se Registrado Correctamente.)
// 1 (Error: El Apellido no esta Válido o Tiene Caracteres Prohibidos.)
// 2 (Error: los Nombres no son Válidos o Tienen Caracteres Prohibidos.)
// 3 (Error: El DNI no es Válida o Tiene Caracteres Prohibidos.)
// 4 (Error: La fecha de Nacimiento no Es Válida.)
// 5 (Error: El Género no Es Válida.)
// 6 (Error: El Numero del Celular no Es Válido.)
// 7 (Error: El Email no Es Válido.)

/*
apellido Varchar(45)
*/
$apellido = (isset($_POST['inputApellido']) && $_POST['inputApellido']!=NULL)?$_POST['inputApellido']:false;
        //die($_REQUEST['inputApellido']."ssssss");


/*
nombre Varchar(45)
*/
$nombres = (isset($_POST['inputNombres']) && $_POST['inputNombres']!=NULL)?$_POST['inputNombres']:false;

/*
dni Varchar(8)
*/
$dni = (isset($_POST['inputDni']) && $_POST['inputDni']!=NULL)?SanitizeVars::STRING_NUMBER($_POST['inputDni']):false;

/*
domicilioCalle Varchar(45)
*/

$domicilio = (isset($_POST['inputDomicilio']) && $_POST['inputDomicilio']!=NULL)?SanitizeVars::STRING_NUMBER_AND_LETTERS_AND_SPACES($_POST['inputDomicilio']):false;
//die($domicilio.'ffff');
/*
telefono Varchar(45)
*/
$celular = (isset($_POST['inputCelular'])&& $_POST['inputCelular']!=NULL)?SanitizeVars::STRING_NUMBER($_POST['inputCelular']):false;

/*
email Varchar(45)
*/
$email = (isset($_POST['inputEmail'])&& $_POST['inputEmail']!=NULL)?SanitizeVars::EMAIL($_POST['inputEmail']):false;

/*
localidad Varchar(50)
*/
$localidad = (isset($_POST['inputLocalidad'])&& $_POST['inputLocalidad']!=NULL)?$_POST['inputLocalidad']:false;


$array_provincia = array('Buenos Aires','Catamarca','Chaco','Chubut','Cordoba','Corrientes','Entre Rios','Formosa','Jujuy','La Rioja','Mendoza','Misiones','Neuquen','Rio Negro','Salta','San Juan','San Luis','Santa Fe','Santa Cruz','Santiago del Estero','Tierra del Fuego','Tucuman');

$provincia = (isset($_POST['inputProvincia'])&& $_POST['inputProvincia']!=NULL && in_array($_POST['inputProvincia'], $array_provincia))?$_POST['inputProvincia']:false;
//echo in_array($_POST['inputProvincia'], $array_provincia);

/*
sexo Enum('F','M','O')
*/
$array_genero = array('F','M','O');
$sexo = (isset($_POST['inputGenero'])&& $_POST['inputGenero']!=NULL && in_array(strtoupper($_POST['inputGenero']), $array_genero))?$_POST['inputGenero']:false;

$array_carreras = array(1,2,4,5,6,9,10,11,12,14,15,16,17,18);
$carrera = (isset($_POST['inputCarrera'])&& $_POST['inputCarrera']!=NULL && in_array(strtoupper($_POST['inputCarrera']), $array_carreras))?$_POST['inputCarrera']:false;

/*
fechaNacimiento Date
*/
$fechaNacimiento = (isset($_POST['inputFechaNacimiento'])&& $_POST['inputFechaNacimiento']!=NULL)?SanitizeVars::DATE($_POST['inputFechaNacimiento']):false;

$token = $_POST['token'];
$action = $_POST['action'];

$respuesta = array();
if (!$apellido || !$nombres || !$dni || !$fechaNacimiento || !$sexo || !$celular || !$email ||
    !$domicilio || !$localidad || !$provincia ||  !$carrera ) {

    $band = false;
    if (!$apellido && !$band) {
       $respuesta['estado'] = 1;
       $respuesta['info'] = 'El Apellido no es Válido o Tiene Caracteres Prohibidos.';
       $band = true;
    };

    if (!$nombres && !$band) {
       $respuesta['estado'] = 2;
       $respuesta['info'] = 'Los Nombres no son Válidos o Tienen Caracteres Prohibidos.';
       $band = true;
    };

    if (!$dni && !$band) {
       $respuesta['estado'] = 3;
       $respuesta['info'] = 'El DNI no es Válido o Tiene Caracteres Prohibidos.';
       $band = true;
    };

    if (!$fechaNacimiento && !$band) {
       $respuesta['estado'] = 4;
       $respuesta['info'] = 'La fecha de Nacimiento no Es Válida.';
       $band = true;
    };

    if (!$sexo && !$band) {
       $respuesta['estado'] = 5;
       $respuesta['info'] = 'El Género no Es Válido.';
       $band = true;
    };

    if (!$celular && !$band) {
       $respuesta['estado'] = 6;
       $respuesta['info'] = 'El Numero del Celular no Es Válido.';
       $band = true;
    };

    if (!$email && !$band) {
       $respuesta['estado'] = 7;
       $respuesta['info'] = 'El Email no Es Válido.';
       $band = true;
    };

    if (!$domicilio && !$band) {
       $respuesta['estado'] = 8;
       $respuesta['info'] = 'El Domicilio no Es Válido.';
       $band = true;
    };

    if (!$localidad && !$band) {
       $respuesta['estado'] = 9;
       $respuesta['info'] = 'La Localidad no Es Válido.';
       $band = true;
    };

    if (!$provincia && !$band) {
       $respuesta['estado'] = 10;
       $respuesta['info'] = 'La Provincia no Es Válido.';
       $band = true;
    };

    if (!$carrera && !$band) {
       $respuesta['estado'] = 11;
       $respuesta['info'] = 'La Carrera no Es Válida.';
       $band = true;
    };
} else {

  // call curl to POST request
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => RECAPTCHA_V3_SECRET_KEY, 'response' => $token)));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);

  curl_close($ch);

  $arrResponse = json_decode($response, true);

  // verify the response

if($arrResponse["success"] == '1' && $arrResponse["action"] == $action && $arrResponse["score"] >= 0.5) {
      $anio = date('Y');
      $idAlumno = "";
      $sql_insert_persona = "INSERT INTO persona(dni, apellido, nombre, fechaNacimiento, nacionalidad, localidad, domicilioCalle, domicilioNumero, domicilioDpto, email, telefono, observaciones, sexo) VALUES
                                                ($dni, '$apellido', '$nombres', '$fechaNacimiento', 'Argentina', '$localidad', '$domicilio', NULL, NULL, '$email', '$celular', NULL, '$sexo')";
      $res_insert_persona = mysqli_query($link,$sql_insert_persona);
      if (!$res_insert_persona) $error_persona = mysqli_errno();

      $sql_insert_alumno = "INSERT INTO alumno (dni, apellido, nombre, anioIngreso, debeTitulo) VALUES
                                               ($dni, '$apellido', '$nombres', $anio, 'No');";
      $res_insert_alumno = @mysqli_query($link,$sql_insert_alumno);
      if (!$res_insert_alumno) {
        $error_usuario = mysqli_errno();
      } else {
          $idAlumno = mysqli_insert_id($link);
      };


      $sql_insert_usuario = "INSERT INTO usuario (dni, idtipo, pass, passwordVencida) VALUES
                                                 ($dni, '1', $dni, 'S');";
      $res_insert_usuario = @mysqli_query($link,$sql_insert_usuario);
      if (!$res_insert_usuario) $error_usuario = mysqli_errno();

      if (!$res_insert_persona || !$res_insert_alumno || !$res_insert_usuario) {


          $sql_select_alumno = "SELECT id FROM alumno WHERE dni=$dni";


          $res_select_alumno = @mysqli_query($link,$sql_select_alumno);
          $fila = mysqli_fetch_assoc($res_select_alumno);

          $idAlumno = $fila['id'];
          $sql_insert_alumno_carrera = "INSERT INTO alumno_estudia_carrera(idAlumno, idCarrera, anio, datosCargados, mesa_especial)
                                        VALUES ($idAlumno, $carrera, $anio, 'N', 'No')";

          $res_insert_alumno_carrera = @mysqli_query($link,$sql_insert_alumno_carrera);


          if (!$res_insert_alumno_carrera) {
            $respuesta['estado'] = '15';
            $respuesta['info'] = 'El Alumno ya esta en esa carrera que se Inscribe.';
          } else {
            $respuesta['estado'] = '101';
            $respuesta['info'] = 'El alumno ya se encuentra registrado y se registro a una nueva carrera exitosamente.' ;
          };

      } else {
        $hoy = date('Y-m-d'); 
        $anio_proximo = $anio + 1;
        $sql_insert_alumno_carrera = "INSERT INTO alumno_estudia_carrera(idAlumno, idCarrera, anio, datosCargados, mesa_especial, fecha_inscripcion)
                                      VALUES ($idAlumno, $carrera, $anio_proximo, 'N', 'No','$hoy')";
        $res_insert_alumno_carrera = @mysqli_query($link,$sql_insert_alumno_carrera);
        if (!$res_insert_alumno_carrera) {
          $respuesta['estado'] = '15';
          $respuesta['info'] = 'La Ocurrio un Problema en la Asignacion de la Carrera.';
        } else {
          $respuesta['estado'] = '100';
          $respuesta['info'] = 'El ingresante se registro en la carrera exitosamente.' ;
          //mail()
        };
      }

  } else {
     $respuesta['estado'] = '12';
     $respuesta['info'] = 'Alerta de Bots.';
  };

};

echo json_encode($respuesta);

 ?>
