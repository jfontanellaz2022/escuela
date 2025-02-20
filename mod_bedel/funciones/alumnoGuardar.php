<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once 'verificarCredenciales.php';
require_once "Sanitize.class.php";
require_once "Persona.php";
require_once "Alumno.php";

$entidad = "Alumno";
$accion = (isset($_POST['accion']) && $_POST['accion']!=NULL)?SanitizeVars::STRING($_POST['accion']):false;
$persona_id =(isset($_POST['persona_id']))?SanitizeVars::INT($_POST['persona_id']):false;
$apellido = (isset($_POST['apellido']) && $_POST['apellido']!=NULL)?SanitizeVars::UTF8($_POST['apellido']):false;
$nombres = (isset($_POST['nombres']) && $_POST['nombres']!=NULL)?SanitizeVars::UTF8($_POST['nombres']):false;
$dni = (isset($_POST['dni']) && $_POST['dni']!=NULL)?SanitizeVars::INT($_POST['dni']):false;
$domicilio = (isset($_POST['domicilio']) && $_POST['domicilio']!=NULL)?SanitizeVars::STRING($_POST['domicilio']):false;
$telefono_caracteristica = (isset($_POST['telefono_caracteristica']))?SanitizeVars::INT($_POST['telefono_caracteristica']):false;
$telefono_numero = (isset($_POST['telefono_numero']))?SanitizeVars::INT($_POST['telefono_numero']):false;
$email = (isset($_POST['email']) && $_POST['email']!=NULL)?SanitizeVars::EMAIL($_POST['email']):false;
$localidad_id = (isset($_POST['localidad_id']) && $_POST['localidad_id']!=NULL)?SanitizeVars::INT($_POST['localidad_id']):false;
$fecha_nacimiento = (isset($_POST['fecha_nacimiento']) && $_POST['fecha_nacimiento']!=NULL)?SanitizeVars::DATE($_POST['fecha_nacimiento']):'NULL';

$anioIngreso = (isset($_POST['anio_ingreso']))?SanitizeVars::INT($_POST['anio_ingreso']):false;
$debeTitulo = (isset($_POST['debe_titulo']))?SanitizeVars::STRING($_POST['debe_titulo']):false;

//var_dump($_POST);die;
//die($persona_id. '-'. $accion.'-'.$apellido.'-'.$nombres.'-'.$dni.'-'.$domicilio.'-'.$telefono_caracteristica.'-'.$telefono_numero.'-'.$email.'-'.$localidad_id.'-'.$fecha_nacimiento . '-' .$anioIngreso .'-' . $debeTitulo);
$alumno_id = 0;
$array_resultados = [];

if ($persona_id) {
      $objPersona = new Persona();
      $param['idPersona'] = $persona_id;
      $param['apellido'] = $apellido;
      $param['nombre'] = $nombres;
      $param['dni'] = $dni;
      $param['fecha_nacimiento'] = $fecha_nacimiento;
      $param['localidad_id'] = $localidad_id;
      $param['domicilio'] = $domicilio;
      $param['email'] = $email;
      $param['telefono_caracteristica'] = $telefono_caracteristica;
      $param['telefono_numero'] = $telefono_numero;
      $res = $objPersona->save($param);
      if (!$res) {
            $array_resultados['codigo'] = 500;
            $array_resultados['alert'] = 'danger';
            $array_resultados['mensaje'] = "Hubo un Error en la Actualizacion de los datos del $entidad.";  
            echo json_encode($array_resultados);exit;
      } 

      $objAlumno = new Alumno();
      $arr_datos_alumno = $objAlumno->getAlumnoByIdPersona($persona_id);
      $alumno_id = $arr_datos_alumno['idAlumno'];
      if ($alumno_id) {
            $param_alumno['id'] = $alumno_id;
            $param_alumno['anio_ingreso'] = $anioIngreso;
            $param_alumno['debe_titulo'] = $debeTitulo;
            $param_alumno['idPersona'] = $persona_id;
            $res_alumno = $objAlumno->save($param_alumno);
            if ($res_alumno) {
                  $array_resultados['codigo'] = 200;
                  $array_resultados['alert'] = 'success';
                  $array_resultados['mensaje'] = "Los datos del $entidad <strong>$apellido, $nombres</strong> fueron Actualizados Exitosamente.";  
            } else {
                  $array_resultados['codigo'] = 500;
                  $array_resultados['alert'] = 'danger';
                  $array_resultados['mensaje'] = "Hubo un Error en la Actualizacion de los datos del $entidad.";  
                  echo json_encode($array_resultados);exit;
            }
      }
   
} else {
     /* $persona_id = 0;
      $sql_persona = "INSERT persona(dni,apellido,nombre,fecha_nacimiento,nacionalidad,idLocalidad,domicilio,email,telefono_caracteristica,telefono_numero) VALUES
                       ('$dni','$apellido','$nombres',$fecha_nacimiento,'Argentina',$localidad_id,'$domicilio','$email','$telefono_caracteristica','$telefono_numero')";
      

      try {
            $resultado_1 = mysqli_query($conex,$sql_persona);
            $persona_id = mysqli_insert_id($conex);
            
            $sql_alumno = "INSERT alumno(dni,anio_ingreso,debe_titulo,habilitado,idPersona) VALUES('$dni','$anioIngreso','$debeTitulo','Si',$persona_id)";
            //var_dump($persona_id,$sql_alumno);exit;
            $resultado = mysqli_query($conex,$sql_alumno);
            
            $sql_usuario = "INSERT usuario(dni,nombre,idtipo,pass, idPersona,idRol) VALUES('$dni','$dni',1,'".md5($dni)."',".$persona_id.",4)";
            //var_dump($persona_id,$sql_usuario);exit;
            $resultado = mysqli_query($conex,$sql_usuario);  

      } catch(Exception $e) {
            $sql_persona = "UPDATE persona 
                            SET apellido = '$apellido',
                                nombre = '$nombres',
                                fechaNacimiento = $fecha_nacimiento,
                                nacionalidad = 'Argentina',
                                idLocalidad = $localidad_id,
                                domicilio = '$domicilio',
                                email = '$email',
                                telefono_caracteristica = '$telefono_caracteristica',
                                telefono_numero = '$telefono_numero'
                            WHERE dni = $dni";
            $resultado_1 = mysqli_query($conex,$sql_persona);
            
      };*/
      
      $array_resultados['codigo'] = 200;
      $array_resultados['mensaje'] = "El $entidad con <strong>$dni</strong> ya se ha registrado.";

};

echo json_encode($array_resultados);



?>
