<?php
        
function generaArregloCarrerasPorMateria($idMateria, $conex) {
    $idAlumno=$_SESSION['idAlumno'];
    $sqlAlumnoCarrera="select b.id, b.descripcion 
                       from alumno_estudia_carrera a, carrera b
                       where a.idAlumno={$idAlumno} and 
                             a.idCarrera=b.id";

    $resultadoSqlAlumnoCarrera=  mysqli_query($conex, $sqlAlumnoCarrera);
    $arregloCarreras=array();
    while ($filaAlumnoCarrera=  mysqli_fetch_assoc($resultadoSqlAlumnoCarrera)) {
               array_push($arregloCarreras, $filaAlumnoCarrera['id']."_".$filaAlumnoCarrera['descripcion']);
    };
           
    $arregloFinal=array();
    foreach ($arregloCarreras as $valor) {
               $arregloTmp=0;$idCarrera=0;$nombreCarrera="";
               $arregloTmp=explode('_',$valor);
               $idCarrera=$arregloTmp[0];
               $nombreCarrera=$arregloTmp[1];
               $sqlMateriaPerteneceCarrera="Select b.id, b.descripcion
                                            FROM carrera_tiene_materia a, carrera b
                                            WHERE a.idCarrera=b.id and a.idMateria={$idMateria} and 
                                               a.idCarrera={$idCarrera}";
               $resultadoMateriaPerteneceCarrera=  mysqli_query($conex, $sqlMateriaPerteneceCarrera);
               if (mysqli_num_rows($resultadoMateriaPerteneceCarrera)>0) {
                     $arregloFinal[$idCarrera]=$nombreCarrera;
               }
                 
           }
         return $arregloFinal;  
        }

function generaArregloCarrerasPorMateriaGeneral($idMateria, $conex) {
               $sqlMateriaPerteneceCarrera="Select b.id, b.descripcion
                                            FROM carrera_tiene_materia a, carrera b
                                            WHERE a.idCarrera=b.id and a.idMateria={$idMateria}";
               $resultadoMateriaPerteneceCarrera=  mysqli_query($conex, $sqlMateriaPerteneceCarrera);
               if (mysqli_num_rows($resultadoMateriaPerteneceCarrera)>1) {
                     $nombreCarrera="Tecnicaturas";
               } else if (mysqli_num_rows($resultadoMateriaPerteneceCarrera)==1) {
                   $fila=  mysqli_fetch_assoc($resultadoMateriaPerteneceCarrera);
                   $nombreCarrera=$fila['descripcion'];
               };
         return $nombreCarrera;  
        };
        
        
        
?>