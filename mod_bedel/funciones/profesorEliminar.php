<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

ini_set("default_charset", "UTF-8");
mb_internal_encoding("UTF-8");

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
      db_start_trans($conex);     
      foreach($arreglo_entidades as $idEntidad) {
            $sql = "select dni FROM profesor WHERE id=$idEntidad";
            $res_dni = @mysqli_query($conex,$sql);
            $fila = mysqli_fetch_assoc($res_dni);
                  
            $sql_pertenece_carrera = "DELETE FROM profesor_pertenece_carrera
                                      WHERE idProfesor = $idEntidad";     
            $ok1 = mysqli_query($conex,$sql_pertenece_carrera);
            //die($sql_pertenece_carrera);   
            if(!$ok1){
                  die('profesor_pertenece_carrera');
            }; 

            $sql_rinde_materias = "DELETE FROM profesor_dicta_materia
                                   WHERE idProfesor = $idEntidad";      
            $ok2 = mysqli_query($conex,$sql_rinde_materias);                       
            //die($sql_rinde_materias);                
            if(!$ok2){
                  die('profesor_dicta_materia');
            }; 

            $sql_usuario = "DELETE FROM usuario
                            WHERE dni =".$fila['dni']." and idtipo=2";   
            $ok3 = mysqli_query($conex,$sql_usuario);                                    
            //die($sql_usuario); 
            if(!$ok3){
                  die('usuario');
            }; 

            $sql_alumno = "DELETE FROM profesor
            WHERE id = $idEntidad";
            $ok4 = mysqli_query($conex,$sql_alumno);
            //die($sql_alumno);     
            if(!$ok4){
                  die('alumno');
            }; 

            $sql_persona = "DELETE FROM persona
                    WHERE dni=".$fila['dni']."";
            $ok5 = mysqli_query($conex,$sql_persona);     
           /* if(!$ok5){
                  die('persona');
            };*/ 
            
            //die($sql_persona);
            /** SE INICIA LA TRANSACCION **/
            
            //PRENGUNTAMOS SI HUBO ERROR
            $errorNro =  mysqli_errno($conex);
            if(!$ok4){
                  db_rollback($conex);
                  break;
            }; 

      } // END FOR

      if ($errorNro) {
            if ($cantidad_entidades>1) {
                  $msg = "Hubo un Error en la Eliminación de los Profesores. ";
            } else {
                  $msg = "Hubo un Error en la Eliminaciòn del Profesor. Tiene Registros Vinculados.";
            }
            $array_resultados['codigo'] = 10;
            $array_resultados['mensaje'] = $msg;  
      } else {
            db_commit($conex);
            if ($cantidad_entidades>1) {
                  $msg = "La Eliminación de los Profesores fue exitosa.";
            } else {
                  $msg = "La Eliminación del Profesor fue exitosa.";
            }
            $array_resultados['codigo'] = 100;
            $array_resultados['mensaje'] = $msg;
      };
};

echo json_encode($array_resultados);



?>
