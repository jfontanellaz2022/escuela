<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

//include_once 'seguridadNivel2.php';
require_once 'conexion.php';
require_once 'Sanitize.class.php';
require_once "_seguridad.php";

ini_set("default_charset", "UTF-8");

/**********************************************************************************************************************************************************************/
/**************************************************************** RECIBIR PARAMETROS Y SANITIZARLOS *******************************************************************/
/**********************************************************************************************************************************************************************/

$entidades_a_eliminar = ( isset($_POST['id']) && $_POST['id']!="" )?$_POST['id']:false;
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/
/**********************************************************************************************************************************************************************/

$array_resultados = array();

if ($entidades_a_eliminar) {
      //die('entrooo');
      $arreglo_entidades = explode(',',$entidades_a_eliminar);
      $cantidad_entidades = count($arreglo_entidades);
      $errorNro = 0;  
      $msg = "";
      //db_start_trans($conex);     
      foreach($arreglo_entidades as $idEntidad) {
            $sql = "DELETE FROM calendarioacademico
                                      WHERE id = $idEntidad";     
            $ok = mysqli_query($conex,$sql);
            //die($sql_pertenece_carrera);   
            if(!$ok){
                  die('calendario');
            }; 
            /** SE INICIA LA TRANSACCION **/
            
            //PRENGUNTAMOS SI HUBO ERROR
            $errorNro =  mysqli_errno($conex);
            if(!$ok){
                  //db_rollback($conex);
                  break;
            }; 

      } // END FOR

      if ($errorNro) {
            if ($cantidad_entidades>1) {
                  $msg = "Hubo un Error en la Eliminación de los Registros. ";
            } else {
                  $msg = "Hubo un Error en la Eliminaciòn del Registro. Tiene Registros Vinculados.";
            }
            $array_resultados['codigo'] = 10;
            $array_resultados['mensaje'] = $msg;  
      } else {
            //db_commit($conex);
            if ($cantidad_entidades>1) {
                  $msg = "La Eliminación de los Registros fue exitosa.";
            } else {
                  $msg = "La Eliminación del Registro fue exitosa.";
            }
            $array_resultados['codigo'] = 100;
            $array_resultados['mensaje'] = $msg;
      };
};

echo json_encode($array_resultados);



?>
