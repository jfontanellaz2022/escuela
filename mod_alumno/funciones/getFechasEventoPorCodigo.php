<?php
set_include_path('../../conexion'.PATH_SEPARATOR.'../../lib');
//include_once 'seguridadNivel2.php'; ESTO LO USA EL ALUMNO TAMBIEN
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$codigo = (isset($_POST['codigo']) && $_POST['codigo']!="")?$_POST['codigo']:false;
$array_resultados = array();

if ($codigo) {
            $anio_vigente = date('Y');
            $sql = "SELECT c.id, c.fechaInicioEvento, c.fechaFinalEvento
                    FROM evento e, calendarioacademico c
                    WHERE e.codigo = '$codigo' and
                          e.id = c.idEvento and
                          c.AnioLectivo = '$anio_vigente'
                    ORDER BY  c.fechaInicioEvento DESC
                    ";
            $resultado = mysqli_query($conex,$sql);
            if ($resultado && mysqli_num_rows($resultado)>0) {
               $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
               $array_resultados['codigo'] = 100;
               $fechaInicio = strtotime($filas[0]['fechaInicioEvento']);
               $fechaFinal = strtotime($filas[0]['fechaFinalEvento']);
               $hoy = strtotime(date('Y-m-d'));
               $esta = 'No';
               if (($hoy >= $fechaInicio) && ($hoy <= $fechaFinal)){
                    $esta = 'Si';
               } else {
                    $esta = 'No';
                }
               $array_resultados['data'] = $filas;
               $array_resultados['habilitado'] = $esta;
            } else {
              $array_resultados['codigo'] = 11;
              $array_resultados['data'] = "No esta Activado el Evento.";
            }
} else {
      $array_resultados['codigo'] = 12;
      $array_resultados['data'] = "Los datos no son correctos.";
}

echo json_encode($array_resultados);

?>
