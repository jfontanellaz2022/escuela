<?php 
//Llamada al modelo
require_once("../app/models/InscripcionRendirMaterias.php");

$irm=new InscripcionRendirMaterias();

$idAlumno = 1066;
$idCarrera = 15;

$irm->getArregloMateriasVerificadasParaInscribirseDetalles($idAlumno,$idCarrera);


//Llamada a la vista
require_once("views/personas_view.phtml");
