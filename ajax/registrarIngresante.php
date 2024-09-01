<?php

session_start();
set_include_path("../lib/".PATH_SEPARATOR."../conexion/");
require_once('conexion.php');

require_once('sanitize.class.php');


function enviarEmail($arr) {
   $valorParametro = base64_encode($arr['dni'].'&'.$arr['anio']);
   $url = "https://escuela40.net/comprobante.php?r=" . $valorParametro;
   $para = $arr['email'];
   $titulo = 'Escuela 40 Mariano Moreno - Inscripción Exitosa';

   $mensaje = '<html>'.
         '<head><title>HTML</title></head>'.
         '<body><h1>Datos de la Inscripcion</h1>'.
         'Por favor no elimine éste email'.
         '<hr>'.
         'Enviado desde la Escuela'.
         '<table>'.
         '<tr><th align="left">Apellido</th><td>'.$arr['apellido'].'</td></tr>'.
         '<tr><th align="left">Nombres</th><td>'.$arr['nombres'].'</td></tr>'.
         '<tr><th align="left">DNI</th><td>'.$arr['dni'].'</td></tr>'.
         '<tr><th align="left">Domiclio</th><td>'.$arr['domicilio'].'</td></tr>'.
         '<tr><th align="left">Telefono</th><td>'.$arr['telefono'].'</td></tr>'.
         '<tr><th colspan="2" align="center">Descargue el Comprobante <a href="'.$url.'" target="_blank">Aquí</a></td></tr>'.
         '</table>'.
         '</body>'.
         '</html>';
   $cabeceras = 'MIME-Version: 1.0' . "\r\n";
   $cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
   $cabeceras .= 'From: noreply@escuela40.net';
   mail($para, $titulo, $mensaje, $cabeceras);

 
};


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
$celular = (isset($_POST['inputCelular'])&& $_POST['inputCelular']!=NULL)?$_POST['inputCelular']:false;

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

$array_estado_civil = array(1,2,3,4,5,6);
$estado_civil = (isset($_POST['inputEstadoCivil'])&& $_POST['inputEstadoCivil']!=NULL && in_array(strtoupper($_POST['inputEstadoCivil']), $array_estado_civil))?$_POST['inputEstadoCivil']:false;

$ocupacion = (isset($_POST['inputOcupacion'])&& $_POST['inputOcupacion']!=NULL)?$_POST['inputOcupacion']:false;
$titulo = (isset($_POST['inputTitulo'])&& $_POST['inputTitulo']!=NULL)?$_POST['inputTitulo']:false;
$escuela = (isset($_POST['inputEscuela'])&& $_POST['inputEscuela']!=NULL)?$_POST['inputEscuela']:false;


$token = $_POST['token'];
$action = $_POST['action'];

$estado_civil_desc = "";
if ($estado_civil==1) {
   $estado_civil_desc = "Soltero/a";
} else if ($estado_civil==2) {
   $estado_civil_desc = "Casado/a";
} else if ($estado_civil==3) {
   $estado_civil_desc = "Unión libre o unión de hecho";
} else if ($estado_civil==4) {
   $estado_civil_desc = "Divorciado/a";
} else if ($estado_civil==5) {
   $estado_civil_desc = "Separado/a";
} else if ($estado_civil==6) {
   $estado_civil_desc = "Viudo/a";
};


$hoy = date('Y-m-d'); 
$mes = date('m');
$anio = date('Y');
$anio_ingreso = $anio;
if ($mes>7) {
     $anio_ingreso++;
};



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
//die('sdfsdfsdf');
if(true) {
      $idAlumno = "";
      $sql_insert_persona = "INSERT INTO persona(dni, apellido, nombre, fechaNacimiento, nacionalidad, localidad, domicilioCalle, domicilioNumero, domicilioDpto, email, telefono, observaciones, sexo, estado_civil, ocupacion, titulo, titulo_expedido_por) VALUES
                                                ($dni, '$apellido', '$nombres', '$fechaNacimiento', 'Argentina', '$localidad', '$domicilio', NULL, NULL, '$email', '$celular', NULL, '$sexo', '$estado_civil_desc','$ocupacion','$titulo', '$escuela')";
      $res_insert_persona = mysqli_query($conex,$sql_insert_persona);
      if (!$res_insert_persona) $error_persona = mysqli_errno();

      $sql_insert_alumno = "INSERT INTO alumno (dni, apellido, nombre, anioIngreso, debeTitulo) VALUES
                                               ($dni, '$apellido', '$nombres', $anio_ingreso, 'No');";
                                               
      $res_insert_alumno = @mysqli_query($conex,$sql_insert_alumno);
      if (!$res_insert_alumno) {
        $error_usuario = mysqli_errno();
      } else {
          $idAlumno = mysqli_insert_id($conex);
      };


      $sql_insert_usuario = "INSERT INTO usuario (dni, idtipo, pass, passwordVencida) VALUES
                                                 ($dni, '1', $dni, 'S');";
      $res_insert_usuario = @mysqli_query($conex,$sql_insert_usuario);
      if (!$res_insert_usuario) $error_usuario = mysqli_errno();

      if (!$res_insert_persona || !$res_insert_alumno || !$res_insert_usuario) {


          $sql_select_alumno = "SELECT id FROM alumno WHERE dni=$dni";


          $res_select_alumno = @mysqli_query($conex,$sql_select_alumno);
          $fila = mysqli_fetch_assoc($res_select_alumno);

          $idAlumno = $fila['id'];
          $sql_insert_alumno_carrera = "INSERT INTO alumno_estudia_carrera(idAlumno, idCarrera, anio, datosCargados, mesa_especial, fecha_inscripcion)
                                        VALUES ($idAlumno, $carrera, $anio_ingreso, 'N', 'No','$hoy')";                              

          $res_insert_alumno_carrera = @mysqli_query($conex,$sql_insert_alumno_carrera);


          if (!$res_insert_alumno_carrera) {
            $respuesta['estado'] = '15';
            $respuesta['info'] = 'El Alumno ya esta en esa carrera que se Inscribe.';
          } else {
            $respuesta['estado'] = '101';
            $respuesta['info'] = 'El alumno ya se encuentra registrado y se registro a una nueva carrera exitosamente. Revise su casilla de correo para imprimir el comprobante.' ;
            $arr = array('apellido'=>$apellido,'nombres'=>$nombres,'dni'=>$dni,'anio'=>$anio_ingreso,'domicilio'=>$domicilio,'telefono'=>$celular,'email'=>$email);
            enviarEmail($arr);
          };

      } else {
        $sql_insert_alumno_carrera = "INSERT INTO alumno_estudia_carrera(idAlumno, idCarrera, anio, datosCargados, mesa_especial, fecha_inscripcion)
                  VALUES ($idAlumno, $carrera, $anio_ingreso, 'N', 'No','$hoy')";                              
        $res_insert_alumno_carrera = @mysqli_query($conex,$sql_insert_alumno_carrera);
        if (!$res_insert_alumno_carrera) {
          $respuesta['estado'] = '15';
          $respuesta['info'] = 'La Ocurrio un Problema en la Asignacion de la Carrera.';
        } else {
          $respuesta['estado'] = '100';
          $respuesta['info'] = 'El ingresante se registro en la carrera exitosamente. Revise su casilla de correo para imprimir el comprobante.' ;
          $arr = array('apellido'=>$apellido,'nombres'=>$nombres,'dni'=>$dni,'anio'=>$anio_ingreso,'domicilio'=>$domicilio,'telefono'=>$celular,'email'=>$email);
          enviarEmail($arr);
        };
      }

  } else {
     $respuesta['estado'] = '12';
     $respuesta['info'] = 'Alerta de Bots.';
  };

};

echo json_encode($respuesta);

 ?>
