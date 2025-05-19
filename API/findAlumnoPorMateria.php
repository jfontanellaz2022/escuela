<?php
//***************************************************************************************************
//** Autor: jfontanellaz@gmail.com                                                                 **
//** SACA TODOS LOS ALUMNOS HABILITADOS QUE ESTAN CURSANDO UNA MATERIA EN UNA AÑO DADO             **
//***************************************************************************************************

//***************************************************************************************************
//** Parámetros                                                                                    **
//** ______________________________________________________________________________________________**
//** id_materia                                                                                    **
//** sinLibres                                                                                     **
//** anio                                                                                          **
//** ______________________________________________________________________________________________**
//** Valores Devueltos                                                                             **
//** ______________________________________________________________________________________________**
//***************************************************************************************************


set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel2.php";
require_once('Alumno.php');
require_once 'conexion.php';
require_once 'SanitizeCustom.class.php';

$id_materia = (isset($_POST['materia']))?SanitizeCustom::INT($_POST['materia']):false;
$id_alumno = (isset($_POST['alumno']))?SanitizeCustom::INT($_POST['alumno']):false;
$anio = (isset($_POST['anio']))?SanitizeCustom::INT($_POST['anio']):date('Y');
//var_dump($_POST);
//$anio = 2023;

$sql = "SELECT a.*, acm.anio_cursado, acm.tipo as cursado, acm.estado_final,
                    acm.fecha_inscripcion, acm.nota, acm.fecha_modificacion_nota, 
                    p.apellido, p.nombre, p.email, p.telefono_caracteristica, p.telefono_numero
        FROM alumno a, alumno_cursa_materia acm, persona p
        WHERE acm.idMateria = $id_materia and
              acm.idAlumno = a.id and
              acm.anio_cursado = $anio and
              a.id = $id_alumno and
              a.dni = p.dni and
              a.habilitado = 'Si'";
//echo $sql;die;
$resultado = mysqli_query($conex,$sql);

if (mysqli_num_rows($resultado)>0) {
   $filas = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
   $array_resultados['codigo'] = 200;
   $array_resultados['mensaje'] = "ok";
   $array_resultados['datos'] = $filas;
 } else {
   $array_resultados['codigo'] = 11;
   $array_resultados['data'] = "No existe Carrera.";
 }


echo json_encode($array_resultados);



?>
