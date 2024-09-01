<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/'.PATH_SEPARATOR.'./');

include_once 'conexion.php';
include_once 'ArrayHash.class.php';
require_once "_seguridad.php";

/*
 * Este Proceso saca todos los Promocionados de la tabla 'alumno_cursa_materia', pero que
 * todavia no se les paso la aprobacion a la tabla 'alumno_rinde_materia'
 * VERIFICA QUE LOS APROBADOS/PROMOCIONADOS CUMPLAN CON LAS CORRELATIVAS Y LOS METE EN UN ARREGLO DE SESSION
 * $_SESSION['arreglo_todos_promocionados_que_cumplen_con_correlativas']
 *
 */

//Se verifica si el alumno se inscribio temporalmente a la materia que promociono
function estaInscriptoTemporalmente($conex,$idAlumno,$idMateria,$idCalendario) {
    $sql = "SELECT *
            FROM alumno_rinde_materia
            WHERE idAlumno=$idAlumno AND 
                  idMateria=$idMateria AND 
                  idCalendario=$idCalendario";
    $resultado = mysqli_query($conex,$sql);
    if (mysqli_num_rows($resultado)>0) return TRUE;
    else return FALSE;
}


function sacarUltimoIdInscripcion($conex) {
    $idCalendario = 0;
    $sql = "SELECT ca.id as idCalendario
            FROM calendarioacademico ca, evento e
            WHERE ca.idEvento = e.id and (e.codigo = 1005 or e.codigo = 1006 or e.codigo = 1007 or e.codigo = 1008) 
            ORDER BY ca.id DESC 
            LIMIT 0,1";
    $resultado = mysqli_query($conex,$sql);
    if (mysqli_num_rows($resultado)>0) {
        $row=mysqli_fetch_assoc($resultado);
        $idCalendario = $row['idCalendario'];
        return $idCalendario;
    } else return 0;
};

$idCalendario = sacarUltimoIdInscripcion($conex);
//die($idCalendario);
$bandExisteTurnoActivo = true;
$anioActual = date('Y');
$values="";$valuesLimpio="";
$hoy=date("Y-m-d H:i:s");

if (!empty($_SESSION['arreglo_todos_promocionados_que_cumplen_con_correlativas'])) {
    foreach ($_SESSION['arreglo_todos_promocionados_que_cumplen_con_correlativas'] as $valor) {    
      if ($valor[8]) { // ACA SE PREGUNTA SI ES TRUE QUE CUMPLE CON LAS CORRELATIVAS
         $idAlumno = $valor[0];
         $idMateria = $valor[1];
         $Apellido = $valor[5];
         $nombre = $valor[6];
         $dni = $valor[4];
         $carrera = $valor[2];
         $materia = $valor[3];
         $nota = $valor[7];
         //echo $idAlumno.'-'.$dni.'-'.$Apellido.'-'.$nombre.'-'.$carrera.'-'.$materia."<br>";
         $values.="({$idAlumno},{$idMateria},{$idCalendario},1,'Promocion',{$nota},'Aprobo', '{$hoy}'),";
         
         if (estaInscriptoTemporalmente($conex,$idAlumno,$idMateria,$idCalendario)) {
              $sqlEliminar = "DELETE FROM alumno_rinde_materia
                              WHERE idAlumno=$idAlumno AND 
                                    idMateria=$idMateria AND 
                                    idCalendario=$idCalendario";
              mysqli_query($conex,$sqlEliminar);
         };

      }; //End IF
    }; //End Foreach
    $valuesLimpio = substr($values, 0, strlen($values)-1);
    $sql = "INSERT INTO alumno_rinde_materia(idAlumno,idMateria,idCalendario,llamado,condicion,nota,estado_final, FechaModificacionNota) " .
           "values {$valuesLimpio}";
    //var_dump($sql);die;
    $resultado=mysqli_query($conex,$sql);
    if ($resultado) {
        $msg="<font color='blue'><b>Atencion:</> La Consulta fue realizada Exitosamente!!!.</font>";
    } else {
        //echo $sql."<br>";
        $msg="<font color='red'><b>Error:</> La Consulta no se pudo Realizar o no se cumplen las correlativas.</font>";
    }
    //echo $sql;
} else {
    $msg="<a href='own_ControlarPromociones.php'><font color='red'><b>Error:</> No hay Alumnos en condiciones de Promocionar.</font>";
}
$_SESSION['arreglo_todos_promocionados_que_cumplen_con_correlativas'] = array();
echo $msg;
?>
