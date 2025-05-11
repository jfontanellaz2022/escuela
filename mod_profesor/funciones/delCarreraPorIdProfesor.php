<?php
	set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');

	include_once 'seguridadNivel2.php';
	include_once 'conexion.php';
	include_once 'Sanitize.class.php';
	
	$idCarrera = (isset($_POST['carrera']) && $_POST['carrera']!=NULL)?SanitizeVars::INT($_POST['carrera']):false;
	$idProfesor = (isset($_POST['profesor']) && $_POST['profesor']!=NULL)?SanitizeVars::INT($_POST['profesor']):false;
	
	$anioActual=date('Y');
	
	$array_resultados = array();
	if ($idProfesor && $idCarrera) {
		$sql_materias = "SELECT pdm.idMateria, ctm.idCarrera
						 FROM profesor_dicta_materia pdm, carrera_tiene_materia ctm
						 WHERE pdm.idMateria = ctm.idMateria and 
							   pdm.idProfesor = $idProfesor and 
							   ctm.idCarrera = $idCarrera";
		$resultado_materias = mysqli_query($conex,$sql_materias);
		if (mysqli_num_rows($resultado_materias)>0) {
			$array_resultados['codigo'] = 11;
			$array_resultados['data'] = "Tiene Materias vinculadas a la Carrera. Debe desvincularse de ellas primero.";
		} else {
			$sql = "DELETE FROM profesor_pertenece_carrera 
		            WHERE idProfesor = $idProfesor and 
				          idCarrera = $idCarrera";
			$resultado = mysqli_query($conex,$sql);
			if ($resultado) {
				$array_resultados['codigo'] = 100;
			    $array_resultados['data'] = "La Carrera fue desvinculada del profesor exitosamente.";
		   } else {
			    $array_resultados['codigo'] = 10;
			    $array_resultados['data'] = "Hubo un Error en la desvinculación de la Carrera.";
		   };
		}					   
	} else {
			$array_resultados['codigo'] = 12;
			$array_resultados['data'] = "Faltan Datos para realizar la desvinculación. ";
	}
	echo json_encode($array_resultados);
	
?>
