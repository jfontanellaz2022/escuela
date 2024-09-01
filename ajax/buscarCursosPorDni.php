<?php
set_include_path("../lib/".PATH_SEPARATOR."../conexion/");
require_once('conexion.php');
require_once('configacreditacion.php');
require_once('arrayHash.class.php');

$conex = conectar(DB_HOST,DB_USER,DB_PASS,DB_NAME_ACREDITACION);
$dni = $_POST['dni'];

$respuesta = array();

$hoy = date('Y-m-d'); 
$mes = date('m');
$anio = date('Y');

$hash = ArrayHash::encode(array('documento'=>$dni));

if ($dni) {
    $sql = "SELECT e.id, i.apellido,i.nombres,i.dni, e.nombre, e.resolucion, e.descripcion,e.duracion, e.fecha 
    FROM interesado i, evento e, evento_interesado ei 
    WHERE i.dni = '$dni' and 
          i.id = ei.interesado_id and 
          ei.asistio = 'Si' and 
          ei.evento_id = e.id and 
          e.concluido = 'Si';";
    //die($sql);
    $res = @mysqli_query($conex,$sql);
    if (!$res) {
        $respuesta['estado'] = '15';
        $respuesta['info'] = "<strong>Error:</strong> Hubo un error y no se ha encuentra cursos realizados con DNI <strong>$dni</strong>.";
    } else {
        if (mysqli_num_rows($res)>0) {
            $fila = mysqli_fetch_all($res,MYSQLI_ASSOC);
            $codificacion = base64_encode($dni);
            $respuesta['estado'] = '100';
            $respuesta['info'] = 'ok';
            $respuesta['datos'] = $fila;
            $respuesta['hash'] = $hash;

        } else {
            $respuesta['estado'] = '16';
            $respuesta['info'] = "<strong>Error:</strong> No se ha encuentra Cursos realizados con DNI <strong>$dni</strong>.";
        };
    };              
} else {
    $respuesta['estado'] = '17';
    $respuesta['info'] = "<strong>Error:</strong> DNI Inválido.";
}

echo json_encode($respuesta);

?>