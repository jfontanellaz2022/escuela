<?php

//require_once('./controlAcceso.php');
set_include_path('../../conexion'.PATH_SEPARATOR.'../../lib'.PATH_SEPARATOR.'./');

require_once('arregloMateriasRegularesAprobadas.php');

function formatearMatriz($arr) {
    foreach ($arr as $val_1) {
        echo "(";
        foreach ($val_1 as $key => $val_2) {
            echo " " . $key . "=>" . $val_2 . ", ";
        };
        echo ")" . "<br>";
    };
}




//******************************************************************************************************************
/* FUNCION CHEQUEA QUE UNA MATERIA REGULAR DE UN ALUMNO CUMPLA CON LAS CORRELATIVAS CORRESPONDIENTES PARA RENDIR  **
/* TAMBIEN CHEQUEA QUE DICHA MATERIA QUE SE EVALUA LAS CORRELATIVAS NO ESTE APROBADA PREVIAMENTE
// VALOR DE RETORNO:

 TRUE: la Materia que se esta evaluando no esta aprobada todavia y esta en estado regular, libre, o promocionada y cumple con las correlativas
 FALSE: La materia que se esta evaluando ya esta aprobada o no cumple con las correlatividades exigidas
********************************************************************************************************** */
function verificaCorrelatividadesParaRendir($conex, $idAlumno, $idMateria) {
    $ARRAY_APROBADAS_ALUMNO = generarArregloAprobadasMinimo($conex, $idAlumno);
    $ARRAY_REGULARES_ALUMNO = generarArregloRegularesMinimo($conex, $idAlumno);
//********* En esta consulta saco las materias correlativas aprobadas para rendir una materia dada ********** //
    $sql = "SELECT a.idMateriaRequerida as idMateriaRequerida, "
            . "    b.nombre as nombre, "
            . "    c.descripcion as descripcion "
            . "FROM  correlativaspararendir a, materia b, condicionmateria c                      "
            . "WHERE (a.idMateriaRequerida=b.id and "
            . "       a.idCondicionMateriaRequerida=c.id and "
            . "       a.idMateria={$idMateria} and "
            . "       c.descripcion='aprobada')";
    $resultado = mysqli_query($conex, $sql);
    $arrayAprobadasRequeridas = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        array_push($arrayAprobadasRequeridas, $fila['idMateriaRequerida']);
    }

    //VERIFICA QUE LAS MATERIAS APROBADAS REQUERIDA DE UNA MATERIA DADA, ESTE O NO EN EL ARREGLO DE APROBADAS.
    $bandAprobadas = true;
    foreach ($arrayAprobadasRequeridas as $valor) {
        if (!in_array($valor, $ARRAY_APROBADAS_ALUMNO)) {
            $bandAprobadas = false;
            break;
        } // END if
    } // END foreach
//********* En esta consulta saco las materias correlativas Regulares para rendir una materia dada *********** //
    $sql = "SELECT a.idMateriaRequerida as idMateriaRequerida, b.nombre as nombre, c.descripcion as descripcion "
            . "FROM  correlativaspararendir a, materia b, condicionmateria c                      "
            . "WHERE (a.idMateriaRequerida=b.id and "
            . "a.idCondicionMateriaRequerida=c.id and a.idMateria='{$idMateria}' and "
            . "c.descripcion='Regular')";
    $resultado = mysqli_query($conex, $sql);
    $arrayRegularesRequeridas = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        array_push($arrayRegularesRequeridas, $fila['idMateriaRequerida']);
    }

    //VERIFICA QUE LAS MATERIAS REGULARES REQUERIDA DE UNA MATERIA DADA, ESTE O NO EN EL ARREGLO DE REGULARES O DE APROBADAS.
    $bandRegulares = true;
    foreach ($arrayRegularesRequeridas as $valor) {
        if (!in_array($valor, $ARRAY_REGULARES_ALUMNO) && !in_array($valor, $ARRAY_APROBADAS_ALUMNO)) {
            $bandRegulares = false;
            break;
        } // END if
    } // END foreach

    if (in_array($idMateria, $ARRAY_APROBADAS_ALUMNO)) {
        $bandEsta = true;
        $dev = 'si';
    } else {
        $bandEsta = false;
        $dev = 'No';
    }

    if (($bandAprobadas) && ($bandRegulares) && (!$bandEsta))
        return true;
    else
        return false;
}





//****************************************************************************************************
/* SE GENERA EL ARREGLO DE MATERIAS QUE ESTAMOS EN CONDICIONES DE RENDIR   ***************************
******************************************************************************************************/
function generaArregloMateriasEnCondicionesDeRendir($conex,$idAlumno) {
    //$_SESSION['arregloMateriasEnCondicionesDeRendir'] = array();
    $hoy = date('Y-m-d h:i:s');
    $sqlMateriasRegulares = "SELECT distinct a.idMateria, b.nombre,
                                             b.anio, a.tipo as tipoCursado,
                                             a.estado_final,
                                             a.anioCursado
                             FROM  alumno_cursa_materia a, materia b
                             WHERE a.idAlumno = {$idAlumno} and
                                  a.FechaVencimientoRegularidad >= '$hoy' and
                                   a.idMateria = b.id and
                                   (a.estado_final='Regularizo' or a.estado_final='Promociono' or a.estado_final='Libre') and
                                   (b.idFormato=1) /* b.idFormato=2 or b.idFormato=3 or b.idFormato=4 or */
                             ORDER BY b.anio, b.nombre";
    //echo $sqlMateriasRegulares;

    $resultadosqlMateriasRegulares = mysqli_query($conex, $sqlMateriasRegulares);
    $arregloMateriasEnCondicionesDeRendir = array();
    while ($filaMateriasRegulares = mysqli_fetch_assoc($resultadosqlMateriasRegulares)) {
        if (verificaCorrelatividadesParaRendir($conex, $idAlumno, $filaMateriasRegulares['idMateria'])) {
            $arrayMateria = array();
            $arrayMateria['idMateria'] = $filaMateriasRegulares['idMateria'];
            $arrayMateria['nombre'] = $filaMateriasRegulares['nombre'];
            $arrayMateria['anio'] = $filaMateriasRegulares['anio'];
            $arrayMateria['tipoCursado'] = $filaMateriasRegulares['tipoCursado'];
            $arrayMateria['anioCursado'] = $filaMateriasRegulares['anioCursado'];
            $arrayMateria['estado_final'] = $filaMateriasRegulares['estado_final'];
            array_push($arregloMateriasEnCondicionesDeRendir, $arrayMateria);
        };
    };
    //var_dump($arregloMateriasEnCondicionesDeRendir);
    //Aca saco las regularidades duplicadas dejando la ultima es decir la regularidad mas reciente
    $arrTmp = array();
    foreach ($arregloMateriasEnCondicionesDeRendir as $val_1) {
        if (count($arrTmp) == 0) {
            array_push($arrTmp, $val_1);
        } else {
            foreach ($arrTmp as $clave => $val_2) {
                if ($val_1['idMateria'] == $val_2['idMateria']) {
                    $band = false;
                    if ($val_1['anioCursado'] > $val_2['anioCursado']) {
                        $arrTmp[$clave]['anioCursado'] = $val_1['anioCursado'];
                        $arrTmp[$clave]['tipoCursado'] = $val_1['tipoCursado'];
                    };
                } else {
                    $band = true;
                }
            }
            if ($band)
                array_push($arrTmp, $val_1);
        }
    }
//var_dump($arrayTmp);////formatearMatriz($arrTmp);die();
    $arregloMateriasEnCondicionesDeRendir = array();
    $arregloMateriasEnCondicionesDeRendir = $arrTmp;
    return $arregloMateriasEnCondicionesDeRendir;
}

//******************************************************************************************
//Esta funcion recibe un alumno y una materia. Devuelve TRUE o FALSE SI LA PUEDE CURSAR.
//********************************************************************************************
function verificaCorrelatividadesParaCursar($conex, $idAlumno, $idMateria) {
    generarArregloRegularesAprobadas($conex, $idAlumno);
//********* En esta consulta saco las materias correlativas aprobadas para rendir una materia dada ********** //                $
    $sql = "SELECT a.idMateriaRequerida as idMateriaRequerida, b.nombre as nombre, c.descripcion as descripcion                        "
            . "FROM  correlativaspararendir a, materia b, condicionmateria c                      "
            . "WHERE (a.idMateriaRequerida=b.id and
	            a.idCondicionMateriaRequerida=c.id and
	            a.idMateria={$idMateria} and
	            c.descripcion='aprobada')";
    $resultado = mysqli_query($conex, $sql);

    $arrayAprobadasRequeridas = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        array_push($arrayAprobadasRequeridas, $fila['idMateriaRequerida']);
    }

//Verifica que la materias Aprobadas Requeridas, esten o no en el arreglo de Aprobadas
    $bandAprobadas = true;
    foreach ($arrayAprobadasRequeridas as $valor) {
        if (!in_array($valor, $_SESSION['arregloAprobadasDelAlumno'])) {
            $bandAprobadas = false;
            break;
        } // END if
    } // END foreach
//********* En esta consulta saco las materias correlativas Regulares para rendir una materia dada *********** //
    $sql = "SELECT a.idMateriaRequerida as idMateriaRequerida, b.nombre as nombre, c.descripcion as descripcion "
            . "FROM  correlativaspararendir a, materia b, condicionmateria c                      "
            . "WHERE (a.idMateriaRequerida=b.id and "
            . "a.idCondicionMateriaRequerida=c.id and a.idMateria='{$idMateria}' and "
            . "c.descripcion='Regular')";
    $resultado = mysqli_query($conex, $sql);

    $arrayRegularesRequeridas = array();
    while ($fila = mysqli_fetch_assoc($resultado)) {
        array_push($arrayRegularesRequeridas, $fila['idMateriaRequerida']);
    }

//Verifica que la materias Regulares Requeridas, esten o no en los arreglos de Aprobadas o Regulares
    $bandRegulares = true;
    foreach ($arrayRegularesRequeridas as $valor) {
        if (!in_array($valor, $_SESSION['arregloRegularesDelAlumno']) &&
                !in_array($valor, $_SESSION['arregloAprobadasDelAlumno'])) {
            $bandRegulares = false;
            break;
        } // END if
    } // END foreach
//Verifica que la materia a verificar no este aprobada.
    if (in_array($idMateria, $_SESSION['arregloAprobadasDelAlumno'])) {
        $bandEsta = true;
        $dev = 'si';
    } else {
        $bandEsta = false;
        $dev = 'No';
    }


    if (($bandAprobadas) && ($bandRegulares) && (!$bandEsta))
        return true;
    else
        return false;
}

// END  function verificaCorrelatividadesParaRendir($idAlumno, $idMateria)
//******************************************************************************************
//Esta funcion recibe un alumno. Devuelve un arreglo con las materias que puede cursar.
//********************************************************************************************
function generaArregloMateriasEnCondicionesDeCursar($conex, $idAlumno) {
    $_SESSION['arregloMateriasEnCondicionesDeRendir'] = array();
    //En esta consulta debo sacar las materias de la carrera que no tengo aprobadas
    $sqlMateriasRegulares = "SELECT distinct a.idMateria,
				b.nombre,
				b.anio,
	                        a.tipo as tipoCursado,
				a.estado_final,
				a.anioCursado "
            . "FROM alumno_cursa_materia a, materia b "
            . "WHERE a.idAlumno={$idAlumno} and
			         a.idMateria = b.id and
			         (a.estado_final='Regularizo' or a.estado_final='Promociono' or a.estado_final='Libre') and
				 (b.idFormato=1  or b.idFormato=2 or b.idFormato=3 or b.idFormato=4 or b.idFormato=5)
  			   ORDER BY b.anio, b.nombre";
    $resultadosqlMateriasRegulares = mysqli_query($conex, $sqlMateriasRegulares);
    while ($filaMateriasRegulares = mysqli_fetch_assoc($resultadosqlMateriasRegulares)) {
        if (verificaCorrelatividadesParaRendir($conex, $idAlumno, $filaMateriasRegulares['idMateria'])) {
            $arrayMateria = array();
            $arrayMateria['idMateria'] = $filaMateriasRegulares['idMateria'];
            $arrayMateria['nombre'] = $filaMateriasRegulares['nombre'];
            $arrayMateria['anio'] = $filaMateriasRegulares['anio'];
            $arrayMateria['tipoCursado'] = $filaMateriasRegulares['tipoCursado'];
            $arrayMateria['anioCursado'] = $filaMateriasRegulares['anioCursado'];
            $arrayMateria['estado_final'] = $filaMateriasRegulares['estado_final'];
            array_push($_SESSION['arregloMateriasEnCondicionesDeRendir'], $arrayMateria);
        };
    };     
    $arrTmp = array();
    foreach ($_SESSION['arregloMateriasEnCondicionesDeRendir'] as $val_1) {
        if (count($arrTmp) == 0) {
            array_push($arrTmp, $val_1);
        } else {
            foreach ($arrTmp as $clave => $val_2) {
                if ($val_1['idMateria'] == $val_2['idMateria']) {
                    $band = false;
                    if ($val_1['anioCursado'] > $val_2['anioCursado']) {
                        $arrTmp[$clave]['anioCursado'] = $val_1['anioCursado'];
                        $arrTmp[$clave]['tipoCursado'] = $val_1['tipoCursado'];
                    };
                } else {
                    $band = true;
                }
            }
            if ($band)
                array_push($arrTmp, $val_1);
        }
    }
    $_SESSION['arregloMateriasEnCondicionesDeRendir'] = array();
    $_SESSION['arregloMateriasEnCondicionesDeRendir'] = $arrTmp;
};
?>
