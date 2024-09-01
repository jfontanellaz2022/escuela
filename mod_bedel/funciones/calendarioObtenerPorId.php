<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'Sanitize.class.php';
require_once "_seguridad.php";

$id = ( isset($_POST['id']) )?SanitizeVars::INT($_POST['id']):false;

$array_resultados = array();
if ($id) {
    $sql = "SELECT c.id, c.AnioLectivo, c.fechaInicioEvento, c.fechaFinalEvento, c.idEvento, c.idPeriodoCuatrimestreActivo, e.descripcion, e.codigo
            FROM calendarioacademico c, evento e
            WHERE (c.idEvento = e.id) and c.id=$id"; 
    //die($sql);        
    $resultado = mysqli_query($conex,$sql);
    if (mysqli_num_rows($resultado)>0) {
      $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
      $array_resultados['codigo'] = 100;
      $array_resultados['datos'] = $filas;
    } else {
      $array_resultados['codigo'] = 11;
      $array_resultados['datos'] = "No existe Registro de Calendario.";
    }
} else {
  $array_resultados['codigo'] = 10;
  $array_resultados['datos'] = "El ID de Calendario es Incorrecto.";
}

echo json_encode($array_resultados);

?>
