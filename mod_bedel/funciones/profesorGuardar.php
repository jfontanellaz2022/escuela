<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once "verificarCredenciales.php";
require_once 'SanitizeCustom.class.php';
require_once "Persona.php";
require_once "Profesor.php";
require_once "Usuario.php";

$entidad = "Profesor";

$profesor_id = (isset($_POST['id']) && $_POST['id']!=NULL)?SanitizeCustom::INT($_POST['id']):false;
$apellido = (isset($_POST['apellido']) && $_POST['apellido']!=NULL)?SanitizeCustom::APELLIDO_NOMBRES($_POST['apellido']):false;
$nombres = (isset($_POST['nombres']) && $_POST['nombres']!=NULL)?SanitizeCustom::APELLIDO_NOMBRES($_POST['nombres']):false;
$dni = (isset($_POST['dni']) && $_POST['dni']!=NULL)?SanitizeCustom::DOCUMENTO_CUIL($_POST['dni'],8,8):false;
$domicilio = (isset($_POST['domicilio']) && $_POST['domicilio']!=NULL)?SanitizeCustom::DOMICILIO($_POST['domicilio'],2,40):false;
$telefono_caracteristica = (isset($_POST['telefono_caracteristica']) && $_POST['telefono_caracteristica']!=NULL)?SanitizeCustom::INT($_POST['telefono_caracteristica']):false;
$telefono_numero = (isset($_POST['telefono_numero']) && $_POST['telefono_numero']!=NULL)?SanitizeCustom::INT($_POST['telefono_numero']):false;
$email = (isset($_POST['email']) && $_POST['email']!=NULL)?SanitizeCustom::EMAIL($_POST['email']):false;
$localidad_id = (isset($_POST['localidad_id']) && $_POST['localidad_id']!=NULL)?SanitizeCustom::INT($_POST['localidad_id']):false;
$fecha_nacimiento = (isset($_POST['fecha_nacimiento']) && $_POST['fecha_nacimiento']!=NULL)?SanitizeCustom::DATE($_POST['fecha_nacimiento']):false;

$persona_id = 0;
$array_resultados = array();
$objetoPersona = new Persona;
$objetoProfesor = new Profesor;
$objetoUsuario = new Usuario;

if ($profesor_id) /* UPDATE */ {
    $persona_id = $objetoProfesor->getById($profesor_id)['idPersona'];
    //var_dump($persona_id);exit;
    if ($persona_id) {
        //*** PERSONA: ACTUALIZA DATOS ****************

        $param['idPersona'] = $persona_id;
        $param['apellido'] = $apellido;
        $param['nombres'] = $nombres;
        $param['dni'] = $dni;
        $param['localidad_id'] = $localidad_id;
        if ($fecha_nacimiento!="" && $fecha_nacimiento!=null) {$param['fecha_nacimiento'] = $fecha_nacimiento;}
        if ($domicilio!="" && $domicilio!=null) {$param['domicilio'] = $domicilio;}
        if ($telefono_caracteristica!="" && $telefono_caracteristica!=null) {$param['telefono_caracteristica'] = $telefono_caracteristica;}
        if ($telefono_numero!="" && $telefono_numero!=null) {$param['telefono_numero'] = $telefono_numero;}
        $param['email'] = $email;
        
        $res = $objetoPersona->save($param);
        if (!$res) {
            $array_resultados['codigo'] = 500;
            $array_resultados['alert'] = 'danger';
            $array_resultados['mensaje'] = "Hubo un Error en la Actualizacion de los datos del Profesor (Persona 1).";  
            echo json_encode($array_resultados);exit;
        }
        //******************************************** */

        //*** PROFESOR: ACTUALIZA DATOS ****************
        $param_profesor = ['id'=>$profesor_id,'idPersona'=>$persona_id];
        $res = $objetoProfesor->save($param_profesor);
        if (!$res) {
            $array_resultados['codigo'] = 500;
            $array_resultados['alert'] = 'danger';
            $array_resultados['mensaje'] = "Hubo un Error en la Actualizacion de los datos del Profesor (Profesor).";  
            echo json_encode($array_resultados);exit;
        }
        //******************************************** */

    } else {
        $array_resultados['codigo'] = 500;
        $array_resultados['alert'] = 'danger';
        $array_resultados['mensaje'] = "Hubo un Error en la Actualizacion de los datos del Profesor (Persona 2).";  
        echo json_encode($array_resultados);exit;
    }

    $array_resultados['codigo'] = 200;
    $array_resultados['alert'] = 'success';
    $array_resultados['mensaje'] = "El Profesor <strong>$apellido, $nombres</strong> fueron actualizados Exitosamente.";

} else /* INSERT*/ {
    //*** PERSONA: INSERT DATOS ****************
    $param = ['dni'=>$dni,'apellido'=>$apellido,'nombres'=>$nombres,'fecha_nacimiento'=>$fecha_nacimiento,
                  'localidad_id'=>$localidad_id,'domicilio'=>$domicilio,'email'=>$email,
                  'telefono_caracteristica'=>$telefono_caracteristica,'telefono_numero'=>$telefono_numero];
    $persona_id = $objetoPersona->save($param);
    if (!$persona_id) {
        $array_resultados['codigo'] = 500;
        $array_resultados['alert'] = 'danger';
        $array_resultados['mensaje'] = "Hubo un Error en la Creacaión de los datos del Profesor (Persona 1).";  
        echo json_encode($array_resultados);exit;
    }
    //******************************************** 

    //*** PROFESOR: ACTUALIZA DATOS ****************
    $param_profesor = ['idPersona'=>$persona_id];
    $res = $objetoProfesor->save($param_profesor);
    if (!$res) {
        $array_resultados['codigo'] = 500;
        $array_resultados['alert'] = 'danger';
        $array_resultados['mensaje'] = "Hubo un Error en la Actualizacion de los datos del Profesor (Profesor).";  
        echo json_encode($array_resultados);exit;
    }
    //******************************************** 


    //*** USUARIO: ACTUALIZA DATOS ****************
    $param_usuario['nombre'] = $dni;
    $param_usuario['password'] = $dni;
    $param_usuario['idRol'] = 3;
    $param_usuario['idPersona'] = $persona_id;
    $res = $objetoUsuario->save($param_usuario);
    if (!$res) {
        $array_resultados['codigo'] = 500;
        $array_resultados['alert'] = 'danger';
        $array_resultados['mensaje'] = "Hubo un Error en la Actualizacion de los datos del Profesor (Usuario).";  
        echo json_encode($array_resultados);exit;
    }

    $array_resultados['codigo'] = 200;
    $array_resultados['alert'] = 'success';
    $array_resultados['mensaje'] = "El Profesor se ha creado!.";  
};

echo json_encode($array_resultados);



?>
