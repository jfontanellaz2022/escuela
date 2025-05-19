<?php
//***************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                     **
//** SACA DATOS DE QUE CURSAN UNA MATERIA EN EL AÑO EN CURSO POR ID MATERIA            **
//** OPCIONAL(sinLibres): SI NO QUEREMOS LOS ALUMNOS QUE HACEN CURSADO LIBRE		   **
//***************************************************************************************
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');

include_once 'seguridadNivel2.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';

$materia = (isset($_POST['materia']) && $_POST['materia']!=NULL)?SanitizeVars::INT($_POST['materia']):false;
$sinLibres = (isset($_POST['sinLibres']) && (in_array($_POST['sinLibres'],[true,false])))?$_POST['sinLibres']:false;
$array_resultados = array();


if ($materia) {
    $anio_actual = date('Y');
    $sql = "SELECT a.*, acm.anioCursado, acm.tipo as cursado, acm.estado_final,
                   acm.FechaHoraInscripcion, acm.nota, acm.FechaModificacionNota, p.email, concat(p.telefono_caracteristica,p.telefono_numero) as telefono
            FROM alumno a, alumno_cursa_materia acm, persona p
            WHERE acm.idMateria = $materia and
                  acm.idAlumno = a.id and
                  acm.anioCursado = $anio_actual and
                  a.dni = p.dni and
                  a.habilitado = 'Si'";
    if ($sinLibres) {
        $sql = "SELECT a.*, acm.anioCursado, acm.tipo as cursado, acm.estado_final,
                       acm.FechaHoraInscripcion, acm.nota, acm.FechaModificacionNota, p.email,  concat(p.telefono_caracteristica,p.telefono_numero) as telefono
                FROM alumno a, alumno_cursa_materia acm, persona p, tipo_cursado_alumno tca
                WHERE acm.idMateria = $materia and
                      acm.idAlumno = a.id and
                      acm.anioCursado = $anio_actual and
                      a.dni = p.dni and
                      a.habilitado = 'Si' and
                      acm.idTipoCursadoAlumno = tca.id and
                      tca.codigo <> '03'";
    };

    //die($sql);
    $sql .= " ORDER BY a.apellido asc, a.nombre asc ";

    $resultado = mysqli_query($conex,$sql);
    if (mysqli_num_rows($resultado)>0) {
       $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
       $array_resultados['codigo'] = 100;
       $array_resultados['data'] = $filas;
    } else {
      $array_resultados['codigo'] = 11;
      $array_resultados['data'] = "No existen Alumnos asociados a la materia.";
    }
} else {
    $array_resultados['codigo'] = 10;
    $array_resultados['data'] = "Faltan Datos Obligatorios.";
}
echo json_encode($array_resultados);

?>
