<?php
set_include_path('../../app/lib/fpdf/'.PATH_SEPARATOR.'../../conexion/');
require_once('conexion.php');
require_once('fpdf.php');

class PDF extends FPDF
{
//Cabecera de p�gina
function Header()
{
    
  $parametros=$_GET['parametros'];
  $arrCarreraTurnoLlamado=explode('_',$parametros);
  $idCarrera=$arrCarreraTurnoLlamado[0];
  $idCalendario=$arrCarreraTurnoLlamado[1];
  $llamado=$arrCarreraTurnoLlamado[2];
  $idMateria=$arrCarreraTurnoLlamado[3];

    $sql="SELECT c.nombre, c.anio
        FROM materia c 
        WHERE c.id=$idMateria";      
    global $conex; 
$resultado=  mysqli_query($conex, $sql);
$fila1=mysqli_fetch_assoc($resultado);
$nombreMateria=strtoupper($fila1['nombre']);
$anioMateria=$fila1['anio'];
if ($anioMateria==1) $anioCompleto=' PRIMERO';
else if ($anioMateria==2) $anioCompleto=' SEGUNDO';
else if ($anioMateria==3) $anioCompleto=' TERCERO';
else if ($anioMateria==4) $anioCompleto=' CUARTO';

$sqlCarrera="SELECT c.descripcion_corta
             FROM carrera c 
             WHERE c.id=$idCarrera"; 
$resultadoCarrera=  mysqli_query($conex, $sqlCarrera);
$filaCarrera=mysqli_fetch_assoc($resultadoCarrera);
$nombreCarrera=$filaCarrera['descripcion_corta'];

    $this->SetLeftMargin(10);
    $this->Image('../../public/img/logo.jpg',15,4,139,13);
    $this->Ln(15);
    //Arial bold 15
    $this->SetFont('courier','B',10);
    //Movernos a la derecha
   //   $this->Cell(80);
    //T�tulo
    $this->SetFillColor(255,255,255);
    $this->SetTextColor(0,0,0);
    $this->Cell(190,7,'ACTA VOLANTE DE EVALUACIONES',0,1,'R',true);

    $this->Ln(1);
    $this->SetFillColor(204,204,204);
    $this->SetTextColor(0,0,0);
			
    //$this->Cell(190,5,'',0,1,'C',true);
	
    $anio=utf8_decode('AÑO');	
    
    $dd=date('d');
    $mm=date('m');
    $yy=date('Y');
    $this->SetFillColor(255,255,255);	
    $this->Cell(52,7,'EVALUACIONES DE ALUMNOS:',0,0,'L',false);
    $this->SetFont('courier','',10);
    $this->Cell(105,7,'REGULARES FINALES',0,0,'L',TRUE);
    $this->SetFont('courier','B',10);
    $this->Cell(10,7,'DIA',1,0,'L',TRUE);
    $this->Cell(10,7,'MES',1,0,'L',TRUE);
    $this->Cell(10,7,$anio,1,1,'L',TRUE);
    
    $this->Cell(25,7,'ASIGNATURA:',0,0,'L',false);
    $this->SetFont('courier','',10);
    $this->Cell(132,7,utf8_decode($nombreMateria),0,0,'L',TRUE);
    $this->Cell(10,9,$dd,1,0,'L',TRUE);
    $this->Cell(10,9,$mm,1,0,'L',TRUE);
    $this->Cell(10,9,$yy,1,1,'L',TRUE);
    
    $this->SetFont('courier','B',10);
    $this->Cell(18,7,'CARRERA:',0,0,'L',false);
    $this->SetFont('courier','',10);
    $this->Cell(65,7,utf8_decode($nombreCarrera),0,0,'L',false);
    $this->SetFont('courier','B',10);
    $this->Cell(8,7,$anio.':',0,0,'L',false);
    $this->SetFont('courier','',10);
    $this->Cell(25,7,$anioCompleto,0,0,'L',false);
    $this->SetFont('courier','B',10);
    $this->Cell(20,7,'DIVISION:',0,0,'L',false);
    $this->SetFont('courier','',10);
    $this->Cell(16,7,'UNICA',0,0,'L',false);
    $this->SetFont('courier','B',10);
    $this->Cell(14,7,'TURNO:',0,0,'L',false);
    $this->SetFont('courier','',10);
    $this->Cell(18,7,'VESPERTINO',0,1,'L',false);

    
    $this->SetFont('courier','',8);
    $this->Cell(15,10,'Orden',1,0,'C',true);
    $this->Cell(15,10,'Permiso',1,0,'C',true);
    $this->Cell(32,10,'DOC. DE IDENTIDAD',1,0,'C',true);
    $this->Cell(68,10,'APELLIDO Y NOMBRES',1,0,'C',true);
    $this->Cell(30,10,'EVALUACIONES',1,0,'C',true);
    $this->Cell(30,10,'OBSERVACIONES',1,1,'C',true);
    
//    $this->Cell(15,5,'',1,0,'C',true);
//    $this->Cell(15,5,'',1,0,'C',true);
//    $this->Cell(40,5,'',1,0,'C',true);
//    $this->Cell(60,5,'',1,0,'C',true);
//    $this->Cell(30,5,'',1,0,'C',true);
//    $this->Cell(30,5,'',1,1,'C',true);
    
//    $this->Cell(15,5,'',1,0,'C',true);
//    $this->Cell(15,5,'',1,0,'C',true);
//    $this->Cell(40,5,'',1,0,'C',true);
//    $this->Cell(60,5,'',1,0,'C',true);
//    $this->Cell(30,5,'',1,0,'C',true);
//    $this->Cell(30,5,'',1,1,'C',true);
//    
//     $this->Cell(15,5,'',1,0,'C',true);
//    $this->Cell(15,5,'',1,0,'C',true);
//    $this->Cell(40,5,'',1,0,'C',true);
//    $this->Cell(60,5,'',1,0,'C',true);
//    $this->Cell(30,5,'',1,0,'C',true);
//    $this->Cell(30,5,'',1,1,'C',true);
}

function numero_nombre($aus) {
    if ($aus==0) $cant_aus='- NINGUNO -';
    elseif ($aus==1) $cant_aus='1(Uno)';
    elseif ($aus==2) $cant_aus='2(Dos)';
    elseif ($aus==3) $cant_aus='3(Tres)';
    elseif ($aus==4) $cant_aus='4(Cuatro)';
    elseif ($aus==5) $cant_aus='5(Cinco)';
    elseif ($aus==6) $cant_aus='6(Seis)';
    elseif ($aus==7) $cant_aus='7(Siete)';
    elseif ($aus==8) $cant_aus='8(Ocho)';
    elseif ($aus==9) $cant_aus='9(Nueve)';
    elseif ($aus==10) $cant_aus='10(Diez)';
    elseif ($aus==11) $cant_aus='11(Once)';
    elseif ($aus==12) $cant_aus='12(Doce)';
    elseif ($aus==13) $cant_aus='13(Trece)';
    elseif ($aus==14) $cant_aus='14(Catorce)';
    elseif ($aus==15) $cant_aus='15(Quince)';
    elseif ($aus==16) $cant_aus='16(Dieciseis)';
    elseif ($aus==17) $cant_aus='17(Diecisiete)';
    elseif ($aus==18) $cant_aus='18(Dieciocho)';
    elseif ($aus==19) $cant_aus='19(Diecinueve)';
    elseif ($aus==20) $cant_aus='20(Veinte)';
    elseif ($aus==21) $cant_aus='21(Veintiuno)';
    elseif ($aus==22) $cant_aus='22(Veintidos)';
    elseif ($aus==23) $cant_aus='23(Veintitres)';
    elseif ($aus==24) $cant_aus='24(Veinticuatro)';
    elseif ($aus==25) $cant_aus='25(Veinticinco)';
    elseif ($aus==26) $cant_aus='26(Veintiseis)';
    elseif ($aus==27) $cant_aus='27(Veintisiete)';
    elseif ($aus==28) $cant_aus='28(Veintiocho)';
    elseif ($aus==29) $cant_aus='29(Veintinueve)';
    elseif ($aus==30) $cant_aus='30(Treinta)';
    elseif ($aus==31) $cant_aus='31(Treinta y uno)';
    elseif ($aus==32) $cant_aus='32(Treinta y dos)';
    elseif ($aus==33) $cant_aus='33(Treinta y tres)';
    elseif ($aus==34) $cant_aus='34(Treinta y cuatro)';
    elseif ($aus==35) $cant_aus='35(Treinta y cinco)';
    elseif ($aus==36) $cant_aus='36(Treinta y seis)';
    elseif ($aus==37) $cant_aus='37(Treinta y siete)';
    elseif ($aus==38) $cant_aus='38(Treinta y ocho)';
    elseif ($aus==39) $cant_aus='39(Treinta y nueve)';
    elseif ($aus==40) $cant_aus='40(Cuarenta)';
 
return $cant_aus;    
}    

function saca_mes($mes) {
    if ($mes=='01') $nombre='Enero';
    elseif ($mes=='02') $nombre='Febrero';
    elseif ($mes=='03') $nombre='Marzo';
    elseif ($mes=='04') $nombre='Abril';
    elseif ($mes=='05') $nombre='Mayo';
    elseif ($mes=='06') $nombre='Junio';
    elseif ($mes=='07') $nombre='Julio';
    elseif ($mes=='08') $nombre='Agosto';
    elseif ($mes=='09') $nombre='Septiembre';
    elseif ($mes=='10') $nombre='Octubre';
    elseif ($mes=='11') $nombre='Noviembre';
    elseif ($mes=='12') $nombre='Diciembre';
    return $nombre;
}

//Pie de p�gina
function FooterMio($excede_pagina, $aus, $apro, $desa)
{
   
   $ausentes = $this->numero_nombre($aus);
   $aprobados = $this->numero_nombre($apro);
   $desaprobados = $this->numero_nombre($desa);
    
       
    //Posici�n: a 1,5 cm del final
   if (!$excede_pagina) {$this->SetY(-55);
   $this->SetX(10);
    //Arial italic 8
    $dia=date('d');
    $mes=$this->saca_mes(date('m'));
    $anio=date('Y');
    //N�mero de p�gina
    $this->SetFont('courier','B',10);
    $this->Cell(25,10,'Presidente:',0,0,'L',false);
    $this->SetFont('courier','',10);
    $this->Cell(45,10,'____________________',0,0,'L',false);
    $this->SetFont('courier','B',10);
    $this->Cell(15,10,'Vocal:',0,0,'L',false);
    $this->SetFont('courier','',10);
    $this->Cell(45,10,'____________________',0,0,'L',false);
    $this->SetFont('courier','B',10);
    $this->Cell(15,10,'Vocal:',0,0,'L',false);
    $this->SetFont('courier','',10);
    $this->Cell(45,10,'____________________',0,1,'L',false);
    $this->Cell(190,5,'Total de alumnos:_______________',0,1,'R',false);
    $this->Cell(190,5,'Aprobados:_________',0,1,'R',false);
    $this->Cell(190,5,'SAN CRISTOBAL:__'.$dia.'__ de _'.$mes.'_ de _'.$anio.'_         Desaprobados:_________',0,1,'R',false);
    $this->Cell(190,5,'Ausentes:_________',0,1,'R',false);
    }
 }
} // END Class


//Creaci�n del objeto de la clase heredada

//$sqlMateriasInscriptas="SELECT a.idMateria, b.nombre as nombreMateria, a.llamado, b.anio, a.idCalendario
//FROM alumno_rinde_materia a, materia b
//WHERE a.idAlumno={$idAlumno} and
//  a.idCalendario={$idTurno} and 
//  a.idMateria=b.id";
//
//$resultadoMateriasInscriptas=mysqli_query($conex,$sqlMateriasInscriptas);

$parametros = $_GET['parametros'];
$arrCarreraTurnoLlamado = explode('_',$parametros);
$idCarrera = $arrCarreraTurnoLlamado[0];
$idCalendario = $arrCarreraTurnoLlamado[1];
$llamado = $arrCarreraTurnoLlamado[2];
$idMateria = $arrCarreraTurnoLlamado[3];

$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('courier','',8);



$sql="SELECT b.id, b.apellido, b.nombre, b.dni, b.anioIngreso, c.nombre as nombreMateria, c.anio, a.condicion, a.nota
      FROM alumno_rinde_materia a, alumno b, materia c 
      WHERE a.idMateria={$idMateria} and a.idCalendario={$idCalendario} and
              a.llamado={$llamado} and a.condicion not like '%Promocion%' and
              a.idAlumno=b.id and a.idMateria=c.id    
      ORDER BY b.apellido, b.nombre";
//die($sql);            

$resultado=  mysqli_query($conex, $sql);

 $cant_ausentes = 0;$cant_aprobados = 0;$cant_desaprobados = 0;
$nota = 0;
$determ = "";
if (mysqli_num_rows($resultado) > 0)
{
$i=1;   
 while ($fila=mysqli_fetch_assoc($resultado))
  {
    if ($fila['nota']==-1) {$nota='-';$determ='AUSENTE';$cant_ausentes++;}
    elseif ($fila['nota']==0) {$nota='';$determ='';}
    elseif ($fila['nota']==1) {$nota='1';$determ='(Uno)';$cant_desaprobados++;}
    elseif ($fila['nota']==2) {$nota='2';$determ='(Dos)';$cant_desaprobados++;}
    elseif ($fila['nota']==3) {$nota='3';$determ='(Tres)';$cant_desaprobados++;}
    elseif ($fila['nota']==4) {$nota='4';$determ='(Cuatro)';$cant_desaprobados++;}
    elseif ($fila['nota']==5) {$nota='5';$determ='(Cinco)';$cant_desaprobados++;}
    elseif ($fila['nota']==6) {$nota='6';$determ='(Seis)';$cant_aprobados++;}
    elseif ($fila['nota']==7) {$nota='7';$determ='(Siete)';$cant_aprobados++;}
    elseif ($fila['nota']==8) {$nota='8';$determ='(Ocho)';$cant_aprobados++;}
    elseif ($fila['nota']==9) {$nota='9';$determ='(Nueve)';$cant_aprobados++;}
    elseif ($fila['nota']==10) {$nota='10';$determ='(Diez)';$cant_aprobados++;}
    
    $pdf->Cell(15,5,$i,1,0,'C',false);
    $pdf->Cell(15,5,'',1,0,'R',false);
    $pdf->Cell(32,5,$fila['dni'],1,0,'C',false);
    $pdf->Cell(68,5,utf8_decode($fila['apellido']).', '.utf8_decode($fila['nombre']),1,0,'L',false);
    $pdf->Cell(15,5,$nota,1,0,'C',false);
    $pdf->Cell(15,5,$determ,1,0,'C',false);
    $pdf->Cell(30,5,$fila['condicion'],1,1,'R',false);
    $anioMateria=$fila['anio'];
    $nombreMateria=$fila['nombreMateria'];
    $i++;
 };
 $_SESSION['anioMateria']=$anioMateria;
 
};
$excede_pagina=false;
if (mysqli_num_rows($resultado) >33) {
    $excede_pagina=true;
    $pdf->FooterMio($excede_pagina,$cant_ausentes,$cant_aprobados,$cant_desaprobados);
} else $pdf->FooterMio($excede_pagina,$cant_ausentes,$cant_aprobados,$cant_desaprobados);

$pdf->Output();
?>