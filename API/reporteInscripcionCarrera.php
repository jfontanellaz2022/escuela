<?php

set_include_path('../app/lib/'.PATH_SEPARATOR.'../app/lib/controllers/'.PATH_SEPARATOR.'./');
define('ROOT_DIR1',realpath('../app/controllers'));
require_once "ActasInscripcionCarreraPdf.class.php";
require_once (ROOT_DIR1 . '/ReporteController.php');

//*******************TOKEN  *****************************/
$array_resultados = [];
$token = (isset($_GET['token']))?$_GET['token']:false;
$array_resultados = [];
/*
if ($token!=$_SESSION['token']) {
  $array_resultados['codigo'] = 500;
  $array_resultados['class'] = 'danger';
  $array_resultados['mensaje'] = 'El Token es INCORRECTO.';
  echo json_encode($array_resultados);die;
}*/
//****************************************************** */
$arr_param = explode('&',base64_decode($_GET['p']));
$anio_lectivo = $arr_param[0];
$dni = $arr_param[1];
$carrera_id = $arr_param[2];


$obj = new ReporteController();
$arr_datos = $obj->getReporteInscripcion($dni,$anio_lectivo,$carrera_id);

$fechai = $arr_datos['FechaInscripcion'];
$fi = explode('-',$fechai);
$fechan =$arr_datos['FechaNacimiento'];
$fn = explode('-',$fechan);

// create new PDF document
$pdf = new ActasInscripcionCarreraPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Escuela Normal Superior 40 - Mariano Moreno');
$pdf->SetTitle('Inscripcion');
$pdf->SetSubject('Inscripcion');

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

// add a page
$pdf->AddPage();
// ---------------------------------------------------------


$pdf->SetFont('times', 'B', 10);
$pdf->SetFont('times', 'B', 10);
$pdf->Cell(200, 10, 'SOLICITUD DE INSCRIPCIÓN',  0, 1, 'C');
$pdf->Ln(3);

//ESTABLECIMIENTO
$pdf->SetFont('times', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(20, 10,'ESTABLECIMIENTO:', 0, 0, 'L');
$pdf->SetFont('times', 'I', 8);
$pdf->Cell(91, 10,'"ESCUELA NORMAL SUPERIOR Nº 40 "MARIANO MORENO"',  0, 0, 'R');

//NIVEL
$pdf->SetFont('times', 'U', 8.5);
$pdf->Cell(20, 10, 'NIVEL:',  0, 0, 'R');
$pdf->SetFont('times', 'I', 8);
$pdf->Cell(18, 10, 'TERCIARIO ',  0, 1, 'R');

//CICLO LECTIVO
$pdf->SetFont('times', 'U', 8);
$pdf->Cell(10);
$pdf->Cell(24, 10, 'CICLO LECTIVO:',  0, 0, 'R');
$pdf->SetFont('times', 'I', 8);
$pdf->Cell(25, 10, $anio_lectivo.' ',  0, 0, 'L');

//FECHA DE INSCRIPCION
$pdf->SetFont('times', 'U', 8);
$pdf->Cell(105, 10,'FECHA DE INSCRIPCIÓN:',  0, 0, 'R'); 
$pdf->SetFont('times', 'I', 8);
$pdf->Cell(0, 10,$fi[2].'/'.$fi[1].'/'.$fi[0],  0, 1, 'L');
$pdf->Ln(2);

//CARRERA
$pdf->SetFont('times', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(17, 10, 'CARRERA:',  0, 0, '');
$pdf->SetFont('times', 'I', 8);
$pdf->Cell(102, 10, $arr_datos['NombreCarrera'], 0, 0, 'L');


//Anio
$pdf->SetFont('times', 'U', 8.5);
$pdf->Cell(9, 10, 'AÑO:',  0, 0, 'L');
$pdf->SetFont('times', 'I', 8);
$pdf->Cell(5, 10, '1°',  0, 0, 'R');

//Division
$pdf->SetFont('times', 'U', 8.5);
$pdf->Cell(17, 10, 'DIVISIÓN: ',  0, 0, 'L');
$pdf->SetFont('times', 'I', 8);
$pdf->Cell(20, 10, 'ÚNICA',  0, 1, 'L');


//DATOS PERSONALES
$pdf->Ln(3);
$pdf->SetFont('times', 'B', 9);
$pdf->Cell(10);
$pdf->Cell(180, 10, 'DATOS PERSONALES',  0, 1, 'L');

//NOMBRE Y APELLIDO
$pdf->SetFont('times', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(36, 10, 'APELLIDO Y NOMBRES:',  0, 0, '');
$pdf->SetFont('times', 'I', 8);
$pdf->Cell(1, 10, ucfirst($arr_datos['Apellido']." ".$arr_datos['Nombre']), 0, 1, 'L');

//DNI
$pdf->SetFont('times', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(27, 10, 'Nº DOCUMENTO:',  0, 0, '');
$pdf->SetFont('times', 'I', 8);
$pdf->Cell(56, 10, $arr_datos['DNI'], 0, 0, 'L');

//FECHA DE NACIMIENTO
$pdf->SetFont('times', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(37, 10, 'FECHA DE NACIMIENTO:',  0, 0, '');
$pdf->SetFont('times', 'I', 8);
$pdf->Cell(37, 10,$fn[2].'/'.$fn[1].'/'.$fn[0], 0, 1, 'L');

//ESTADO CIVIL
$pdf->SetFont('times', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(24, 10, 'ESTADO CIVIL:',  0, 0, 'L');
$pdf->SetFont('times', 'I', 8);
$pdf->Cell(37, 10, ucfirst($arr_datos['EstadoCivil']), 0, 1, 'L');

//DOMICILIO
$pdf->SetFont('times', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(33, 10, 'DOMICILIO ACTUAL:',  0, 0, 'L');
$pdf->SetFont('times', 'I', 8);
$pdf->Cell(1, 10, ucfirst($arr_datos['Domicilio']), 0, 1, '');

//LOCALIDAD DE RESIDENCIA:
$pdf->SetFont('times', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(20, 10, 'LOCALIDAD:',  0, 0, 'L');
$pdf->SetFont('times', 'I', 8);
$pdf->Cell(1, 10, ucfirst($arr_datos['Localidad']), 0, 0, '');

//TELÉFONO
$pdf->SetFont('times', 'U', 8.5);
$pdf->Cell(91, 10, 'TELÉFONO:',  0, 0, 'R');
$pdf->SetFont('times', 'I', 8);
$pdf->Cell(1, 10, '(' . $arr_datos['Telefono_Caracteristica'] . ') ' . $arr_datos['Telefono_Numero'], 0, 1, '');

//CORREO ELECTRONICO
$pdf->SetFont('times', 'U', 8);
$pdf->Cell(10);
$pdf->Cell(36, 10, 'CORREO ELECTRÓNICO:',  0, 0, 'L');
$pdf->SetFont('times', 'I', 8);
$pdf->Cell(1, 10, $arr_datos['Email'], 0, 1, '');

//TITULO NIVEL MEDIO
$pdf->SetFont('times', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(34, 10, 'TITULO NIVEL MEDIO:',  0, 0, 'L');
$pdf->SetFont('times', 'I', 8);
$pdf->Cell(1, 10, ucfirst($arr_datos['Titulo']), 0, 1, '');

//EXPEDIDO POR
$pdf->SetFont('times', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(25, 10, 'EXPEDIDO POR:',  0, 0, 'L');
$pdf->SetFont('times', 'I', 8);
$pdf->Cell(1, 10, ucfirst($arr_datos['Escuela']), 0, 1, '');

//OCUPACION
$pdf->SetFont('times', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(20, 10, 'OCUPACIÓN:',  0, 0, 'L');
$pdf->SetFont('times', 'I', 8);
$pdf->Cell(1, 10, ucfirst($arr_datos['Ocupacion']), 0, 1, '');
$pdf->Ln(5);

//FIRMA Y ACLARACION
$pdf->SetFont('times', 'U', 8.5);
$pdf->Cell(10);
$pdf->Cell(220, 10, 'FIRMA Y ACLARACIÓN:',  0, 1, 'C');
$pdf->SetFont('times', 'I', 8);


//Close and output PDF document
$pdf->Output('ComprobanteInscripcionENS40.pdf');



?>
   