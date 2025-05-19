<?php
set_include_path('../app/utils/'.PATH_SEPARATOR.'../app/models/'.PATH_SEPARATOR.'../app/lib/'.PATH_SEPARATOR.'./');
require_once "seguridadNivel2.php";
require_once 'Materia.php';
require_once 'AlumnoCursaMateria.php';
require_once 'ReporteMateriaPdf.class.php';

// create new PDF document
$pdf = new MateriaPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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


$materia_id = isset($_REQUEST['materia_id'])?$_REQUEST['materia_id']:'';
$anio_cursado = isset($_REQUEST['anio_cursado'])?$_REQUEST['anio_cursado']:2024;


//die('entrooo');
$materia = new Materia;
$arr_materia = $materia->getMateriaById($materia_id);
//die('entrooo');
$materia_anio = "";
$materia_nombre = $arr_materia['nombre'];
$materia_carrera = $arr_materia['carrera'];

if ($arr_materia['anio']==1) {
    $materia_anio = "Primero";
} else if ($arr_materia['anio']==2) {
    $materia_anio = "Segundo";
} else if ($arr_materia['anio']==3) {
    $materia_anio = "Tercero";
} else if ($arr_materia['anio']==4) {
    $materia_anio = "Cuarto";
}



$alumnos_cursan_materia = new AlumnoCursaMateria;
$ARRAY_DATOS_INSCRIPCION_ALUMNO = $alumnos_cursan_materia->getAllAlumnosByMateria(['materia_id'=>$materia_id,'anio_cursado'=>$anio_cursado]);


// ******************************************** DATOS DEL PDF ***************************************************

$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(1);
$pdf->Cell(180, 10, 'PLANILLA COLECTORA DE NOTAS - PROMOCIONES/REGULARIDADES - ESPACIOS CURRICULARES',  0, 1, 'C');

//CARRERA
$pdf->SetFont('helvetica', 'BU', 8.5);
$pdf->Cell(17, 4, 'CARRERA:',  0, 0, 'L');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(17, 4, ' ' . $materia_carrera, 0, 0, 'L');

//MATERIA
$pdf->Ln();
$pdf->SetFont('helvetica', 'BU', 8.5);
$pdf->Cell(17, 4, 'MATERIA:',  0, 0, 'L');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(17, 4, ' ' . $materia_nombre, 0, 0, 'L');

//CURSO
$pdf->Ln();
$pdf->SetFont('helvetica', 'BU', 8.5);
$pdf->Cell(13, 4, 'CURSO:',  0, 0, 'L');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(17, 4, ' ' . $materia_anio, 0, 0, 'L');

//FORMATO
$pdf->Ln();
$pdf->SetFont('helvetica', 'BU', 8.5);
$pdf->Cell(17, 4, 'FORMATO:',  0, 0, 'L');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(17, 4, ' ' . 'Materia', 0, 0, 'L');

//PROFESOR
$pdf->Ln();
$pdf->SetFont('helvetica', 'BU', 8.5);
$pdf->Cell(19, 4, 'PROFESOR:',  0, 0, 'L');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(17, 4, ' ' . 'Fontanellaz, Javier', 0, 0, 'L');


// ****************************************** FIN DATOS DEL PDF *************************************************
$pdf->Ln(7);





$str_recursa = $tbl_rows = "";

if (!empty($ARRAY_DATOS_INSCRIPCION_ALUMNO)) {
    foreach($ARRAY_DATOS_INSCRIPCION_ALUMNO as $item) {
        $str_recursa = ($item['nombre_estado']=='Libre')?'SI':'';
        $tr_nota = ($item['nota']==-1)?'AUSENTE (Libre)' : $item['nota'] .'&nbsp;('. ucfirst($item['nombre_estado']) .')';
        $tbl_rows .= '<tr>
                <td colspan="4" align="left">&nbsp;' . $item['apellido'] . ', ' . $item['nombre']. '</td>
                <th colspan="2" align="center">' . $item['dni'] . '</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>

                <th colspan="2" align="center"></th>
                <th colspan="3" align="left">&nbsp;' . $tr_nota . '</th>
                <th colspan="2" align="center">' . $str_recursa . '</th>
                </tr>
    '; 
    }
}

$tbl = <<<EOD


<table border="1">
<tr> 
<th colspan="4" align="center" valign="middle"><b>APELLIDO Y NOMBRE</b></th>
<th colspan="2" align="center">DNI</th>
<th colspan="5" align="center">TRABAJOS/PRODUCCIONES/ETC</th>
<th colspan="2" align="center">&nbsp;NOTA INSTANCIA FINAL INTEGRADORA&nbsp;</th>
<th colspan="3" align="center">NOTA FINAL</th>
<th colspan="2" align="center">RECURSA</th>
</tr> 
$tbl_rows
</table>
EOD;


$pdf->writeHTML($tbl, true, false, false, false, '');




// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Inscripcion.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+