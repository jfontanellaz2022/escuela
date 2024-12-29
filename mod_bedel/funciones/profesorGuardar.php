<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

require_once "Profesor.php";
require_once "Persona.php";
require_once "Usuario.php";
require_once 'SanitizeCustom.class.php';

require_once "_seguridad.php";

$entidad = "Profesor";

$id = (isset($_POST['id']) && $_POST['id']!=NULL)?SanitizeCustom::INT($_POST['id']):false;
$apellido = (isset($_POST['apellido']) && $_POST['apellido']!=NULL)?SanitizeCustom::APELLIDO_NOMBRES($_POST['apellido']):false;
$nombres = (isset($_POST['nombres']) && $_POST['nombres']!=NULL)?SanitizeCustom::APELLIDO_NOMBRES($_POST['nombres']):false;
$dni = (isset($_POST['dni']) && $_POST['dni']!=NULL)?SanitizeCustom::DOCUMENTO_CUIL($_POST['dni'],8,8):false;
$domicilio = (isset($_POST['domicilio']) && $_POST['domicilio']!=NULL)?SanitizeCustom::DOMICILIO($_POST['domicilio'],2,40):false;
$telefono_caracteristica = (isset($_POST['telefono_caracteristica']) && $_POST['telefono_caracteristica']!=NULL)?SanitizeCustom::INT($_POST['telefono_caracteristica']):false;
$telefono_numero = (isset($_POST['telefono_numero']) && $_POST['telefono_numero']!=NULL)?SanitizeCustom::INT($_POST['telefono_numero']):false;
$email = (isset($_POST['email']) && $_POST['email']!=NULL)?SanitizeCustom::EMAIL($_POST['email']):false;
$localidad_id = (isset($_POST['localidad_id']) && $_POST['localidad_id']!=NULL)?SanitizeCustom::INT($_POST['localidad_id']):false;
$fecha_nacimiento = (isset($_POST['fecha_nacimiento']) && $_POST['fecha_nacimiento']!=NULL)?SanitizeCustom::DATE($_POST['fecha_nacimiento']):false;
$array_resultados = array();
$objetoPersona = new Persona;
$objetoProfesor = new Profesor;

if ($id) {
    $persona_id = $objetoPersona->getPersonaByDni($dni)['id'];
    $profesor_id = $objetoProfesor->getProfesorByDni($dni)['id'];

    $idPer = $objetoPersona->save(['id'=>$persona_id,'apellido'=>$apellido,'nombres'=>$nombres,'fecha_nacimiento'=>$fecha_nacimiento,
                                'localidad_id'=>$localidad_id,'domicilio'=>$domicilio,'email'=>$email,
                                'telefono_caracteristica'=>$telefono_caracteristica,'telefono_numero'=>$telefono_numero]);
    
    $idProf = $objetoProfesor->save(['id'=>$profesor_id,'dni'=>$dni,'apellido'=>$apellido,'nombres'=>$nombres]);

    if ($idPer && $idProf) {
        $array_resultados['codigo'] = 100;
        $array_resultados['mensaje'] = "Los datos del $entidad fueron Actualizados Exitosamente.";
    } else {
        $array_resultados['codigo'] = 12;
        $array_resultados['mensaje'] = "Hubo un Error en la Actualizacion de los datos del $entidad. ";
    } 
    

} else {
        if (!empty($objetoPersona->getPersonaByDni($dni))) {
                $persona_id = $objetoPersona->getPersonaByDni($dni)['id'];
                //die('aca 1');
                $idPer = $objetoPersona->save(['id'=>$persona_id,'dni'=>$dni,'apellido'=>$apellido,'nombres'=>$nombres,'fecha_nacimiento'=>$fecha_nacimiento,
                                               'localidad_id'=>$localidad_id,'domicilio'=>$domicilio,'email'=>$email,
                                               'telefono_caracteristica'=>$telefono_caracteristica,'telefono_numero'=>$telefono_numero]);
        } else {
               //die('aca 2');
                $idPer = $objetoPersona->save(['dni'=>$dni,'apellido'=>$apellido,'nombres'=>$nombres,'fecha_nacimiento'=>$fecha_nacimiento,
                                               'localidad_id'=>$localidad_id,'domicilio'=>$domicilio,'email'=>$email,
                                               'telefono_caracteristica'=>$telefono_caracteristica,'telefono_numero'=>$telefono_numero]);
        };

      //die('aca 3');
      $idProf = $objetoProfesor->save(['dni'=>$dni,'apellido'=>$apellido,'nombres'=>$nombres]);

      $objetoUsuario = new Usuario();
      $password = md5($dni);
      $objetoUsuario->save(['dni'=>$dni,'idTipo'=>2,'password'=>$password]);

      $array_resultados['codigo'] = 100;
      $array_resultados['mensaje'] = "El $entidad <strong>$apellido, $nombres</strong> fueron creado Exitosamente.";
      
      // $array_resultados['codigo'] = 12;
      // $array_resultados['mensaje'] = "Hubo un Error en la creación del $entidad. ";
      
};

echo json_encode($array_resultados);



?>
