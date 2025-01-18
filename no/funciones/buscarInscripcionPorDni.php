<?php
set_include_path("../lib/".PATH_SEPARATOR."../conexion/");
require_once('conexion.php');

$dni = $_POST['dni'];

$respuesta = array();

$hoy = date('Y-m-d'); 
$mes = date('m');
$anio = date('Y');
$anio_lectivo = "";

if ($mes>7) {
    $anio_lectivo = $anio + 1;
} else if ($mes<5) {
    $anio_lectivo = $anio;
};

if ($dni) {
    $sql = "SELECT c.descripcion as 'NombreCarrera', p.nombre as 'Nombre', p.apellido as 'Apellido', 
                    p.dni as 'DNI', p.fechaNacimiento as FechaNacimiento, p.localidad as 'Localidad', 
                    p.nacionalidad as 'Nacionalidad', p.domicilioCalle as 'Domicilio', 
                    p.telefono as 'Celular', p.email as 'Email', ac.fecha_inscripcion as 'FechaInscripcion'
            FROM persona p, alumno a, carrera c, alumno_estudia_carrera ac 
            WHERE p.dni = '$dni' AND 
                  p.dni = a.dni AND 
                  a.id = ac.idAlumno AND 
                  ac.idCarrera = c.id AND 
                  ac.anio = '$anio_lectivo'";

    $res = @mysqli_query($conex,$sql);
    if (!$res) {
        $respuesta['estado'] = '15';
        $respuesta['info'] = "<strong>Error:</strong> No se ha encuentra Inscripcion con DNI <strong>$dni</strong>.";
    } else {
        if (mysqli_num_rows($res)>0) {
            $fila = mysqli_fetch_all($res,MYSQLI_ASSOC);
            $codificacion = base64_encode($dni.'&'.$anio_lectivo);
            $url = 'https://escuela40.net/comprobante.php?r='. $codificacion;
            $respuesta['estado'] = '100';
            $respuesta['info'] = $fila;
            $respuesta['url'] = $url;
        } else {
            $respuesta['estado'] = '16';
            $respuesta['info'] = "<strong>Error:</strong> No se ha encuentra Inscripcion con DNI <strong>$dni</strong>.";
        };
    };              
} else {
    $respuesta['estado'] = '17';
    $respuesta['info'] = "<strong>Error:</strong> DNI Inválido.";
}

echo json_encode($respuesta);

?>