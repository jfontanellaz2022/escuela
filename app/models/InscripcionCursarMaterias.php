<?php

require_once('AlumnoCursaMateria.php');
require_once('AlumnoRindeMateria.php');
require_once('CorrelativasParaCursar.php');
require_once('Carrera.php');


class InscripcionCursarMaterias
{

    //********************************************************************************************************/
    //Posibles materias que puede Cursar. Son candidatas porque no se aplico el control de las correlativas
    //Materias Regulares o Libres que no estan aprobadas
    //********************************************************************************************************/
    public function getMateriasCandidatasParaInscripcion($idAlumno,$idCarrera)
    {
        $arr_resultado = [];
        $arm = new AlumnoRindeMateria();
        $acm = new AlumnoCursaMateria();
        $materias_carrera = new Carrera();
        $arr_materias_carrera = $materias_carrera->getMateriasPorIdCarrera($idCarrera);
        $arr_materias_aprobadas = $arm->getMateriasRendidasByEstado($idAlumno,'Aprobo');
        //SACO TODAS LAS MATERIAS QUE NO ESTAN APROBADAS COMO CANDIDATAS PARA INSCRIBIRSE A CURSAR
        foreach ($arr_materias_carrera as $item) {
            if ((!in_array($item['materia_id'],$arr_materias_aprobadas))) {
                $arr_resultado[] = $item['materia_id'];
            };
        }
        
        return $arr_resultado;
    }


    // Verifica que una materia cumpla  o no con las correlativas. Para Cursar o para Rendir
    // de acuerdo a las materias aprobadas y/o regulares de un alumno
     
    public function getVerificaMateriaCorrelativa($idAlumno,$idMateria)
    {
        $arm = new AlumnoRindeMateria();
        $acm = new AlumnoCursaMateria();
        $correlativas = new CorrelativasParaCursar();

        $band_aprobadas = $band_regulares = TRUE; 
        $arr_materias_correlativas_requeridas = $correlativas->getMateriasCorrelativasByIdMateria($idMateria);
        //die()
        $arr_materias_correlativas_aprobadas_requeridas = $arr_materias_correlativas_requeridas['aprobadas'];
        $arr_materias_correlativas_regulares_requeridas = $arr_materias_correlativas_requeridas['regulares'];
        $arr_materias_aprobadas = $arm->getMateriasRendidasByEstado($idAlumno,'Aprobo');      
        $arr_materias_regulares = array_merge($acm->getMateriasCursadasByEstado($idAlumno,'Regularizo'),$acm->getMateriasCursadasByEstado($idAlumno,'Promociono',FALSE),$acm->getMateriasCursadasByEstado($idAlumno,'Aprobo',FALSE));
        //var_dump($arr_materias_regulares);

        //Determina si el arreglo de correlativas regulares libres es vacio o no.
        //En el caso que sea Vacio $band_regulares_libres = TRUE
        //Si no es Vacio se recorre dicho arreglo y se verifica contra el arreglo de las materias regulares y libres del alumno
        //Si encuentra que un item del primer arreglo no esta el el segundo y $band_regulares_libres = FALSE (no cumpliria con correlativas)
        if (!empty($arr_materias_correlativas_regulares_requeridas)) {
            foreach ($arr_materias_correlativas_regulares_requeridas as $item_regulares_requerida) {
                if (!in_array($item_regulares_requerida,$arr_materias_regulares) && !in_array($item_regulares_requerida,$arr_materias_aprobadas) ) {
                    $band_regulares = FALSE; 
                    break;
                };
            };
        };

        //Determina si el arreglo de correlativas aprobadas es vacio o no.
        //En el caso que sea Vacio $band_aprobadas = TRUE
        //Si no es Vacio se recorre dicho arreglo y se verifica contra el arreglo de las materias aprobadas del alumno
        //Si encuentra que un item del primer arreglo no esta el el segundo y $band_aprobadas = FALSE (no cumpliria con correlativas)
        if (!empty($arr_materias_correlativas_aprobadas_requeridas)) {
            foreach ($arr_materias_correlativas_aprobadas_requeridas as $item_aprobadas_requerida) {
                if (!in_array($item_aprobadas_requerida,$arr_materias_aprobadas) /* && $band_aprobadas*/) {
                    //die('noooo'.$idMateria);
                    $band_aprobadas = FALSE; 
                    break;
                }
            };
        };

        return ($band_regulares && $band_aprobadas);
    }


    // VERIFICA QUE SE CUMPLA LA APROBACION DE AL MENOS UNA CANTIDAD O IGUAL A UNA CANTIDAD
    function verificarExcepciones($idAlumno, $idMateria) {
        $band = false;
        
        $objMateria = new Materia();
        $code = $objMateria->getMateriaById($idMateria)['codigo'];


        
        if ($code==1111) { //INICIAL
            $arm = new AlumnoRindeMateria();
            $arr_materias_aprobadas = $arm->getMateriasRendidasByEstado($idAlumno,'Aprobo');
            $cant = 0;
            if (in_array(356,$arr_materias_aprobadas)) $cant++;
            if (in_array(354,$arr_materias_aprobadas)) $cant++;
            if (in_array(357,$arr_materias_aprobadas)) $cant++;
            if (in_array(355,$arr_materias_aprobadas)) $cant++;
            if (in_array(353,$arr_materias_aprobadas)) $cant++;
         
            if ($cant>=3) {
                      return TRUE;
            } else return FALSE;

        } else if ($code==2222) { //PRIMARIA
            $arm = new AlumnoRindeMateria();
            $arr_materias_aprobadas = $arm->getMateriasRendidasByEstado($idAlumno,'Aprobo');
            $cant = 0;
            if (in_array(314,$arr_materias_aprobadas)) $cant++;
            if (in_array(315,$arr_materias_aprobadas)) $cant++;
            if (in_array(316,$arr_materias_aprobadas)) $cant++;
            if (in_array(317,$arr_materias_aprobadas)) $cant++;
            if (in_array(318,$arr_materias_aprobadas)) $cant++;
            if (in_array(319,$arr_materias_aprobadas)) $cant++;
         
            if ($cant>=3) {
                      return TRUE;
            } else return FALSE;
        } else {
            return TRUE;
        }



        //1 si materia = id_materia = 325 // Taller de Practica II - Seminario de lo grupal y los grupos en el aprendizaje - Primaria 
        // AL MENOS 3 DE LOS TALLERES DE PRACTICA DE 1ER AÑO DEL CAMPO DE LA FORMACION ESPECIFICA
        // (Comunicación y Expresión Oral y Escrita (314) - Resolución de Problemas y creatividad (315) Ciencias Naturales para una cultura ciudadana 
        //  Problemáticas de las Ciencias Sociales - Área Estético Expresiva I )



    }


    // *******************************************************************************
    //  Retorna el arreglo de Materias en la que puede Inscribir a Cursar.
    // *******************************************************************************
    public function getArregloMateriasVerificadasParaInscribirse($idAlumno,$idCarrera)
    {
        
        $arr_materias_candidatas_para_inscribirse = $this->getMateriasCandidatasParaInscripcion($idAlumno,$idCarrera);
        $arr_materias_verificadas_para_inscribirse = [];
        foreach ($arr_materias_candidatas_para_inscribirse as $item) {
                if ($this->getVerificaMateriaCorrelativa($idAlumno,$item)) {
                    $arr_materias_verificadas_para_inscribirse[] = $item;
                }
        }

        return $arr_materias_verificadas_para_inscribirse;
    }

    
    public function getArregloMateriasVerificadasParaInscribirseDetalles($alumno_id,$carrera_id)
    {
        $arr_materias_carrera = [];
        $alumno_cursa_materia = new AlumnoCursaMateria();
        $max_anio_que_cursa =  $alumno_cursa_materia->getAlumnoCursaMateriasByMaximoAnioCursadoByIdCarrera($alumno_id,$carrera_id);
        $max_anio = $max_anio_que_cursa;
        $carrera = new Carrera();
        $arr_materias_carrera = $carrera->getMateriasPorIdCarrera($carrera_id);
        $arr_materias_verificadas_inscripcion = $this->getArregloMateriasVerificadasParaInscribirse($alumno_id,$carrera_id);
        $arr_materias_verificadas_inscripcion_detalles = [];

        foreach ($arr_materias_carrera as $value) {
            if (in_array($value['materia_id'],$arr_materias_verificadas_inscripcion)) {
                //echo "Carrera: ".$value['carrera']." | Materia: ".$value['nombre']." (".$value['materia_id'].") | Año: ".$value['anio']."<br>";
                $arr_value_detalles = [];
                $arr_value_detalles['carrera'] = $value['carrera'];
                $arr_value_detalles['nombre'] = $value['nombre'];
                $arr_value_detalles['materia_id'] = $value['materia_id'];
                $arr_value_detalles['anio'] = $value['anio'];
                if ($value['anio']<=$max_anio+1) {
                    $arr_materias_verificadas_inscripcion_detalles[] = $arr_value_detalles;
                };
                
            };
        }

        return $arr_materias_verificadas_inscripcion_detalles;
    }  
   


}


/*
$tb = new InscripcionCursarMaterias();
$idAlumno = 471;
$idCarrera = 12;

echo ArrayCustom::show($tb->getArregloMateriasVerificadasParaInscribirseDetalles($idAlumno,$idCarrera),'Matriz');
//echo ArrayCustom::show($tb->getMateriasCandidatasParaInscripcion($idAlumno,$idCarrera),'Vector');
die;*/

