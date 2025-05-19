<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/');
session_start();
date_default_timezone_set('America/Argentina/Buenos_Aires');


require_once 'SanitizeCustom.class.php';
require_once 'Persona.php';
require_once 'Alumno.php';
require_once 'AlumnoEstudiaCarrera.php';
require_once 'Usuario.php';

function enviarEmail($arr) {
   $carrera_id = $arr['carrera_id'];
   $anio_lectivo = $arr['anio_lectivo'];
   $apellido = $arr['apellido'];
   $nombres = $arr['nombres'];
   $dni = $arr['dni'];
   $domicilio = $arr['domicilio'];
   $telefono = $arr['telefono'];
   $fecha_nacimiento = $arr['fecha_nacimiento'];
   $email = $arr['email'];
   $url_pdf = "";
   $codificacion = base64_encode($anio_lectivo.'&'.$dni.'&'.$carrera_id);
   $url_pdf .= 'https://escuela40.net/API/reporteInscripcionCarrera.php?p='. $codificacion;

   $para      = $email;
   $titulo    = 'E.N.S. 40 "Mariano Moreno" - Datos de la registracion del ingresante ';
              
   $imageUrl = "https://escuela40.net/public/img/encabezado_ens40_1.jpeg";

   $mensaje = "<html>
                       <head>
                         <title>Registracion del Ingresante</title>
                         <style>
                           .header {
                             font-size: 24px;
                             font-weight: bold;
                             color: #333;
                             text-align: center;
                             margin-top: 20px;
                           }
                           .code {
                             font-size: 28px;
                             font-weight: bold;
                             color: #4CAF50;
                             text-align: center;
                           }
                           .content {
                             font-size: 16px;
                             color: #555;
                             text-align: center;
                           }

                           .content-datos {
                             font-size: 16px;
                             color: #555;
                             text-align: left;
                           }

                           .container {
                             width: 100%;
                             max-width: 600px;
                             margin: 0 auto;
                             padding: 20px;
                             border: 1px solid #ddd;
                             border-radius: 8px;
                           }
                           .image {
                             width: 100%;
                             height: auto;
                           }
                         </style>
                       </head>
                       <body>
                         <div class='container'>
                           <img src='$imageUrl' alt='Encabezado' class='image'>
                           <p class='header'>La Resgistraci&oacute;n se realiz&oacute; correctamente.</p>
                           
                           <p class='content'>Para ingresar a la aplicaci&oacute;n de Gesti&oacute;n de la escuela debe ir a la url <a href='https://escuela40.net'>https://escuela40.net</a> .
                             Una vez que ingreso al link, deber&aacute; hace click en la opcion 'Acceder al Sistema', se le abrir&aacute; un nueva ventana, 
                             y desde ah&iacute; debe ingresar en usuario su n&uacute;mero de DNI, y en contrase&ntilde;a tambi&eacute;n debe ingresar
                             su n&uactue;mero de dni (una vez que ingres&oacute; por razones de seguridad se recomienda cambiar la contrase&ntilde;a). 
                           </p>
                           <p class='content'>Para descargar el formulario generado por la inscripci&oacute;n haga click <a href='".$url_pdf."' target='_blank'>Aqu&iacute;</a>.</p>
                           <p class='content'>Recuerda que en &eacute;ste sistema usted va a gestionar muchas tareas administrativas mientras transite la carrera.</p>
                           <p class='content'>El Instituto le da la Bienvenida!!! <br>E.N.S. 40 'Mariano Moreno'.</p>
                         </div>
                       </body>
                       </html>";
              
              
              // Encabezados para enviar correo en formato HTML
               $headers = "MIME-Version: 1.0" . "\r\n";
               $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
               $headers .= "From: soporte@escuela40.net" . "\r\n";
              mail($para, $titulo, $mensaje, $headers);


};


//Retornos de esta funcionalidad
// 200 (Atención: El Ingresante se Registrado Correctamente.)
// 500 (Error: El Apellido no esta Válido o Tiene Caracteres Prohibidos.)

/*
apellido Varchar(45)
*/
$apellido = (isset($_POST['inputApellido']) && $_POST['inputApellido']!=NULL)?SanitizeCustom::APELLIDO_NOMBRES($_POST['inputApellido']):false;
        //die($_REQUEST['inputApellido']."ssssss");


/*
nombre Varchar(45)
*/
$nombres = (isset($_POST['inputNombres']) && $_POST['inputNombres']!=NULL)?SanitizeCustom::APELLIDO_NOMBRES($_POST['inputNombres']):false;

/*
dni Varchar(8)
*/
$dni = (isset($_POST['inputDni']) && $_POST['inputDni']!=NULL)?SanitizeCustom::DOCUMENTO_CUIL($_POST['inputDni'],8,8):false;

/*
domicilioCalle Varchar(45)
*/

$domicilio = (isset($_POST['inputDomicilio']) && $_POST['inputDomicilio']!=NULL)?SanitizeCustom::DOMICILIO($_POST['inputDomicilio']):false;
//die($domicilio.'ffff');
/*
telefono Varchar(45)
*/
$celular_caracteristica = (isset($_POST['inputCelularCar'])&& $_POST['inputCelularCar']!=NULL)?SanitizeCustom::NUMEROS($_POST['inputCelularCar'],2,5):false;

$celular_numero = (isset($_POST['inputCelularNum'])&& $_POST['inputCelularNum']!=NULL)?SanitizeCustom::NUMEROS($_POST['inputCelularNum'],5,8):false;
/*
email Varchar(45)
*/
$email = (isset($_POST['inputEmail'])&& $_POST['inputEmail']!=NULL)?SanitizeVars::EMAIL($_POST['inputEmail']):false;

/*
localidad Varchar(50)
*/
$localidad = (isset($_POST['inputLocalidad'])&& $_POST['inputLocalidad']!=NULL)?$_POST['inputLocalidad']:false;

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


//*******************TOKEN  *****************************/
$token = (isset($_GET['token']))?$_GET['token']:false;
$array_resultados = [];
if ($token!=$_SESSION['token']) {
  $array_resultados['codigo'] = 500;
  $array_resultados['class'] = 'danger';
  $array_resultados['mensaje'] = 'El Token es INCORRECTO.';
  echo json_encode($array_resultados);die;
}
//****************************************************** */

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


//var_dump($apellido,$nombres,$dni,$fechaNacimiento,$sexo,$celular_caracteristica,$celular_numero,$email,$domicilio,$localidad,$carrera);
//exit;
//var_dump('1.- ' . $token,'2.- ' . $_SESSION['token']);exit;
$respuesta = array();

if ($token!=$_SESSION['token']) {
            $respuesta['codigo'] = 500;
            $respuesta['alert'] = 'danger';
            $respuesta['mensaje'] = 'El Token es INCORRECTO.';
            echo json_encode($respuesta);die;
} else {


if (!$apellido || !$nombres || !$dni || !$fechaNacimiento || !$sexo || !$celular_caracteristica || !$celular_numero || 
    !$email || !$domicilio || !$localidad || !$carrera || !$estado_civil_desc || !$ocupacion || !$titulo || !$escuela) {
      //var_dump('Entro',$sexo);exit;
         $band = false;

         if (!$estado_civil_desc && !$band) {
            $respuesta['codigo'] = 500;
            $respuesta['alert'] = 'danger';
            $respuesta['mensaje'] = 'El Estado Civil no es Válido o Tiene Caracteres Prohibidos.';
            echo json_encode($respuesta);exit;
            $band = true;
         };

         if (!$ocupacion && !$band) {
            $respuesta['codigo'] = 500;
            $respuesta['alert'] = 'danger';
            $respuesta['mensaje'] = 'La Ocupación no es Válida o Tiene Caracteres Prohibidos.';
            echo json_encode($respuesta);exit;
            $band = true;
         };

         if (!$titulo && !$band) {
            $respuesta['codigo'] = 500;
            $respuesta['alert'] = 'danger';
            $respuesta['mensaje'] = 'El Titulo de Secundaria no es Válido o Tiene Caracteres Prohibidos.';
            echo json_encode($respuesta);exit;
            $band = true;
         };

         if (!$escuela && !$band) {
            $respuesta['codigo'] = 500;
            $respuesta['alert'] = 'danger';
            $respuesta['mensaje'] = 'El nombre de la Escuela Secundaria no es Válido o Tiene Caracteres Prohibidos.';
            echo json_encode($respuesta);exit;
            $band = true;
         };

         if (!$apellido && !$band) {
            $respuesta['codigo'] = 500;
            $respuesta['alert'] = 'danger';
            $respuesta['mensaje'] = 'El Apellido NO es Válido o Tiene Caracteres Prohibidos.';
            echo json_encode($respuesta);exit;
            $band = true;
         };

         if (!$nombres && !$band) {
            $respuesta['codigo'] = 500;
            $respuesta['alert'] = 'danger';
            $respuesta['mensaje'] = 'Los Nombres NO son Válidos o Tienen Caracteres Prohibidos.';
            echo json_encode($respuesta);exit;
            $band = true;
         };

         if (!$dni && !$band) {
            $respuesta['codigo'] = 500;
            $respuesta['alert'] = 'danger';
            $respuesta['mensaje'] = 'El DNI NO es Válido o Tiene Caracteres Prohibidos.';
            echo json_encode($respuesta);exit;
            $band = true;
         };

         if (!$fechaNacimiento && !$band) {
            $respuesta['codigo'] = 500;
            $respuesta['alert'] = 'danger';
            $respuesta['mensaje'] = 'La fecha de Nacimiento NO es Válida.';
            echo json_encode($respuesta);exit;
            $band = true;
         };

         if (!$sexo && !$band) {
            $respuesta['codigo'] = 500;
            $respuesta['alert'] = 'danger';
            $respuesta['mensaje'] = 'El Género NO es Válido.';
            echo json_encode($respuesta);exit;
            $band = true;
         };

         if (!$celular_caracteristica && !$band) {
            $respuesta['codigo'] = 500;
            $respuesta['alert'] = 'danger';
            $respuesta['mensaje'] = 'El Numero del Celular NO es Válido.';
            echo json_encode($respuesta);exit;
            $band = true;
         };

         if (!$celular_numero && !$band) {
            $respuesta['codigo'] = 500;
            $respuesta['alert'] = 'danger';
            $respuesta['mensaje'] = 'El Numero del Celular NO es Válido.';
            echo json_encode($respuesta);exit;
            $band = true;
         };

         if (!$email && !$band) {
            $respuesta['codigo'] = 500;
            $respuesta['alert'] = 'danger';
            $respuesta['mensaje'] = 'El Email NO es Válido.';
            echo json_encode($respuesta);exit;
            $band = true;
         };

         if (!$domicilio && !$band) {
            $respuesta['codigo'] = 500;
            $respuesta['alert'] = 'danger';
            $respuesta['mensaje'] = 'El Domicilio NO es Válido.';
            echo json_encode($respuesta);exit;
            $band = true;
         };

         if (!$localidad && !$band) {
            $respuesta['codigo'] = 500;
            $respuesta['alert'] = 'danger';
            $respuesta['mensaje'] = 'La Localidad NO es Válido.';
            echo json_encode($respuesta);exit;
            $band = true;
         };

         if (!$carrera && !$band) {
            $respuesta['codigo'] = 500;
            $respuesta['alert'] = 'danger';
            $respuesta['mensaje'] = 'La Carrera NO es Válida.';
            echo json_encode($respuesta);exit;
            $band = true;
         };
         
} else {

         $persona_id = $alumno_id = 0;
         $objPersona = new Persona();
         $arr_datos_persona = $objPersona->getPersonaByDni($dni);
         $param_persona['dni'] = $dni;
         $param_persona['apellido'] = $apellido;
         $param_persona['nombres'] = $nombres;
         $param_persona['genero'] = $sexo;
         $param_persona['fecha_nacimiento'] = $fechaNacimiento;
         $param_persona['localidad_id'] = $localidad;
         $param_persona['domicilio'] = $domicilio;
         $param_persona['email'] = $email;
         $param_persona['telefono_caracteristica'] = $celular_caracteristica;
         $param_persona['telefono_numero'] = $celular_numero;
         $param_persona['estado_civil'] = $estado_civil_desc;
         $param_persona['ocupacion'] = $ocupacion;
         $param_persona['titulo'] = $titulo;
         $param_persona['titulo_expedido_por'] = $escuela;

         if (!$arr_datos_persona) { // ** SI NO EXISTE PERSONA ENTONCES LA CREA **
                     $persona_id = $objPersona->save($param_persona); // CREA PERSONA

                     // CREA EL ALUMNO
                     $objAlumno = new Alumno();
                     $param_alumno['anio_ingreso'] = $anio_ingreso;
                     $param_alumno['debe_titulo'] = 'No';
                     $param_alumno['habilitado'] = 'Si';
                     $param_alumno['idPersona'] = $persona_id;
                     $alumno_id = $objAlumno->save($param_alumno);

                     // CREA EL USUARIO
                     $objUsuario = new Usuario();
                     $param_usuario['nombre'] = $dni;
                     $param_usuario['password'] = $dni;
                     $param_usuario['idPersona'] = $persona_id;
                     $param_usuario['idRol'] = 4;
                     $objUsuario->save($param_usuario);

                     // CREA EL VINCULO ENTRE ALUMNO Y CARRERA
                     $objAlumnoEstudiaCarrera = new AlumnoEstudiaCarrera();
                     $param_alumno_carrera['idAlumno'] = $alumno_id;
                     $param_alumno_carrera['idCarrera'] = $carrera;
                     $param_alumno_carrera['anio'] = $anio_ingreso;
                     $param_alumno_carrera['mesa_especial'] = 'No';
                     $param_alumno_carrera['fecha_inscripcion'] = date('Y-m-d');
                     $objAlumnoEstudiaCarrera->save($param_alumno_carrera); 

                     $respuesta['codigo'] = 200;
                     $respuesta['alert'] = 'success';
                     $respuesta['mensaje'] = 'Los datos de la inscripción se realizó correctamente.';

                     enviarEmail(['apellido'=>$apellido,'nombres'=>$nombres,'dni'=>$dni,'telefono'=>'(' . $celular_caracteristica . ') ' . $celular_numero,
                                  'domicilio'=>$domicilio,'fecha_nacimiento'=>$fechaNacimiento,'email'=>$email,'carrera_id'=>$carrera,'anio_lectivo'=>$anio_ingreso]);


         } else {  // ** SI EXISTE PERSONA ENTONCES ACTUALIZA SUS DATOS??? **                
                     
                     $persona_id = $arr_datos_persona['idPersona'];
                     $param_persona['idPersona'] = $persona_id;
                     $objPersona->save($param_persona); //Actualizo los datos de persona????
                     
                     // CREA EL ALUMNO
                     $objAlumno = new Alumno();
                     $alumno_id = 0;
                     $arr_datos_alumno = $objAlumno->getAlumnoByIdPersona($persona_id);
                     $param_alumno['anio_ingreso'] = $anio_ingreso;
                     $param_alumno['debe_titulo'] = 'No';
                     $param_alumno['habilitado'] = 'Si';
                     $param_alumno['idPersona'] = $persona_id;
                     if (!$arr_datos_alumno) {
                           $alumno_id = $objAlumno->save($param_alumno);
                     } else {
                           $alumno_id = $arr_datos_alumno['idAlumno'];
                     }
                     
                     // CREA EL USUARIO
                     $objUsuario = new Usuario();
                     $arr_datos_usuario = $objUsuario->getUsuarioByIdPersona($persona_id);
                     
                     $param_usuario['nombre'] = $dni;
                     $param_usuario['password'] = $dni;
                     $param_usuario['idPersona'] = $persona_id;
                     $param_usuario['idRol'] = 4;
                     if (!$arr_datos_usuario) {
                        //$objUsuario->save($param_usuario);
                     }

                     
                     // CREA EL ENTRE ALUMNO Y CARRERA
                     $objAlumnoEstudiaCarrera = new AlumnoEstudiaCarrera();
                     $arr_datos_alumno_carrera = $objAlumnoEstudiaCarrera->getAlumnoEstudiaCarrera($carrera,$alumno_id);
                     $param_alumno_carrera['idAlumno'] = $alumno_id;
                     $param_alumno_carrera['idCarrera'] = $carrera;
                     $param_alumno_carrera['anio'] = $anio_ingreso;
                     $param_alumno_carrera['mesa_especial'] = 'No';
                     $param_alumno_carrera['fecha_inscripcion'] = date('Y-m-d');
                     if (!$arr_datos_alumno_carrera) {
                        $objAlumnoEstudiaCarrera->save($param_alumno_carrera); 
                     }

                     $respuesta['codigo'] = 200;
                     $respuesta['alert'] = 'success';
                     $respuesta['mensaje'] = 'Los datos de la inscripción se realizó correctamente.';
                     
                     enviarEmail(['apellido'=>$apellido,'nombres'=>$nombres,'dni'=>$dni,'telefono'=>'(' . $celular_caracteristica . ') ' . $celular_numero,
                                  'domicilio'=>$domicilio,'fecha_nacimiento'=>$fechaNacimiento,'email'=>$email,'carrera_id'=>$carrera,'anio_lectivo'=>$anio_ingreso]);
    
         }


} 

}
echo json_encode($respuesta);

 ?>
