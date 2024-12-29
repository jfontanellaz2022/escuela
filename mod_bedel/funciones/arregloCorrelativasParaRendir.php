<?php
session_start();
/* Esta funcion llena los siguientes arreglos de Session */
function arma_arreglos_regulares_aprobadas($idAlumno,$conex)
{
        $_SESSION['arregloMateriasAprobadasTodasCarreras']=array();
        $_SESSION['arregloAprobadasDelAlumno']=array();
        $_SESSION['arregloMateriasRegularesTodasCarreras']=array();
        $_SESSION['arregloRegularesDelAlumno']=array();
        $_SESSION['ARRAY_CARRERAS']=array();

        $sqlAlumnoCarrera = "select b.id, b.descripcion 
                             from alumno_estudia_carrera a, carrera b
                             where a.idAlumno = {$idAlumno} and 
                                   a.idCarrera = b.id";
        $resultadoSqlAlumnoCarrera = mysqli_query($conex, $sqlAlumnoCarrera);
           
        
        if (mysqli_num_rows($resultadoSqlAlumnoCarrera)>0) {
            while ($filaAlumnoCarrera=  mysqli_fetch_assoc($resultadoSqlAlumnoCarrera)) {
                   $arregloCarreras=array();
                   $arregloCarreras['id']=$filaAlumnoCarrera['id'];
                   $arregloCarreras['nombre']=$filaAlumnoCarrera['descripcion'];
                   array_push($_SESSION['ARRAY_CARRERAS'],$arregloCarreras);
            };
        };
           //var_dump($_SESSION['ARRAY_CARRERAS']);
        
        
        
        $strCarreraSql="";
        foreach ($_SESSION['ARRAY_CARRERAS'] as $carrera) {
            $strCarrera="b.id=".$carrera['id'];   
            $strCarreraSql=$strCarreraSql.$strCarrera." or ";
        }

        $cadenaCarrera=substr($strCarreraSql, 0, strlen($strCarreraSql)-3);

        $arregloMateriasAprobadasTodasCarreras=array();

        $sqlMateriasAprobadas="SELECT distinct (b.id) as idCarrera, b.descripcion as nombreCarrera, d.id as idMateria, d.nombre as nombreMateria
                        FROM alumno_rinde_materia a, carrera b, carrera_tiene_materia c, materia d
                        WHERE a.idAlumno={$idAlumno} and a.estado_final='Aprobo' and
                                     a.idMateria=c.idMateria and c.idCarrera=b.id and ({$cadenaCarrera})";                                     
        //echo $sqlMateriasAprobadas;die('hata aca');
        $resultado=mysqli_query($conex, $sqlMateriasAprobadas);
        //var_dump($resultado);die($sqlMateriasAprobadas);
        if (!$resultado) die("<font color='red'><b>Error: </b>Falta Carga Carrera al Alumno: <a href='alumno.php?hname=alumno_estudia_carreraDetailEdit0alumno_handler&fk0={$idAlumno}'>{$idAlumno}</a></font>");
        //echo mysqli_num_rows($resultado);die;
        if (mysqli_num_rows($resultado)>0) {
            while ($fila=mysqli_fetch_assoc($resultado)) {
                $arregloMateriaAprobadaPorCarrera=array(); 
                $arregloMateriaAprobadaPorCarrera['idCarrera']=$fila['idCarrera'];
                $arregloMateriaAprobadaPorCarrera['nombreCarrera']=$fila['nombreCarrera'];
                $arregloMateriaAprobadaPorCarrera['idMateria']=$fila['idMateria'];
                $arregloMateriaAprobadaPorCarrera['nombreMateria']=$fila['nombreMateria'];
                array_push($arregloMateriasAprobadasTodasCarreras,$arregloMateriaAprobadaPorCarrera);
                $_SESSION['arregloMateriasAprobadasTodasCarreras']=$arregloMateriasAprobadasTodasCarreras; 
            };
        };
        $sql="SELECT distinct idMateria FROM alumno_rinde_materia WHERE idAlumno={$idAlumno} and estado_final='Aprobo'";
        $resultado=mysqli_query($conex,$sql);
        $arrayAprobadasDelAlumno=array();
        if (mysqli_num_rows($resultado)>0) {
             while ($fila=mysqli_fetch_assoc($resultado)) {array_push($arrayAprobadasDelAlumno, $fila['idMateria']);}
        };    
        $_SESSION['arregloAprobadasDelAlumno']=$arrayAprobadasDelAlumno;	

        $arregloMateriasRegularesTodasCarreras=array();
        $sqlMateriasRegulares="SELECT distinct (b.id) as idCarrera, b.descripcion as nombreCarrera, d.id as idMateria, d.nombre as nombreMateria
                               FROM alumno_cursa_materia a, carrera b, carrera_tiene_materia c,materia d
                               WHERE a.idAlumno={$idAlumno} and a.estado_final='Regularizo' and
                                             a.idMateria=c.idMateria and c.idCarrera=b.id and
                                             c.idMateria=d.id and ({$cadenaCarrera})
                               UNION 
                               SELECT distinct (b.id) as idCarrera, b.descripcion as nombreCarrera, d.id as idMateria, d.nombre as nombreMateria
                               FROM alumno_cursa_materia a, carrera b, carrera_tiene_materia c,materia d
                               WHERE a.idAlumno={$idAlumno} and a.estado_final='Libre' and
                                             a.idMateria=c.idMateria and c.idCarrera=b.id and
                                             c.idMateria=d.id and d.idFormato=1 and ({$cadenaCarrera})
                                             ";
        //echo $sqlMateriasRegulares; die ('aca 2 ');                                   
        $resultado=mysqli_query($conex,$sqlMateriasRegulares);
        if (mysqli_num_rows($resultado)>0) {
            while ($fila=mysqli_fetch_assoc($resultado)) {
                $arregloMateriaRegularPorCarrera=array(); 
                $arregloMateriaRegularPorCarrera['idCarrera']=$fila['idCarrera'];
                $arregloMateriaRegularPorCarrera['nombreCarrera']=$fila['nombreCarrera'];
                $arregloMateriaRegularPorCarrera['idMateria']=$fila['idMateria'];
                $arregloMateriaRegularPorCarrera['nombreMateria']=$fila['nombreMateria'];
                array_push($arregloMateriasRegularesTodasCarreras,$arregloMateriaRegularPorCarrera);
            };
        };
        //var_dump($arrayMateriasRegularesTodasCarreras[0]);

        $arregloMateriasRegularesTodasCarreras_1=array();
        foreach ($arregloMateriasRegularesTodasCarreras as $valor) {
                                if (!in_array($valor, $_SESSION['arregloMateriasAprobadasTodasCarreras'])) 
                                {
                                   array_push($arregloMateriasRegularesTodasCarreras_1, $valor);
                                }
                        } // END foreach

        $_SESSION['arregloMateriasRegularesTodasCarreras']=$arregloMateriasRegularesTodasCarreras_1;
        $_SESSION['arregloTodasMateriasInscriptas']=array();

        $sql="SELECT distinct idMateria FROM alumno_cursa_materia 
              WHERE idAlumno={$idAlumno} and (estado_final='Regularizo' or estado_final='Promociono') 
              UNION
              SELECT distinct idMateria FROM alumno_cursa_materia a, materia b 
              WHERE a.idAlumno={$idAlumno} and a.estado_final='Libre' and  
                    a.idMateria=b.id and b.idFormato=1";
        $resultado=mysqli_query($conex,$sql);
        $arrayRegularesDelAlumno=array();
        while ($fila=mysqli_fetch_assoc($resultado)) {array_push($arrayRegularesDelAlumno, $fila['idMateria']);}
                        //print_r($arrayRegularesDelAlumno);
        $_SESSION['arregloRegularesDelAlumno']=$arrayRegularesDelAlumno;
}


//******************************************************************************************************
//ESTA FUNCION RECIBE UN IDALUMNO Y UNA MATERIA QUE TIENE PARA APROBAR (PROMOCION O APROBACION/TALLER)
//LA FUNCION DEVUELVE TRUE O FALSE SI TIENE O NO LAS CORRELATIVAS CORRESPONDIENTE
//******************************************************************************************************
function verificaCorrelatividadesParaRendir($idAlumno, $idMateria, $conex) {
        arma_arreglos_regulares_aprobadas($idAlumno,$conex);
        $sql="SELECT a.idMateriaRequerida as idMateriaRequerida, b.nombre as nombre, c.descripcion as descripcion  
              FROM  correlativaspararendir a, materia b, condicionmateria c
              WHERE (a.idMateriaRequerida=b.id
                    and a.idCondicionMateriaRequerida=c.id
                    and a.idMateria={$idMateria}
			        and c.descripcion='aprobada')";
	//echo $sql;die('aca');		
 		$resultado=mysqli_query($conex,$sql);
   		$arrayAprobadasRequeridas=array();
   		while ($fila=mysqli_fetch_assoc($resultado)) {array_push($arrayAprobadasRequeridas, $fila['idMateriaRequerida']);}
                                               
		$bandAprobadas=true;
		foreach ($arrayAprobadasRequeridas as $valor) {
  			if (!in_array($valor, $_SESSION['arregloAprobadasDelAlumno'])) {
			      $bandAprobadas=false;
				  break;
			  } // END if
		} // END foreach
		$sql="SELECT a.idMateriaRequerida as idMateriaRequerida, b.nombre as nombre, c.descripcion as descripcion  
              FROM  correlativaspararendir a, materia b, condicionmateria c
              WHERE (a.idMateriaRequerida=b.id 
                    and a.idCondicionMateriaRequerida=c.id
                    and a.idMateria={$idMateria}
			        and c.descripcion='Regular')";
                    //echo $sql;die('aca 2');
		$resultado=mysqli_query($conex,$sql);
		$arrayRegularesRequeridas=array();
                //var_dump($_SESSION['arregloAprobadasDelAlumno']);die;
		while ($fila=mysqli_fetch_assoc($resultado)) {array_push($arrayRegularesRequeridas, $fila['idMateriaRequerida']);}
		
		$bandRegulares=true;
		if (in_array($idMateria,$_SESSION['arregloAprobadasDelAlumno'])) {$bandEsta=true;$dev='si';}
		else {$bandEsta=false;$dev='No';}
		foreach ($arrayRegularesRequeridas as $valor) {
			if (!in_array($valor, $_SESSION['arregloRegularesDelAlumno'])&&!in_array($valor, $_SESSION['arregloAprobadasDelAlumno'])) {
      		   	  $bandRegulares=false;
				  break;
			  } // END if
		} // END foreach
		if (($bandAprobadas)&&($bandRegulares)&&(!$bandEsta)) return true; 
		else return false;
} // END  function verificaCorrelatividadesParaRendir($idAlumno, $idMateria) 

?>
