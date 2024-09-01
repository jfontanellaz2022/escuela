<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

require_once "conexion.php";
require_once "Sanitize.class.php";
require_once "_seguridad.php";

ini_set("default_charset", "UTF-8");
mb_internal_encoding("UTF-8");

$entidad = "Alumno";
$accion = (isset($_POST['accion']) && $_POST['accion']!=NULL)?SanitizeVars::STRING($_POST['accion']):false;
$apellido = (isset($_POST['apellido']) && $_POST['apellido']!=NULL)?SanitizeVars::UTF8($_POST['apellido']):false;
$nombres = (isset($_POST['nombres']) && $_POST['nombres']!=NULL)?SanitizeVars::UTF8($_POST['nombres']):false;
$dni = (isset($_POST['dni']) && $_POST['dni']!=NULL)?SanitizeVars::INT($_POST['dni']):false;
$domicilio = (isset($_POST['domicilio']) && $_POST['domicilio']!=NULL)?SanitizeVars::STRING($_POST['domicilio']):false;
$telefono_caracteristica = (isset($_POST['telefono_caracteristica']))?SanitizeVars::INT($_POST['telefono_caracteristica']):false;
$telefono_numero = (isset($_POST['telefono_numero']))?SanitizeVars::INT($_POST['telefono_numero']):false;
$email = (isset($_POST['email']) && $_POST['email']!=NULL)?SanitizeVars::EMAIL($_POST['email']):false;
$localidad_id = (isset($_POST['localidad_id']) && $_POST['localidad_id']!=NULL)?SanitizeVars::INT($_POST['localidad_id']):false;
$fecha_nacimiento = (isset($_POST['fecha_nacimiento']) && $_POST['fecha_nacimiento']!=NULL)?"'".SanitizeVars::DATE($_POST['fecha_nacimiento'])."'":'NULL';


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
                      WHERE dni = '$dni'";
      //die($sql_persona);        
      $resultado_1 = mysqli_query($conex,$sql_persona);
      if (!$resultado_1) {
            echo "</strong>Errorcode (". mysqli_errno($conex).')</strong>: '.mysqli_error($conex); die;
      }
      
      $filas_afectadas_1 = mysqli_affected_rows($conex);
      $sql_profesor = "UPDATE alumno
                       SET apellido = '$apellido', 
                              nombre = '$nombres'
                       WHERE dni = $dni";
      //die($sql_profesor);   
      $resultado_2 = mysqli_query($conex,$sql_profesor);
      $filas_afectadas_2 = mysqli_affected_rows($conex);           
      if ($filas_afectadas_1!=-1 && $filas_afectadas_2!=-1) {
         $array_resultados['codigo'] = 100;
         $array_resultados['mensaje'] = "Los datos del $entidad <strong>$apellido, $nombres</strong> fueron Actualizados Exitosamente.";
      } else {
         $errorNro =  mysqli_errno($conex);
         $array_resultados['codigo'] = 12;
         $array_resultados['mensaje'] = "Hubo un Error en la Actualizacion de los datos del $entidad. ";
      }
} else if ($accion=='nuevo') {
      $sql_persona = "INSERT persona(dni,apellido,nombre,fechaNacimiento,nacionalidad,idLocalidad,domicilio,email,telefono_caracteristica,telefono_numero) VALUES
                       ('$dni','$apellido','$nombres',$fecha_nacimiento,'Argentina',$localidad_id,'$domicilio','$email','$telefono_caracteristica','$telefono_numero')";
      $resultado_1 = mysqli_query($conex,$sql_persona);
      $bandError = false;
      if (mysqli_errno($conex)=='1062') {
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
            if (mysqli_errno($conex)) {
                  $array_resultados['codigo'] = 9;
                  $array_resultados['mensaje'] = "El $entidad con <strong>$dni</strong> produjo un error.";  
                  $bandError = true;
            }
      };

      $sql_usuario = "INSERT usuario(dni,idtipo,pass) VALUES('$dni',1,'".md5($dni)."')";
      $resultado = mysqli_query($conex,$sql_usuario);
      
      if (!$bandError) {
            $sql_alumno = "INSERT alumno(dni,apellido,nombre) VALUES('$dni','$apellido','$nombres')";
            $resultado_2 = mysqli_query($conex,$sql_alumno);
            if (mysqli_errno($conex)=='1062') {
                  $array_resultados['codigo'] = 10;
                  $array_resultados['mensaje'] = "El $entidad con <strong>$dni</strong> ya se encuentra registrado.";
            } else if (mysqli_errno($conex)=='0') {
                  $array_resultados['codigo'] = 100;
                  $array_resultados['mensaje'] = "El $entidad <strong>$apellido, $nombres</strong> fue creado Exitosamente.";
            } else {
                  $array_resultados['codigo'] = 11;
                  $array_resultados['mensaje'] = "El $entidad con <strong>$dni</strong> no pudo ser registrado."; 
            }
      }
      
};

echo json_encode($array_resultados);



?>
