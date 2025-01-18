<?php
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');

//include_once 'seguridadNivel2.php'; ESTO LO USA EL ALUMNO TAMBIEN
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$codigo = (isset($_POST['codigo']) && $_POST['codigo']!="")?$_POST['codigo']:false;
$array_resultados = array();

function sacaUltimaInscripcionExamenAnioVigente($conex,$anio_vigente){
      $sql = "SELECT max(c.id) as id_inscripcion
              FROM evento e, calendarioacademico c
              WHERE e.id = c.idEvento and
                    (e.codigo = '1005' or e.codigo = '1006' or e.codigo = '1007' or e.codigo = '1008') and
                    c.AnioLectivo = '$anio_vigente'
              ORDER BY  c.fechaInicioEvento DESC
              ";
      //die($sql);        
      $resultado = mysqli_query($conex,$sql);
      $fila = mysqli_fetch_assoc($resultado);
      return $fila['id_inscripcion'];
}

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
            $id_ultima_inscripcion = sacaUltimaInscripcionExamenAnioVigente($conex,$anio_vigente);

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
               $array_resultados['id_ultima_inscripcion'] = $id_ultima_inscripcion;
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
