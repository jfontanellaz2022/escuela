<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');

require_once "verificarCredenciales.php";
require_once "AlumnoCursaMateria.php";
require_once "Tipificacion.php";

$acm = new AlumnoCursaMateria();

$argumentos = [];


$alumno_id = $_SESSION['idAlumno'];
$anio_cursado = date('Y');

//var_dump($_SESSION['arr_materias_inscriptas_cursar_actualizadas']);die;

/*$_SESSION['arr_materias_inscriptas_cursar_actualizadas'] 

array(1) {
    [0]=>
    array(2) {
      [0]=>
      string(3) "401"
      [1]=>
      string(14) "Semipresencial"
    }
  } */
  
  
$objTipificacion = new Tipificacion();
foreach ($_SESSION['arr_materias_inscriptas_cursar_actualizadas'] as $value) {
   $materia_id = $value[0];
   $valor = $value[1];
   
   
   $cursado_id = $objTipificacion->getTipificacionByCodigo($valor)['id'];
   $cursado_nombre = $objTipificacion->getTipificacionByCodigo($valor)['nombre'];
   
   $estado_id = $objTipificacion->getTipificacionByCodigo(Constantes::CODIGO_ESTADO_CURSANDO)['id']; 
   $estado_nombre = $objTipificacion->getTipificacionByCodigo(Constantes::CODIGO_ESTADO_CURSANDO)['nombre']; 

   $argumentos = [];
   $argumentos['alumno_id'] = $alumno_id;
   $argumentos['materia_id'] = $materia_id;
   $argumentos['cursado_id'] = $cursado_id;
   $argumentos['estado_id'] = $estado_id;
   $argumentos['usuario_id'] = $alumno_id;
   $argumentos['anio_cursado'] = $anio_cursado;
   $argumentos['fecha_inscripcion'] = date('Y-m-d');
   $argumentos['nota'] = 0; //Se pone 0 porque es una inscripcion
   $argumentos['cursado_nombre'] = $cursado_nombre;
   $argumentos['estado_nombre'] = $estado_nombre; //Se pone Cursando porque es una inscripcion
   $argumentos['fecha_modificacion_nota'] = date('Y-m-d');; //Se pone 0 porque es una inscripcion
   $argumentos['fecha_vencimiento_regularidad'] = NULL; //Se pone 0 porque es una inscripcion
   
   

   
   //$argumentos['tipo'] = $valor; 
   
//$arr = ['alumno_id'=>646,'materia_id'=>401,'anio_cursado'=>2024,'tipo'=>'Presencial','fecha_hora_inscripcion'=>'2023-12-12',
//         'nota'=>0,'estado_final'=>'Cursando','fecha_modificacion_nota'=>'2023-12-12','fecha_vencimiento_regularidad'=>'2023-12-12','usuario_id'=>7];

   
   //var_dump($argumentos);die;

   if ($valor=='No') {
       $acm->deleteAlumnoCursaMateriaByIdAlumnoByIdMateriaByAnio($alumno_id,$materia_id,$anio_cursado);
   } else {
       $acm->deleteAlumnoCursaMateriaByIdAlumnoByIdMateriaByAnio($alumno_id,$materia_id,$anio_cursado);
       
       $acm->save($argumentos);
   }
   
}

$arr_resultado = [];
$respuesta['estado'] = 0;
$respuesta['mensaje'] = "nada";

echo json_encode($respuesta);
?>
