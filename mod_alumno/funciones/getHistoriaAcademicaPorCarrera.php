<?php
session_start();
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/');

require_once "Carrera.php";
require_once "AlumnoRindeMateria.php";
require_once "AlumnoCursaMateria.php";

$idCarrera = $_POST['idCarrera'];
$idAlumno = $_SESSION['idAlumno'];

$carrera = new Carrera();
$arr_materias_carrera = $carrera->getMateriasPorIdCarrera($idCarrera);
$cantidad_anio_carrera = $carrera->getCantidadAniosCarrera($idCarrera);

$alumno_rinde_materia = new AlumnoRindeMateria();
$arr_aprobadas = $alumno_rinde_materia->getMateriasRendidasByEstadoDetalle($idAlumno,'Aprobo');

$alumno_cursa_materia = new AlumnoCursaMateria();
$arr_regulares_libre = array_merge($alumno_cursa_materia->getMateriasCursadasByEstadoConDetalles($idAlumno,'Regularizo'),$alumno_cursa_materia->getMateriasCursadasByEstadoConDetalles($idAlumno,'Libre'),$alumno_cursa_materia->getMateriasCursadasByEstadoConDetalles($idAlumno,'Promociono',FALSE),$alumno_cursa_materia->getMateriasCursadasByEstadoConDetalles($idAlumno,'Aprobo',FALSE));

//var_dump($alumno_cursa_materia->getMateriasCursadasByEstadoConDetalles($idAlumno,'Promociono',FALSE));die;

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


echo "<div class='table-responsive'><table class='table table-bordered'>";
for ($i=1;$i<=$cantidad_anio_carrera;$i++) {
    echo "<thead>
            <tr>
            <th colspan='6' style='text-align: center;background-color: #D8F367;'>A&Ntilde;O</th>
            </tr>
            <tr>
                <th style='text-align: center;' colspan='4'>CURSADO</th>
                <th style='text-align: center;' colspan='2'>EXAMEN FINAL</th></tr>
            <tr>  
                <th>Asignatura</th>
                <th>Nota</th>
                <th>Estado</th>
                <th>F.Vencimiento</th>
                <th>Nota Final</th>
                <th>Estado Final</th>
            </tr>    
        </thead>";
    foreach ($arr_materias_carrera as $value) {
        $materia_id = 0;
        $materia_nombre = "";
        $materia_anio = 0;
        $materia_nota_regularidad = 0;
        $materia_nota_final = 0;
        $materia_estado = "";
        $materia_estado_final = "";
        $materia_fecha_vencimiento = "";

        $materia_id = $value['materia_id'];
        $materia_nombre = $value['nombre'];
        $materia_anio = $value['anio'];
        $materia_detalles = getDetallesCursado($materia_id,$arr_regulares_libre);
        $materia_aprobadas_detalles = getDetallesAprobada($materia_id,$arr_aprobadas);
        if (!empty($materia_detalles)) {
            $materia_nota_regularidad = $materia_detalles['nota'];
            $materia_estado = $materia_detalles['estado_final'];
            $materia_fecha_vencimiento = $materia_detalles['fecha_vencimiento_regularidad'];
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
            echo "<tr><td>".$materia_nombre.' <strong>('.$materia_id.")</strong></td><td>".$materia_nota_regularidad."</td><td>".$materia_estado."</td><td>".$materia_fecha_vencimiento."</td><td><strong>".$materia_nota_final."</strong></td><td><span class='badge badge-success'>".$materia_estado_final."</span></td></tr>";
        };
    }
    
   
}
echo "</table></div>";



?>
