<?php

//require_once('./controlAcceso.php');

function generaArregloCarrerasAlumno($conex, $idAlumno) {
           $sqlAlumnoCarrera="select b.id, b.descripcion 
                              from alumno_estudia_carrera a, carrera b
                              where a.idAlumno={$_SESSION['idAlumno']} and 
                                    a.idCarrera=b.id";
           //echo $sqlAlumnoCarrera;
           $resultadoSqlAlumnoCarrera=  mysqli_query($conex, $sqlAlumnoCarrera);
           
           $arreglo_carreras=array();
           while ($filaAlumnoCarrera=  mysqli_fetch_assoc($resultadoSqlAlumnoCarrera)) {
               $arr=array();
               $arr['id']=$filaAlumnoCarrera['id'];
               $arr['nombre']=$filaAlumnoCarrera['descripcion'];
               array_push($arreglo_carreras,$arr);
               
           };
           return $arreglo_carreras;
           //var_dump($_SESSION['ARRAY_CARRERAS']);
        };


/***************************************************************************************/
/***************************************************************************************/
function generarArregloAprobadas($conex, $idAlumno) {
    $ARRAY_CARRERAS=generaArregloCarrerasAlumno($conex, $idAlumno);     
    $strCarreraSql = "";
    foreach ($ARRAY_CARRERAS as $carrera) {
        $strCarrera = "b.id=" . $carrera['id'];
        $strCarreraSql = $strCarreraSql . $strCarrera . " or ";
    }
    $cadenaCarrera = substr($strCarreraSql, 0, strlen($strCarreraSql) - 3); //Esta cadena es para la SQL
    
// **************************** MATERIAS APROBADAS **********************************************************
    $arregloMateriasAprobadasTodasCarreras = array();
    $sqlMateriasAprobadas = "SELECT distinct (b.id) as idCarrera, b.descripcion as nombreCarrera, d.id as idMateria, d.nombre as nombreMateria
                        FROM alumno_rinde_materia a, carrera b, carrera_tiene_materia c, materia d
                        WHERE a.idAlumno={$idAlumno} and a.estado_final='Aprobo' and
                                     a.idMateria=c.idMateria and c.idMateria=d.id and c.idCarrera=b.id and ({$cadenaCarrera})";
    $resultado = mysqli_query($conex, $sqlMateriasAprobadas);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $arregloMateriaAprobadaPorCarrera = array();
        $arregloMateriaAprobadaPorCarrera['idCarrera'] = $fila['idCarrera'];
        $arregloMateriaAprobadaPorCarrera['nombreCarrera'] = $fila['nombreCarrera'];
        $arregloMateriaAprobadaPorCarrera['idMateria'] = $fila['idMateria'];
        $arregloMateriaAprobadaPorCarrera['nombreMateria'] = $fila['nombreMateria'];
        array_push($arregloMateriasAprobadasTodasCarreras, $arregloMateriaAprobadaPorCarrera);
    }
    return $arregloMateriasAprobadasTodasCarreras;
}


/***************************************************************************************/
/***************************************************************************************/
function generarArregloAprobadasMinimo($conex, $idAlumno) {
    $sql = "SELECT distinct idMateria FROM alumno_rinde_materia WHERE idAlumno={$idAlumno} and estado_final='Aprobo'";
    $resultado = mysqli_query($conex, $sql);
    $arrayAprobadasDelAlumno = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        array_push($arrayAprobadasDelAlumno, $fila['idMateria']);
    }
    return $arrayAprobadasDelAlumno;
}


/***************************************************************************************/
/***************************************************************************************/
function generarArregloRegulares($conex, $idAlumno) {
    $ARRAY_CARRERAS=generaArregloCarrerasAlumno($conex, $idAlumno);    
    $ARRAY_MATERIAS_APROBADAS_TODAS_CARRERAS=generarArregloAprobadas($conex,$idAlumno);
    $strCarreraSql = "";
    foreach ($ARRAY_CARRERAS as $carrera) {
        $strCarrera = "b.id=" . $carrera['id'];
        $strCarreraSql = $strCarreraSql . $strCarrera . " or ";
    }
    $cadenaCarrera = substr($strCarreraSql, 0, strlen($strCarreraSql) - 3); //Esta cadena es para la SQL
    
    $arregloMateriasRegularesTodasCarreras = array();
    $sqlMateriasRegulares = "SELECT distinct (b.id) as idCarrera, b.descripcion as nombreCarrera, d.id as idMateria, 
                               d.nombre as nombreMateria, a.anioCursado as anioCursado, a.tipo as tipoCursado,
                               d.anio as anioMateria
                        FROM alumno_cursa_materia a, carrera b, carrera_tiene_materia c,materia d 
                        WHERE a.idAlumno={$idAlumno} and 
                             (a.estado_final='Regularizo' or a.estado_final='Promociono' or a.estado_final='Libre') and
                              a.idMateria=c.idMateria and c.idCarrera=b.id and c.idMateria=d.id and c.idMateria=d.id and 
                             (d.idFormato=1 or d.idFormato=2 or d.idFormato=3 or d.idFormato=4 or d.idFormato=5) and
                             ({$cadenaCarrera}) 
                        ORDER BY d.id, a.anioCursado";
    $resultado = mysqli_query($conex, $sqlMateriasRegulares);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $arregloMateriaRegularPorCarrera = array();
        $arregloMateriaRegularPorCarrera['idCarrera'] = $fila['idCarrera'];
        $arregloMateriaRegularPorCarrera['nombreCarrera'] = $fila['nombreCarrera'];
        $arregloMateriaRegularPorCarrera['idMateria'] = $fila['idMateria'];
        $arregloMateriaRegularPorCarrera['nombreMateria'] = $fila['nombreMateria'];
        array_push($arregloMateriasRegularesTodasCarreras, $arregloMateriaRegularPorCarrera);
    }
    $arregloMateriasRegularesTodasCarreras_1 = array();
//    var_dump($arregloMateriasRegularesTodasCarreras); echo "<br><br>";
//    var_dump($ARRAY_MATERIAS_APROBADAS_TODAS_CARRERAS);die;
    foreach ($arregloMateriasRegularesTodasCarreras as $valor) {
        if (!in_array($valor, $ARRAY_MATERIAS_APROBADAS_TODAS_CARRERAS)) {
            array_push($arregloMateriasRegularesTodasCarreras_1, $valor);
        }
    } // END foreach
    return $arregloMateriasRegularesTodasCarreras_1;
}

/***************************************************************************************/
/***************************************************************************************/
function generarArregloRegularesMinimo($conex, $idAlumno) {
   $ARRAY_CARRERAS=generaArregloCarrerasAlumno($conex, $idAlumno);
   $ARRAY_MATERIAS_APROBADAS_TODAS_CARRERAS=generarArregloAprobadas($conex,$idAlumno);
    $strCarreraSql = "";
    foreach ($ARRAY_CARRERAS as $carrera) {
        $strCarrera = "b.id=" . $carrera['id'];
        $strCarreraSql = $strCarreraSql . $strCarrera . " or ";
    }
    $cadenaCarrera = substr($strCarreraSql, 0, strlen($strCarreraSql) - 3); //Esta cadena es para la SQL
    $arregloMateriasRegularesTodasCarreras = array();
    $sqlMateriasRegulares = "SELECT distinct (b.id) as idCarrera, b.descripcion as nombreCarrera, d.id as idMateria, 
                               d.nombre as nombreMateria, a.anioCursado as anioCursado, a.tipo as tipoCursado,
                               d.anio as anioMateria
                        FROM alumno_cursa_materia a, carrera b, carrera_tiene_materia c,materia d 
                        WHERE a.idAlumno={$idAlumno} and 
                             (a.estado_final='Regularizo' or a.estado_final='Promociono' or a.estado_final='Libre') and
                              a.idMateria=c.idMateria and c.idCarrera=b.id and c.idMateria=d.id and c.idMateria=d.id and 
                             (d.idFormato=1 or d.idFormato=2 or d.idFormato=3 or d.idFormato=4 or d.idFormato=5) and
                             ({$cadenaCarrera}) 
                        ORDER BY d.id, a.anioCursado";
    //echo $sqlMateriasRegulares;die;
    $resultado = mysqli_query($conex, $sqlMateriasRegulares);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $arregloMateriaRegularPorCarrera = array();
        $arregloMateriaRegularPorCarrera['idCarrera'] = $fila['idCarrera'];
        $arregloMateriaRegularPorCarrera['nombreCarrera'] = $fila['nombreCarrera'];
        $arregloMateriaRegularPorCarrera['idMateria'] = $fila['idMateria'];
        $arregloMateriaRegularPorCarrera['nombreMateria'] = $fila['nombreMateria'];
        array_push($arregloMateriasRegularesTodasCarreras, $arregloMateriaRegularPorCarrera);
    }
    $arrayRegularesDelAlumno = array();
    foreach ($arregloMateriasRegularesTodasCarreras as $valor) {
        if (!in_array($valor, $ARRAY_MATERIAS_APROBADAS_TODAS_CARRERAS)) {
            array_push($arrayRegularesDelAlumno, $valor['idMateria']);
        }
    } // END foreach
    return $arrayRegularesDelAlumno;
}
//************************************************************************************************************************
?>
