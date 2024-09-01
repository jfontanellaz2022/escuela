<?php
//require_once "_seguridad.php";

function getMateriaPorId($idMateria,$conex) {
    $array_resultados = array();

    if ($idMateria) {
        $sql = "SELECT id, nombre
                FROM materia
                WHERE id = $idMateria";
                die($sql);
        $resultado = mysqli_query($conex,$sql);
        if (mysqli_num_rows($resultado)>0) {
          $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
          $array_resultados['codigo'] = 100;
          $array_resultados['data'] = $filas;
        } else {
          $array_resultados['codigo'] = 11;
          $array_resultados['data'] = "No existe la Materia.";
        }
    } else {
        $array_resultados['codigo'] = 10;
        $array_resultados['data'] = "Faltan Datos Obligatorios.";
    }
    return $array_resultados;
}

?>
