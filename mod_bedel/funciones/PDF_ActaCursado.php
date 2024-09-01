<?php
set_include_path('../../app/lib/'.PATH_SEPARATOR.'../../conexion/');
set_include_path('../../app/models/'.PATH_SEPARATOR.'../../app/lib/'.PATH_SEPARATOR.'../../conexion/');
require_once('AlumnoRindeMateriaDetalle.php');
require_once('Carrera.php');
require_once('Materia.php');
require_once('MyPdf.class.php');

// create new PDF document
$pdf = new MyPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', 'BI', 12);

// add a page
$pdf->AddPage();

$parametros=$_GET['parametros'];
$arrCarreraTurnoLlamado=explode('_',$parametros);
$idCarrera=$arrCarreraTurnoLlamado[0];
$idCalendario=$arrCarreraTurnoLlamado[1];
$llamado=$arrCarreraTurnoLlamado[2];
$idMateria=$arrCarreraTurnoLlamado[3];

$alumnos_rinden_materia = new AlumnoRindeMateriaDetalle();
$ARRAY_ALUMNOS_RINDEN_MATERIA = $alumnos_rinden_materia->getAlumnosByIdMateriaByIdCalendarioDetalle($idMateria,$idCalendario,$llamado);

$carrera = new Carrera();
$carrera_nombre = $carrera->getCarreraById($idCarrera)['descripcion_corta'];

$materia = new Materia();
$materia_nombre = $materia->getMateriaById($idMateria)['nombre'];
$materia_anio = $materia->getMateriaById($idMateria)['anio'];
$materia_anio_nombre = "";
if ($materia_anio==1) $materia_anio_nombre=' PRIMERO';
else if ($materia_anio==2) $materia_anio_nombre=' SEGUNDO';
else if ($materia_anio==3) $materia_anio_nombre=' TERCERO';
else if ($materia_anio==4) $materia_anio_nombre=' CUARTO';

//var_dump($carrera->getCarreraById($idCarrera)['descripcion_corta']);die;
//var_dump($materia_nombre);die;
//die($idCarrera.'*'.$idCalendario.'*'.$idMateria.'*'.$llamado);


$pdf->Ln(15);
//Arial bold 15
$pdf->SetFont('courier','B',10);
    //Movernos a la derecha
   //   $this->Cell(80);
    //T�tulo
$pdf->SetY(25);    
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(190,7,'PRIMER AÑO',0,1,'C',true);

$pdf->Ln(1);
$pdf->SetFillColor(204,204,204);
$pdf->SetTextColor(0,0,0);
// Segundo Grupo
    
$dd=date('d');
$mm=date('m');
$yy=date('Y');

/*$pdf->SetX(11);
$pdf->SetFillColor(255,255,255);	
$pdf->Cell(52,7,'EVALUACIONES DE ALUMNOS:',0,0,'L',false);
$pdf->SetFont('courier','',10);
$pdf->Cell(105,7,'REGULARES FINALES',0,0,'L',TRUE);
$pdf->SetFont('courier','B',10);
$pdf->Cell(10,7,'DIA',1,0,'L',TRUE);
$pdf->Cell(10,7,'MES',1,0,'L',TRUE);
$pdf->Cell(10,7,'AÑO',1,1,'L',TRUE);*/


/*
$pdf->SetX(11);
$pdf->SetFillColor(255,255,255);	

$pdf->Cell(25,7,'ASIGNATURAS',0,0,'L',false);
$pdf->SetFont('courier','',10);
$pdf->Cell(132,7,utf8_decode($materia_nombre),0,0,'L',TRUE);
$pdf->Cell(10,9,$dd,1,0,'L',TRUE);
$pdf->Cell(10,9,$mm,1,0,'L',TRUE);
$pdf->Cell(10,9,$yy,1,1,'L',TRUE);*/

$pdf->setX(7);
$pdf->SetFillColor(255,255,255);	
$pdf->SetFont('courier','B',10);
$pdf->Cell(60,14,'ASIGNATURAS',1,0,'C',TRUE);
$pdf->Cell(21,14,'CURSADO',1,0,'C',TRUE);
$pdf->Cell(15,7,'P.D',1,0,'C',TRUE);
$pdf->Cell(24,7,'Regularizó',1,0,'C',TRUE);
$pdf->Cell(31,7,'EXÁMEN FINAL',1,0,'C',TRUE);
$pdf->Cell(45,14,'OBSERVACIONES',1,1,'C',TRUE);
$pdf->setXY(88,40);
$pdf->Cell(15,7,' Prom.D.',1,0,'C',TRUE);
$pdf->Cell(15,7,'Nota',1,0,'C',TRUE);
$pdf->Cell(9,7,'Año',1,0,'C',TRUE);
$pdf->Cell(15,7,'Nota',1,0,'C',TRUE);
$pdf->Cell(16,7,'Fecha',1,0,'C',TRUE);

$pdf->setXY(7,47);
$pdf->SetFont('times', '', 9);
$pdf->Cell(60,7,'Historia Argentina y Latinoamericana',1,0,'L',TRUE);
$pdf->Cell(21,7,'Semipresencial',1,0,'C',TRUE);
$pdf->Cell(15,7,'4 (Cuatro)',1,0,'C',TRUE);
$pdf->Cell(15,7,'4 (Cuatro)',1,0,'C',TRUE);
$pdf->Cell(9,7,'2023',1,0,'C',TRUE);
$pdf->Cell(15,7,'4 (Cuatro)',1,0,'C',TRUE);
$pdf->Cell(16,7,'2024/02/24',1,0,'C',TRUE);
$pdf->Cell(45,7,'Observaciones',1,1,'C',TRUE);
$pdf->setX(7);
$pdf->Cell(60,7,'Historia Argentina y Latinoamericana',1,0,'L',TRUE);
$pdf->Cell(21,7,'Semipresencial',1,0,'C',TRUE);
$pdf->Cell(15,7,'4 (Cuatro)',1,0,'C',TRUE);
$pdf->Cell(15,7,'4 (Cuatro)',1,0,'C',TRUE);
$pdf->Cell(9,7,'2023',1,0,'C',TRUE);
$pdf->Cell(15,7,'4 (Cuatro)',1,0,'C',TRUE);
$pdf->Cell(16,7,'2024/02/24',1,0,'C',TRUE);
$pdf->Cell(45,7,'Observaciones',1,0,'C',TRUE);

/*
$pdf->SetX(11);
$pdf->Cell(25,7,'ASIGNATURA:',0,0,'L',false);
$pdf->SetFont('courier','',10);
$pdf->Cell(132,7,utf8_decode($materia_nombre),0,0,'L',TRUE);
$pdf->Cell(10,9,$dd,1,0,'L',TRUE);
$pdf->Cell(10,9,$mm,1,0,'L',TRUE);
$pdf->Cell(10,9,$yy,1,1,'L',TRUE);*/


/*$pdf->SetX(11);
$pdf->SetFont('courier','B',10);
$pdf->Cell(18,7,'CARRERA:',0,0,'L',false);
$pdf->SetFont('courier','',10);
$pdf->Cell(65,7,utf8_decode($carrera_nombre),0,0,'L',false);
$pdf->SetFont('courier','B',10);
$pdf->Cell(8,7,'AÑO:',0,0,'L',false);
$pdf->SetFont('courier','',10);
$pdf->Cell(25,7,$materia_anio_nombre,0,0,'L',false);
$pdf->SetFont('courier','B',10);
$pdf->Cell(20,7,'DIVISION:',0,0,'L',false);
$pdf->SetFont('courier','',10);
$pdf->Cell(16,7,'UNICA',0,0,'L',false);
$pdf->SetFont('courier','B',10);
$pdf->Cell(14,7,'TURNO:',0,0,'L',false);
$pdf->SetFont('courier','',10);
$pdf->Cell(18,7,'VESPERTINO',0,1,'L',false);

$pdf->SetX(11);
$pdf->SetFont('courier','',8);
$pdf->Cell(15,10,'Orden',1,0,'C',true);
$pdf->Cell(15,10,'Permiso',1,0,'C',true);
$pdf->Cell(32,10,'DOC. DE IDENTIDAD',1,0,'C',true);
$pdf->Cell(68,10,'APELLIDO Y NOMBRES',1,0,'C',true);
$pdf->Cell(30,10,'EVALUACIONES',1,0,'C',true);
$pdf->Cell(30,10,'OBSERVACIONES',1,1,'C',true);
*/
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Acta_Volante.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+