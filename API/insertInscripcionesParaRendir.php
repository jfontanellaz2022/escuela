<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel1.php";
require_once "AlumnoRindeMateria.php";
require_once "InscripcionRendirMaterias.php";

//*******************TOKEN  *****************************/
$token = (isset($_GET['token']))?$_GET['token']:false;
$array_resultados = [];
if ($token!=$_SESSION['token']) {
  $array_resultados['codigo'] = 500;
  $array_resultados['class'] = 'danger';
  $array_resultados['mensaje'] = 'El Token es INCORRECTO.';
  echo json_encode($array_resultados);die;
}
//****************************************************** */

function getCursado($idMateria,$arr) {
    $val_retorno = "";
    //var_dump($arr);die;
    foreach ($arr as $value) {
         if ($value['idMateria']==$idMateria) {
            $val_retorno = ($value['cursado']=='Libre')?'Libre':'Regular';
            return $val_retorno;
         };
    }
    return "Sin Estado";
}

$arm = new AlumnoRindeMateria();
$argumentos = [];

$alumno_id = $_SESSION['idAlumno'];
$inscripcion_activa = $_SESSION['arr_calendario']['inscripcion_activa'];
$calendario_id = $_SESSION['arr_calendario']['inscripcion_asociada'];
$cantidad_llamados = $_SESSION['arr_calendario']['cantidad_llamados'];
$carrera_id = $_POST['carrera_id'];

$irm = new InscripcionRendirMaterias();
$arr_en_condiciones_de_rendir = $irm->getArregloMateriasVerificadasParaInscribirseDetalles($alumno_id,$carrera_id);
$arr_materias_por_tipo_cursado = $irm->getMateriasCandidatasPorTipoCursado();
//var_dump($arr_materias_por_tipo_cursado);die;
//var_dump($_SESSION['arr_materias_inscriptas_actualizadas']);exit;

foreach ($_SESSION['arr_materias_inscriptas_actualizadas'] as $value) {
        $materia_id = $value[0];
        $inscribe = $value[1];

        $argumentos = [];
        $argumentos['alumno_id'] = $alumno_id;
        $argumentos['materia_id'] = $materia_id;
        $argumentos['calendario_id'] = $calendario_id;
        $argumentos['condicion'] = $value[1];
        $argumentos['fecha_hora_inscripcion'] = date('Y-m-d H:i:s');
        $argumentos['nota'] = 0; //Se pone 0 porque es una inscripcion
        $argumentos['estado_final'] = 'Pendiente'; //Se pone 0 porque es una inscripcion
        $argumentos['usuario_id'] = $alumno_id;

        //var_dump($argumentos);die;

        if ($inscribe=='No') {
            $arm->deleteAlumnoRindeMateriaByIdAlumnoByIdMateriaByIdCalendario($alumno_id,$materia_id,$calendario_id);
        } else {
            if ($inscripcion_activa!=$calendario_id) { // Es una Inscripcion Intermedia
                    $argumentos['llamado'] = 2;
                    $arm->save($argumentos);
            } else {
                    if ($cantidad_llamados==1) {
                        $argumentos['llamado'] = 1;
                        $arm->save($argumentos);
                    } else if ($cantidad_llamados==2) {
                        $argumentos['llamado'] = 1;
                        $arm->save($argumentos);
                        $argumentos['llamado'] = 2;
                        $arm->save($argumentos);
                    }
            }
            
        }

}

$arr_resultado = [];
$respuesta['estado'] = 0;
$respuesta['mensaje'] = "nada";

echo json_encode($respuesta);
?>
