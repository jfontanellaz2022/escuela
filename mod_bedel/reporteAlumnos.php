<?php
set_include_path('../lib/'.PATH_SEPARATOR.'../conexion/'.PATH_SEPARATOR.'./');
include_once 'seguridad.php';
include_once 'conexion.php';
include_once 'Sanitize.class.php';
include_once 'ArrayHash.class.php';

$x0 = 20;
$carrera = $_REQUEST['p'];
$datos = explode('&',base64_decode($_REQUEST['p'])); //("periodo","importeOriginal","importeInteres","importeTotal","fechaVencimiento")

$sql = "SELECT p.apellido,p.nombre,p.dni,p.email,p.telefono 
        FROM alumno_estudia_carrera aec, alumno a, persona p 
		WHERE aec.idCarrera = $carrera and 
		      aec.anio = 2023 and 
			  aec.idAlumno = a.id and 
			  a.habilitado = 'Si' and 
			  a.dni = p.dni";

$resultado = mysqli_query($conex,$sql);
if (!$resultado) {
	 die("Error: Hubo un error");
};

//============================================================+
// File name   : example_027.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 027 for TCPDF class
//               1D Barcodes
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: 1D Barcodes.
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
//require_once('tcpdf_include.php');
require_once('MyPdf.class.php');
// create new PDF document
$pdf = new MyPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$primerFilaY = 30.65;
$altoCeldaEncabezado = 4.60;
$pseudoMargen = 12;



$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Caja de Jubilaciones y Pensiones de la Provincia de Santa Fe');
$pdf->setSubject('Declaraciones Juradas');
$pdf->setKeywords('');
$pdf->setAutoPageBreak(0); // cantidad de paginas del listado
//$pdf->SetY(37);


// set default header data
$pdf->SetHeaderData('escudo.jpg', 30, 'Caja de Jubilaciones y Pensiones', 'Provincia de Santa Fe');
//$pdf->SetHeaderData('/images/escudo.jpg', 100, 'string to print as title on document header', 'string to print on document header');


// set header and footer fonts
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// ---------------------------------------------------------
$pdf->SetFont('helvetica', '', 11);

// add a page
$pdf->AddPage();
/* ACA SE DIBUJAN LOS BOX DE LA BOLETA DE PAGO */
$pdf->SetY(30);
$pdf->setFontSize(12);
$pdf->SetFont('helvetica', 'B');
$pdf->cell(190, $altoCeldaEncabezado + 0.16, "REPORTE DE INGRESANTES", 0, 1, 'C');
$pdf->cell(190, $altoCeldaEncabezado + 0.16, "", 0, 1, 'C');

/* 1er grupo */
$pdf->SetY(45);
$pdf->SetFont('Times','B',12);
$pdf->Cell(8,7,"",1,0,'C',0);
$pdf->Cell(60,7,"APELLIDO Y NOMBRE",1,0,'C',0);
$pdf->Cell(23,7,"DNI",1,0,'C',0);
$pdf->Cell(60,7,"EMAIL",1,0,'C',0);
$pdf->Cell(30,7,"TELEFONO",1,1,'C',0);
$pdf->SetFont('Times','',11);

/************************************* ITERAR PARA IR ARMANDO LAS FILAS ************************************************/
$c=0;
while ($fila = mysqli_fetch_assoc($resultado)) {
	$c++;
	$pdf->Cell(8,7,$c,1,0,'C',0);
	$pdf->Cell(60,7,$fila['apellido'].', '.$fila['nombre'],1,0,'L',0);
	$pdf->Cell(23,7,$fila['dni'],1,0,'C',0);
	$pdf->Cell(60,7,$fila['email'],1,0,'L',0);
	$pdf->Cell(30,7,$fila['telefono'],1,1,'L',0);
	$pdf->SetFillColor(220,220,220);
}

// define barcode style
//Close and output PDF document
$pdf->Output();

//============================================================+
// END OF FILE
//============================================================+
