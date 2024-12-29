<?php
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');

include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
$idCarrera = (isset($_POST['carrera']) && $_POST['carrera']!=NULL)?SanitizeVars::INT($_POST['carrera']):false;
$idProfesor = (isset($_POST['profesor']) && $_POST['profesor']!=NULL)?SanitizeVars::INT($_POST['profesor']):false;
$error_code_mysql = 0;
$array_resultados = array();
if ($idCarrera && $idProfesor) {
				$sql = "INSERT INTO profesor_pertenece_carrera(idProfesor, idCarrera) values "
			       ."($idProfesor, $idCarrera)";
				try {
					mysqli_query($conex,$sql);
				} catch (Exception $e) {
					//echo 'Excepción capturada: ',  $e->getCode().': '.$e->getMessage(), "\n";
					$error_code_mysql = $e->getCode();
				}
				if ($error_code_mysql==0) {
				   $array_resultados['codigo'] = 100;
				   $array_resultados['data'] = "El profesor fue vinculado a la carrera exitosamente.";
				} else {
					if ($error_code_mysql==1062) {
						  $array_resultados['codigo'] = 11;
						  $array_resultados['data'] = "El Profesor con ID <b>".$idProfesor."</b> ya se encuentra vinculado a la Carrera.";
					} else {
						  $array_resultados['codigo'] = 12;
						  $array_resultados['data'] = "Hubo un Error en la vinculacion del Profesor a la Carrera.";
					}
				};
	} else {
		$array_resultados['codigo'] = 13;
		$array_resultados['data'] = "Faltan Datos para realizar la carga. ";
	}
	echo json_encode($array_resultados);

?>
