<?php
//***************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                     **
//** SACA TODOS LOS DATOS DE LA CARRERA POR ID DE CARRERA                              **
//***************************************************************************************
set_include_path('../../conexion'.PATH_SEPARATOR.'../../app/lib');
include_once 'seguridadNivel2.php';
include_once 'conexion.php';

$array_resultados = array();

$sql = "SELECT c.*
        FROM carrera c
        WHERE habilitada = 'Si'
        ORDER BY descripcion";
$resultado = mysqli_query($conex,$sql);

if (mysqli_num_rows($resultado)>0) {
        $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
        $array_resultados['codigo'] = 100;
        $array_resultados['data'] = $filas;
} else {
        $array_resultados['codigo'] = 11;
        $array_resultados['data'] = "No existe Carrera.";
};

echo json_encode($array_resultados);

?>
