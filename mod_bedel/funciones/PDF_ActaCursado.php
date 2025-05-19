<?php
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/');



require_once "ActasInscripcionCursadoIndividualPdf.class.php";

require_once "Carrera.php";
require_once "Alumno.php";
require_once "AlumnoCursaMateria.php";
require_once "AlumnoRindeMateria.php";


//require_once('Materia.php');


// create new PDF document
$pdf = new ActasInscripcionCursadoIndividualPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Escuela Normal Superior 40 - Mariano Moreno');
$pdf->SetTitle('Acta Volante');
$pdf->SetSubject('Examenes Finales');
$pdf->SetKeywords('Acta, Volante, Finales');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set font
$pdf->SetFont('times', 'BI', 12);
$pdf->SetFillColor(204,204,204);
$pdf->SetTextColor(0,0,0);
/******************************************************************************************************************************************************* */
//$parametros=$_GET['parametros'];
$idCarrera = $_GET['idCarrera'];
$idAlumno = $_GET['idAlumno'];

$alumno = new Alumno();
$arr_datos_alumno = $alumno->getById($idAlumno);

$carrera = new Carrera();
$arr_datos_carrera = $carrera->getCarreraById($idCarrera);

$alumno_rinde_materia = new AlumnoRindeMateria();
$arr_aprobadas = $alumno_rinde_materia->getMateriasRendidasByEstadoDetalle($idAlumno,'Aprobo');

$alumno_cursa_materia = new AlumnoCursaMateria();
$arr_regulares_libre = array_merge($alumno_cursa_materia->getMateriasCursadasByEstadoConDetalles($idAlumno,'Regularizo'),$alumno_cursa_materia->getMateriasCursadasByEstadoConDetalles($idAlumno,'Libre'),$alumno_cursa_materia->getMateriasCursadasByEstadoConDetalles($idAlumno,'Promociono',FALSE),$alumno_cursa_materia->getMateriasCursadasByEstadoConDetalles($idAlumno,'Aprobo',FALSE),$alumno_cursa_materia->getMateriasCursadasByEstadoConDetalles($idAlumno,'Cursando',FALSE));

$carrera = new Carrera();
$arr_materias_carrera = $carrera->getMateriasPorIdCarrera(15);
$cantidad_anio_carrera = $carrera->getCantidadAniosCarrera(15);

$pdf->Ln();
$pdf->setXY(117,500);
for ($i=1;$i<=$cantidad_anio_carrera;$i++) {
    $pdf->setAnio($i);
    $pdf->setPagina($i);
    $pdf->setAlumno(["apellido"=>$arr_datos_alumno['apellido'],"nombre"=>$arr_datos_alumno['nombre'],"documento"=>$arr_datos_alumno['dni'],"carrera"=>$arr_datos_carrera['descripcion']]);

    $pdf->AddPage();
    if ($pdf->getPagina()==1) {
        $pdf->setXY(7,49);
    } else {
        $pdf->setXY(7,31);
    }
    

    foreach ($arr_materias_carrera as $value) {
        $materia_id = 0;
        $materia_nombre = "";
        $materia_anio = 0;
        $materia_cursado = "";
        $materia_anio_cursado = "";
        $materia_nota_regularidad = "";
        $materia_nota_final = "";
        $materia_estado = "";
        $materia_estado_final = "";
        $materia_fecha_vencimiento = "";
        $materia_fecha_aprobacion = "";
        $materia_id = $value['materia_id'];
        $materia_nombre = $value['nombre'];
        $materia_anio = $value['anio'];
        $materia_cursadas_detalles = $alumno_cursa_materia->getDetallesCursado($materia_id,$arr_regulares_libre);
        
        $materia_aprobadas_detalles = $alumno_rinde_materia->getDetallesAprobada($materia_id,$arr_aprobadas);
        if (!empty($materia_cursadas_detalles)) {
            $materia_nota_regularidad = $materia_cursadas_detalles['nota'];
            $materia_estado = $materia_cursadas_detalles['estado_final'];
            $materia_fecha_vencimiento = $materia_cursadas_detalles['fecha_vencimiento_regularidad'];
            $materia_cursado = $materia_cursadas_detalles['cursado'];
            $materia_anio_cursado = $materia_cursadas_detalles['anio_cursado'];
            $materia_estado_final = ($materia_cursado=='Libre')?'Libre':'Aprobo';
        } else {
            $materia_nota_regularidad = "";
        }

        if (!empty($materia_aprobadas_detalles)) {
            $materia_nota_final = $materia_aprobadas_detalles['nota'];
            $materia_estado_final = 'Aprobo';
            $materia_fecha_aprobacion = substr($materia_aprobadas_detalles['fecha_modificacion_nota'],0,10);
        } else {
            $materia_nota_final = "";
        }

        if ($materia_anio==$i) {
                $pdf->SetFont('times', '', 9);
                $pdf->Cell(75,7,$materia_nombre,1,0,'L',TRUE);
                $pdf->Cell(21,7,$materia_cursado,1,0,'C',TRUE);
                $pdf->Cell(18,7,$materia_estado,1,0,'C',TRUE);
                $pdf->Cell(13,7,$materia_nota_regularidad,1,0,'C',TRUE);
                $pdf->Cell(10,7,$materia_anio_cursado,1,0,'C',TRUE);
                $pdf->Cell(13,7,$materia_nota_final,1,0,'C',TRUE);
                $pdf->Cell(17,7,$materia_fecha_aprobacion,1,0,'C',TRUE);
                $pdf->Cell(29,7,'',1,1,'C',TRUE);
                $pdf->setX(7);
        };
        
    }
    
}

//Close and output PDF document
$pdf->Output($arr_datos_carrera['descripcion'].'-'.$arr_datos_alumno['apellido'].'_'.$arr_datos_alumno['nombre'].'-'.$arr_datos_alumno['dni'].'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+