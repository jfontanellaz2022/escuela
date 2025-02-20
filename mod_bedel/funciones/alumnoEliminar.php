<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');
require_once "verificarCredenciales.php";
require_once "conexion.php";
require_once "Sanitize.class.php";

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
            $sql = "select dni FROM alumno WHERE id=$idEntidad";
            $res_dni = @mysqli_query($conex,$sql);
            $fila = mysqli_fetch_assoc($res_dni);
                  
            $sql_pertenece_carrera = "DELETE FROM alumno_estudia_carrera
            WHERE idAlumno = $idEntidad";     
            $ok1 = mysqli_query($conex,$sql_pertenece_carrera);
            //die($sql_pertenece_carrera);   
            if(!$ok1){
                  die('alumno_estudia_carrera');
            }; 

            $sql_rinde_materias = "DELETE FROM alumno_rinde_materia
                                   WHERE idAlumno = $idEntidad";      
            $ok2 = mysqli_query($conex,$sql_rinde_materias);                       
            //die($sql_rinde_materias);                
            if(!$ok2){
                  die('alumno_rinde_materia');
            }; 

            $sql_cursa_materias = "DELETE FROM alumno_cursa_materia
                                   WHERE idAlumno = $idEntidad";
            $ok3 = mysqli_query($conex,$sql_cursa_materias);                      
            //die($sql_cursa_materias);    
            if(!$ok3){
                  die('alumno_cursa_materia');
            }; 

            $sql_usuario = "DELETE FROM usuario
                            WHERE dni =".$fila['dni']." and idtipo=1";   
            $ok4 = mysqli_query($conex,$sql_usuario);                                    
            //die($sql_usuario); 
            if(!$ok4){
                  die('usuario');
            }; 

            $sql_alumno = "DELETE FROM alumno
            WHERE id = $idEntidad";
            $ok5 = mysqli_query($conex,$sql_alumno);
            //die($sql_alumno);     
            if(!$ok5){
                  die('alumno');
            }; 

            $errorNro =  mysqli_errno($conex);


            $sql_persona = "DELETE FROM persona
                    WHERE dni=".$fila['dni']."";
            $ok6 = mysqli_query($conex,$sql_persona);     

            /*if(!$ok6){
                  die('persona');
            };*/ 
            
            //die($sql_persona);
            /** SE INICIA LA TRANSACCION **/
            
            
            //PRENGUNTAMOS SI HUBO ERROR
            
            if(!$ok5){
                  db_rollback($conex);
                  break;
            }; 

      } // END FOR

      if ($errorNro) {
            if ($cantidad_entidades>1) {
                  $msg = "Hubo un Error en la Eliminación de los Alumnos. ";
            } else {
                  $msg = "Hubo un Error en la Eliminaciòn del Alumno. Tiene Registros Vinculados.";
            }
            $array_resultados['codigo'] = 10;
            $array_resultados['mensaje'] = $msg;  
      } else {
            db_commit($conex);
            if ($cantidad_entidades>1) {
                  $msg = "La Eliminación de los Alumnos fue exitosa.";
            } else {
                  $msg = "La Eliminación del Alumno fue exitosa.";
            }
            $array_resultados['codigo'] = 100;
            $array_resultados['mensaje'] = $msg;
      };
};

echo json_encode($array_resultados);



?>
