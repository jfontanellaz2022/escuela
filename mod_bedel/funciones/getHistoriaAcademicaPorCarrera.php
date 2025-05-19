<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../');
require_once "verificarCredenciales.php";
require_once "Carrera.php";
require_once "AlumnoRindeMateria.php";
require_once "AlumnoCursaMateria.php";

$idCarrera = $_POST['carrera_id'];
$idAlumno = $_POST['alumno_id'];

$carrera = new Carrera();
$arr_materias_carrera = $carrera->getMateriasPorIdCarrera($idCarrera);
$cantidad_anio_carrera = $carrera->getCantidadAniosCarrera($idCarrera);

$alumno_rinde_materia = new AlumnoRindeMateria();
$arr_aprobadas = $alumno_rinde_materia->getMateriasRendidasByEstadoDetalle($idAlumno,'Aprobo');

$alumno_cursa_materia = new AlumnoCursaMateria();
$arr_regulares_libre = array_merge($alumno_cursa_materia->getMateriasCursadasByEstadoConDetalles($idAlumno,'Cursando',false),$alumno_cursa_materia->getMateriasCursadasByEstadoConDetalles($idAlumno,'Regularizo'),$alumno_cursa_materia->getMateriasCursadasByEstadoConDetalles($idAlumno,'Libre'),$alumno_cursa_materia->getMateriasCursadasByEstadoConDetalles($idAlumno,'Promociono',FALSE),$alumno_cursa_materia->getMateriasCursadasByEstadoConDetalles($idAlumno,'Aprobo',FALSE));

//var_dump($alumno_cursa_materia->getMateriasCursadasByEstadoConDetalles($idAlumno,'Cursando',false));die;

function getDetallesCursado($materia_id,$arr) {
    $arr_resultado = [];
    //var_dump($arr);die;
    foreach($arr as $value) {
        if ($value['idMateria']==$materia_id) {
            $arr_resultado = $value;
        };
    }
    return $arr_resultado;
}

function getDetallesAprobada($materia_id,$arr) {
    $arr_resultado = [];
    //var_dump($arr);die;
    foreach($arr as $value) {
        if ($value['idMateria']==$materia_id) {
            $arr_resultado = $value;
        };
    }
    return $arr_resultado;
}

echo "<div class='row'><a class='btn btn-primary' target='_blank' href='./funciones/PDF_ActaCursado.php?idCarrera=".$idCarrera."&idAlumno=".$idAlumno."'><img src='../public/img/icons/pdf_icon.png' width='22'>&nbsp;Descargar</a></div><div class='row'>&nbsp;</div>";
echo "<table class='table table-stripped'>";
for ($i=1;$i<=$cantidad_anio_carrera;$i++) {
   
    echo "<tr><th colspan=7 style='text-align: center;background-color: #D8F367;'>A&ntilde;o ".$i."</th></tr>";
    echo "<tr><th style='text-align: center;'></th>"
       . "<th style='text-align: center;' colspan='4'>CURSADO</th>"
       . "<th style='text-align: center;' colspan='2'>EXAMEN FINAL</th></tr>";
    echo "<tr><th style='text-align: center;'>Asignatura</th>"
       . "<th style='text-align: center;'>Cursado</th>"
       . "<th style='text-align: center;'>Nota</th>"
       . "<th style='text-align: center;'>Estado</th>"
       . "<th style='text-align: center;'>F.Vencimiento</th>"
       . "<th style='text-align: center;'>Nota Final</th>"
       . "<th style='text-align: center;'>Estado Final</th></tr>";
    foreach ($arr_materias_carrera as $value) {
        $materia_id = 0;
        $materia_nombre = "";
        $materia_anio = 0;
        $materia_nota_regularidad = 0;
        $materia_nota_final = 0;
        $materia_estado = "";
        $materia_estado_final = "";
        $materia_cursado = "";
        $materia_fecha_vencimiento = "";

        $materia_id = $value['materia_id'];
        $materia_nombre = $value['nombre'];
        $materia_anio = $value['anio'];
        $materia_detalles = getDetallesCursado($materia_id,$arr_regulares_libre);
        //var_dump($materia_detalles);exit;
        $materia_aprobadas_detalles = getDetallesAprobada($materia_id,$arr_aprobadas);
        if (!empty($materia_detalles)) {
            $materia_nota_regularidad = $materia_detalles['nota'];
            $materia_estado = $materia_detalles['estado_final'];
            $materia_fecha_vencimiento = $materia_detalles['fecha_vencimiento_regularidad'];
            $materia_cursado = $materia_detalles['cursado'];
        } else {
            $materia_nota_regularidad = "";
        }
        if (!empty($materia_aprobadas_detalles)) {
            $materia_nota_final = $materia_aprobadas_detalles['nota'];
            $materia_estado_final = "Aprobo";
        } else {
            $materia_nota_final = "";
        }
        
        if ($materia_anio==$i ) {
            echo "<tr><td><small>".$materia_nombre.' <strong>('.$materia_id.")</strong></small></td>".
                     "<td><small>".$materia_cursado."</small></td>".
                     "<td><small>".$materia_nota_regularidad."</small></td>".
                     "<td><small>".$materia_estado."</small></td>".
                     "<td><small>".$materia_fecha_vencimiento."</small></td>".
                     "<td><small><strong>".$materia_nota_final."</strong></small></td>".
                     "<td></small><span class='badge badge-success'>".$materia_estado_final."</span><small></td>".
                 "</tr>";
        };
    }
    
   
}
echo "</table>";
//var_dump($arr_materias_carrera);







?>
