<?php
set_include_path('../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel2.php";
require_once "AlumnoCursaMateria.php";
require_once "Carrera.php";
require_once "Materia.php";
require_once "ActasInscripcionCursadoPdf.class.php";
//*******************TOKEN  *****************************/
$array_resultados = [];
$token = (isset($_GET['token']))?$_GET['token']:false;
$array_resultados = [];
if ($token!=$_SESSION['token']) {
  $array_resultados['codigo'] = 500;
  $array_resultados['class'] = 'danger';
  $array_resultados['mensaje'] = 'El Token es INCORRECTO.';
  echo json_encode($array_resultados);die;
}
//****************************************************** */
// create new PDF document
$pdf = new ActasInscripcionCursadoPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Escuela Normal Superior 40 - Mariano Moreno');
$pdf->SetTitle('Acta Volante');
$pdf->SetSubject('Inscripcion al Cursado');
$pdf->SetKeywords('Acta,Iscripcion Cursado,Cursado');

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

$param = json_decode(base64_decode($_GET['parametros']));
//var_dump($param);exit;

$idMateria = $param[1];
$idCarrera = $param[0][0];
$anio_vigente = $param[0][1];
$fecha_acta = $param[0][2];

$dia = substr($fecha_acta,0,2);
$mes = substr($fecha_acta,3,2);
$anio = substr($fecha_acta,6,4);
$fecha_formateada = $anio . '-' . $mes . '-' . $dia;
$dd = date('d',strtotime($fecha_formateada));
$mm = date('m',strtotime($fecha_formateada));
$yy = date('Y',strtotime($fecha_formateada));

$pdf->setDia($dd);
$pdf->setMes($mm);
$pdf->setAnio($yy);

$alumnos_cursan_materia = new AlumnoCursaMateria();
$ARRAY_ALUMNOS_CURSAN_MATERIA = $alumnos_cursan_materia->getAlumnosCursanByIdMateriaDetalle($idMateria,$anio_vigente);
//var_dump($ARRAY_ALUMNOS_CURSAN_MATERIA);exit;
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

$pdf->Ln(15);
//Arial bold 15
$pdf->SetFont('courier','B',10);
    //Movernos a la derecha
   //   $this->Cell(80);
    //T�tulo
$pdf->SetY(25);    
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(190,7,'ACTA INSCRIPCIONES A CURSADO',0,1,'R',true);

$pdf->Ln(1);
$pdf->SetFillColor(204,204,204);
$pdf->SetTextColor(0,0,0);
// Segundo Grupo
    
$pdf->SetX(11);
$pdf->SetFillColor(255,255,255);	
$pdf->Cell(52,7,'INFORMACIÓN DE ALUMNOS:',0,0,'L',false);
$pdf->SetFont('courier','',10);
$pdf->Cell(105,7,'INSCRIPCIÓN A CURSADO',0,0,'L',TRUE);
$pdf->SetFont('courier','B',10);
$pdf->Cell(10,7,'DIA',1,0,'L',TRUE);
$pdf->Cell(10,7,'MES',1,0,'L',TRUE);
$pdf->Cell(10,7,'AÑO',1,1,'L',TRUE);
$pdf->SetX(11);
$pdf->Cell(25,7,'ASIGNATURA:',0,0,'L',false);
$pdf->SetFont('courier','',10);
$pdf->Cell(132,7,$materia_nombre,0,0,'L',TRUE);
$pdf->Cell(10,9,$dd,1,0,'L',TRUE);
$pdf->Cell(10,9,$mm,1,0,'L',TRUE);
$pdf->Cell(10,9,$yy,1,1,'L',TRUE);

$pdf->SetX(11);
$pdf->SetFont('courier','B',10);
$pdf->Cell(18,7,'CARRERA:',0,0,'L',false);
$pdf->SetFont('courier','',10);
$pdf->Cell(65,7,$carrera_nombre,0,0,'L',false);
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
$pdf->Cell(32,10,'DOC. DE IDENTIDAD',1,0,'C',true);
$pdf->Cell(78,10,'APELLIDO Y NOMBRES',1,0,'C',true);
$pdf->Cell(30,10,'NOTAS',1,0,'C',true);
$pdf->Cell(30,10,'MODALIDAD',1,1,'C',true);
$cant_desaprobados = $cant_aprobados = $cant_ausentes = $i = 0;
//var_dump($ARRAY_ALUMNOS_RINDEN_MATERIA);exit;

foreach ($ARRAY_ALUMNOS_CURSAN_MATERIA as $item) {
    //if ($item['condicion']=='Regular') {
            $i++;
            $pdf->SetX(11);
            if ($item['nota']==-1) {$nota='-';$determ='AUSENTE';$cant_ausentes++;}
            elseif ($item['nota']==0) {$nota='0';$determ='(Cursando)';}
            elseif ($item['nota']==1) {$nota='1';$determ='(Uno)';$cant_desaprobados++;}
            elseif ($item['nota']==2) {$nota='2';$determ='(Dos)';$cant_desaprobados++;}
            elseif ($item['nota']==3) {$nota='3';$determ='(Tres)';$cant_desaprobados++;}
            elseif ($item['nota']==4) {$nota='4';$determ='(Cuatro)';$cant_desaprobados++;}
            elseif ($item['nota']==5) {$nota='5';$determ='(Cinco)';$cant_desaprobados++;}
            elseif ($item['nota']==6) {$nota='6';$determ='(Seis)';$cant_aprobados++;}
            elseif ($item['nota']==7) {$nota='7';$determ='(Siete)';$cant_aprobados++;}
            elseif ($item['nota']==8) {$nota='8';$determ='(Ocho)';$cant_aprobados++;}
            elseif ($item['nota']==9) {$nota='9';$determ='(Nueve)';$cant_aprobados++;}
            elseif ($item['nota']==10) {$nota='10';$determ='(Diez)';$cant_aprobados++;}

            $pdf->Cell(15,5,$i,1,0,'C',false);
            $pdf->Cell(32,5,$item['dni'],1,0,'C',false);
            $pdf->Cell(78,5,$item['apellido'].', '.$item['nombre'],1,0,'L',false);
            $pdf->Cell(15,5,$nota,1,0,'C',false);
            $pdf->Cell(15,5,$determ,1,0,'C',false);
            $pdf->Cell(30,5,$item['condicion'],1,1,'R',false);
    //}
};

$pdf->setAprobados($cant_aprobados);
$pdf->setDesaprobados($cant_desaprobados);
$pdf->setAusentes($cant_ausentes);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('AV_' . $carrera_nombre . '_' . $materia_nombre . '_' . $materia_anio_nombre . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+