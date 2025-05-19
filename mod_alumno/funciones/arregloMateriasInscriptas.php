<?php

function generaArregloMateriasInscriptas($conex, $idCalendario,$idAlumno) {
    $arregloMateriasInscriptas=array();
    $arregloMateriasInscriptasSoloId=array();
    $arreglo_todo = array();
    $sqlInscripcionesAlumno="SELECT *
                             FROM alumno_rinde_materia
                             WHERE idAlumno=$idAlumno and
                                   idCalendario=$idCalendario and
                                   estado_final='Pendiente'";
    //echo $sqlInscripcionesAlumno;
    $resultadoInscripcionesAlumno=  mysqli_query($conex, $sqlInscripcionesAlumno);
    if (mysqli_num_rows($resultadoInscripcionesAlumno)>0) {
               while ($filaMateriaInscripta=  mysqli_fetch_assoc($resultadoInscripcionesAlumno)) {
                  $arregloMateriaInscripta=array();
                  $arregloMateriaInscripta['idMateria']=$filaMateriaInscripta['idMateria'];
                  $arregloMateriaInscripta['llamado']=$filaMateriaInscripta['llamado'];
               //   array_push($arregloMateriaInscripta, $filaMateriaInscripta['idMateria'],$filaMateriaInscripta['llamado']);
                  array_push($arregloMateriasInscriptas,$arregloMateriaInscripta);
                  array_push($arregloMateriasInscriptasSoloId,$filaMateriaInscripta['idMateria']);

               };
      };
      $arreglo_todo['materias_inscriptas'] = $arregloMateriasInscriptas;
      $arreglo_todo['materias_inscriptas_solo_id'] = $arregloMateriasInscriptasSoloId;
      return $arreglo_todo;

}

?>
