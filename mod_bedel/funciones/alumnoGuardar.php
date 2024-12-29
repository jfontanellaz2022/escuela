<?php
set_include_path('../../app/models/v1/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../'.PATH_SEPARATOR.'../../conexion/');

require_once 'verificarCredenciales.php';
require_once "conexion.php";
require_once "Sanitize.class.php";

ini_set("default_charset", "UTF-8");
mb_internal_encoding("UTF-8");

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
$fecha_nacimiento = (isset($_POST['fecha_nacimiento']) && $_POST['fecha_nacimiento']!=NULL)?"'".SanitizeVars::DATE($_POST['fecha_nacimiento'])."'":'NULL';

$anioIngreso = (isset($_POST['anio_ingreso']))?SanitizeVars::INT($_POST['anio_ingreso']):false;
$debeTitulo = (isset($_POST['debe_titulo']))?SanitizeVars::STRING($_POST['debe_titulo']):false;

//var_dump($_POST);die;
//die($accion.'-'.$apellido.'-'.$nombres.'-'.$dni.'-'.$domicilio.'-'.$telefono_caracteristica.'-'.$telefono_numero.'-'.$email.'-'.$localidad_id.'-'.$fecha_nacimiento);

$array_resultados = array();

if ($accion=='editar') {
      $sql_persona = "UPDATE persona
                      SET apellido ='$apellido', 
                              nombre = '$nombres',
                              fechaNacimiento = $fecha_nacimiento,
                              idLocalidad = $localidad_id,
                              domicilio = '$domicilio',
                              email = '$email',
                              telefono_caracteristica = '$telefono_caracteristica',
                              telefono_numero = '$telefono_numero'
                      WHERE id = $persona_id";
      $resultado_1 = mysqli_query($conex,$sql_persona);
      $sql_alumno = "UPDATE alumno
                     SET debe_titulo ='$debeTitulo', 
                         anio_ingreso = '$anioIngreso'
                     WHERE idPersona = $persona_id";
      //var_dump($sql_alumno);exit;                     
      $resultado_1 = mysqli_query($conex,$sql_alumno);
      if (!$resultado_1) {
            echo "</strong>Errorcode (". mysqli_errno($conex).')</strong>: '.mysqli_error($conex); die;
      }
      
      $filas_afectadas_1 = mysqli_affected_rows($conex);
              
      if ($filas_afectadas_1!=-1) {
         $array_resultados['codigo'] = 100;
         $array_resultados['mensaje'] = "Los datos del $entidad <strong>$apellido, $nombres</strong> fueron Actualizados Exitosamente.";
      } else {
         $errorNro =  mysqli_errno($conex);
         $array_resultados['codigo'] = 12;
         $array_resultados['mensaje'] = "Hubo un Error en la Actualizacion de los datos del $entidad. ";
      }
} else if ($accion=='nuevo') {
      $persona_id = 0;
      $sql_persona = "INSERT persona(dni,apellido,nombre,fechaNacimiento,nacionalidad,idLocalidad,domicilio,email,telefono_caracteristica,telefono_numero) VALUES
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
            
      };
      
      $array_resultados['codigo'] = 200;
      $array_resultados['mensaje'] = "El $entidad con <strong>$dni</strong> ya se ha registrado.";

};

echo json_encode($array_resultados);



?>
