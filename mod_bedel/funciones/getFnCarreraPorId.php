<?php
//require_once "_seguridad.php";

function getCarreraPorId($idCarrera,$conex) {
    $array_resultados = array();

    if ($idCarrera) {
        $sql = "SELECT id, codigo, descripcion, descripcion_corta, habilitada, imagen
                FROM carrera 
                WHERE id = $idCarrera";
        $resultado = mysqli_query($conex,$sql);
        if (mysqli_num_rows($resultado)>0) {
          $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
          $array_resultados['codigo'] = 100;
          $array_resultados['data'] = $filas;
        } else {
          $array_resultados['codigo'] = 11;
          $array_resultados['data'] = "No existe la Carrera.";
        }
    } else {
        $array_resultados['codigo'] = 10;
        $array_resultados['data'] = "Faltan Datos Obligatorios.";
    }
    return $array_resultados;
}

?>
