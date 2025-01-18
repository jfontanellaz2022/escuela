<?php

require_once('AlumnoCursaMateria.php');
require_once('AlumnoRindeMateria.php');
require_once('CorrelativasParaRendir.php');
require_once('Carrera.php');

class InscripcionRendirMaterias
{

private $arr_materias_tipo_cursado = [];

    //********************************************************************************************************/
    //Posibles materias que puede rendir. Son candidatas porque no se aplico el control de las correlativas
    //Materias Regulares o Libres qye no estan aprobadas
    //********************************************************************************************************/
    public function getMateriasCandidatasParaInscripcion($idAlumno,$idCarrera)
    {
        $arr_resultado = [];

        $arm = new AlumnoRindeMateria();
        $acm = new AlumnoCursaMateria();
        $arr_regulares_libres = array_merge($acm->getMateriasCursadasByEstado($idAlumno,'Regularizo',FALSE),$acm->getMateriasCursadasByEstado($idAlumno,'Libre',FALSE));
        $this->arr_materias_tipo_cursado=array_merge($acm->getMateriasCursadasByEstadoConDetallesTipoCursado($idAlumno,'Regularizo',FALSE),$acm->getMateriasCursadasByEstadoConDetallesTipoCursado($idAlumno,'Libre',FALSE));
        
        //var_dump($this->arr_materias_tipo_cursado);die;
        $arr_materias_aprobadas = $arm->getMateriasRendidasByEstado($idAlumno,'Aprobo');

        $arr_materias_regulares_libres_no_aprobadas = [];
        //var_dump($arr_regulares_libres);die;
        foreach ($arr_regulares_libres as $item) {
            if (!in_array($item,$arr_materias_aprobadas)) {
                    $arr_resultado[] = $item;
            }
        }
        return $arr_resultado;
    }

    public function getMateriasCandidatasPorTipoCursado()
    {
        return $this->arr_materias_tipo_cursado;
    }

    
    // Verifica que una materia cumpla  o no con las correlativas. Para Cursar o para Rendir
    // de acuerdo a las materias aprobadas y/o regulares de un alumno
     
    public function getVerificaMateriaCorrelativa($idAlumno,$idMateria)
    {
        $arm = new AlumnoRindeMateria();
        $acm = new AlumnoCursaMateria();
        $correlativas = new CorrelativasParaRendir();
        
       
        $band_aprobadas = $band_regulares = TRUE; 
        $arr_materias_correlativas_requeridas = $correlativas->getMateriasCorrelativasByIdMateria($idMateria);
        //var_dump($arr_materias_correlativas_requeridas);exit;
    
        

        $arr_materias_correlativas_aprobadas_requeridas = $arr_materias_correlativas_requeridas['aprobadas'];
        $arr_materias_correlativas_regulares_requeridas = $arr_materias_correlativas_requeridas['regulares'];
        

        $arr_materias_aprobadas = $arm->getMateriasRendidasByEstado($idAlumno,'Aprobo');      
        $arr_materias_regulares = $acm->getMateriasCursadasByEstado($idAlumno,'Regularizo');

        //Determina si el arreglo de correlativas regulares libres es vacio o no.
        //En el caso que sea Vacio $band_regulares_libres = TRUE
        //Si no es Vacio se recorre dicho arreglo y se verifica contra el arreglo de las materias regulares y libres del alumno
        //Si encuentra que un item del primer arreglo no esta el el segundo y $band_regulares_libres = FALSE (no cumpliria con correlativas)
        if (!empty($arr_materias_correlativas_regulares_requeridas)) {
            foreach ($arr_materias_correlativas_regulares_requeridas as $item_regulares_requerida) {
                if (!in_array($item_regulares_requerida,$arr_materias_regulares)) {
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
                    $band_aprobadas = FALSE; 
                    break;
                }
            };
        };

       //die("aprobadas: ".$band_aprobadas." Regulares: ".$band_regulares_libres);
       // die($band_regulares_libres && $band_aprobadas);
        return ($band_regulares && $band_aprobadas);
    }


    // *******************************************************************************
    //  Retorna el arreglo de Materias en la que puede Inscribir a Rendir o a Cursar.
    // *******************************************************************************
    public function getArregloMateriasVerificadasParaInscribirse($idAlumno,$idCarrera)
    {
        
        $arr_materias_candidatas_para_inscribirse = $this->getMateriasCandidatasParaInscripcion($idAlumno,$idCarrera);
        //die('test 2.1');
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
        $carrera = new Carrera();
        $arr_materias_carrera = $carrera->getMateriasPorIdCarrera($carrera_id);
        $arr_materias_verificadas_inscripcion = $this->getArregloMateriasVerificadasParaInscribirse($alumno_id,$carrera_id);
        $arr_materias_verificadas_inscripcion_detalles = [];

        //var_dump($arr_materias_carrera);die;
        foreach ($arr_materias_carrera as $value) {
            if (in_array($value['materia_id'],$arr_materias_verificadas_inscripcion)) {
                //echo "Carrera: ".$value['carrera']." | Materia: ".$value['nombre']." (".$value['materia_id'].") | Año: ".$value['anio']."<br>";
                $arr_value_detalles = [];
                $arr_value_detalles['carrera'] = $value['carrera'];
                $arr_value_detalles['nombre'] = $value['nombre'];
                $arr_value_detalles['materia_id'] = $value['materia_id'];
                $arr_value_detalles['anio'] = $value['anio'];
                $arr_value_detalles['cursado_id'] = $value['cursado_id'];
                $arr_value_detalles['promocionable'] = $value['promocionable'];
                $arr_value_detalles['formato_id'] = $value['formato_id'];
                $arr_materias_verificadas_inscripcion_detalles[] = $arr_value_detalles;
            };
        }
        //var_dump($arr_value_detalles);exit;
        return $arr_materias_verificadas_inscripcion_detalles;

    } 
   


}


/*$tb = new InscripcionRendirMaterias();
$idAlumno = 646;
$idCarrera = 15;

var_dump($tb->getArregloMateriasVerificadasParaInscribirseDetalles($idAlumno,$idCarrera));*/

