<?php
require_once('tcpdf/tcpdf.php');

date_default_timezone_set("America/Argentina/Buenos_Aires");
setlocale(LC_TIME, 'es_AR.UTF-8','esp');

class ActasPdf extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = '../public/img/logo_150x150.jpg';
        //$image_file = '';
        $this->Image($image_file, 10, 4, 20, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
				//          (arch       ,  coord_x, coord_y, tamanio
        // Set font
        // Title
				$this->SetFont('helvetica', 'B', 13);
				$this->SetXY(35,8);
        $this->Cell(70, 18, 'Escuela Normal Superior N40 "Mariano Moreno"', 0, false, 'L', 0, '', 0, false, 'M', 'M');
				$this->SetXY(35,14);
				$this->SetFont('helvetica', '', 11);
				$this->Cell(70, 18, 'J.M. Bullo 1402 - TE. 03408 - 422447', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $this->SetXY(35,19);
				$this->SetFont('helvetica', '', 11);
				$this->Cell(70, 18, 'San Cristobal - Pcia. de Santa Fe', 0, false, 'L', 0, '', 0, false, 'M', 'M');
    }
    
    
     // Page footer
    public function Footer() {
        setlocale(LC_TIME,"es_ES");
        // Position at 15 mm from bottom
        $this->SetY(-35);
        // Set font
        //$this->SetFont('helvetica', 'I', 8);
        // Page number
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        $dia = date('d');
        //$mes=$this->saca_mes(date('m'));
        $mes = date('m');
        $dateObj   = DateTime::createFromFormat('!m', $mes);
        $mes_nombre = strftime('%B', $dateObj->getTimestamp());
        $anio = date('Y');
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
        $this->Cell(190,5,'SAN CRISTOBAL:__'.$dia.'__ de _'.ucfirst($mes_nombre).'_ de _'.$anio.'_         Desaprobados:_________',0,1,'R',false);
        $this->Cell(190,5,'Ausentes:_________',0,1,'R',false);
        
        
        
    }

}



?>
