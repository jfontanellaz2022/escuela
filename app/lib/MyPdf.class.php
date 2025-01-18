<?php
require_once('tcpdf/tcpdf.php');

class MyPdf extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = '../../public/assets/img/logo_n.jpg';
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

}



?>
