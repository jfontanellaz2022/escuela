<?php
require_once('tcpdf/tcpdf.php');

class MateriaPdf extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = '../public/assets/img/logo_n.jpg';
        //$image_file = '';
        $this->Image($image_file, 10, 4, 20, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
				//          (arch       ,  coord_x, coord_y, tamanio
        // Set font
        // Title
				/*$this->SetFont('helvetica', 'B', 13);
				$this->SetXY(35,8);
        $this->Cell(70, 18, 'Escuela Normal Superior N40 "Mariano Moreno"', 0, false, 'L', 0, '', 0, false, 'M', 'M');
				$this->SetXY(35,14);
				$this->SetFont('helvetica', '', 11);
				$this->Cell(70, 18, 'J.M. Bullo 1402 - TE. 03408 - 422447', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $this->SetXY(35,19);
				$this->SetFont('helvetica', '', 11);
				$this->Cell(70, 18, 'San Cristobal - Pcia. de Santa Fe', 0, false, 'L', 0, '', 0, false, 'M', 'M');*/



        //$this->Image('assets/img/LogoENS40.jpg', 55, 10 , 25, 25); 
        // Arial bold 15 //TITULO
        //$this->SetFont('Arial', 'I', 8);
        // Movernos a la derecha titulo
        $this->Cell(5);
        // Título
        $this->SetFont('helvetica', 'B', 13);
        $this->Cell(106, 5, 'Escuela Normal Superior "Mariano Moreno Nº 40', 0, 1, 'L');
        $this->Cell(20);
        $this->SetFont('helvetica', '', 10);
        $this->Cell(195, 5, 'Nivel Superior - Tel. Fax. 03408-422447', 0, 1, 'L');
        $this->Cell(20);
        $this->Cell(196, 5, 'E-Mail: superiorbedelia40@yahoo.com.ar', 0, 1, 'L');
        $this->Cell(20);
        $this->Cell(210, 5, 'Juan M Bullo 1402 - 3070 - San Cristóbal (Santa Fe)', 0, 1, 'L');
        $this->Cell(20);
        // Salto de línea
        $this->Ln(3);
        //$this-> Line(25,20,(210-25),20);

    }

    // Pie de página
  public function Footer() {
        //Posición: a 1,5 cm del final
        
        $this->SetY(-15);
        // Arial italic 8
        //$this->SetFont('Arial', 'U', 8);
        //Número de página
        
        $this->Cell(10);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . '', 0, 0, 'C');
    
    //$this-> Line(25,20,(210-25),20);
    }

}



?>
