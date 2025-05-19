<?php
require_once('ActasPdf.class.php');

class ActasInscripcionCarreraPdf extends ActasPdf {

     
     // Page footer
    public function Footer() {
        $this->SetY(-50);
        // Arial italic 8
        $this->SetFont('times', 'U', 8);
        //Número de página
        $this->Cell(10);
        $this->Cell(59, 5,  'DOCUMENTACIÓN PARA PRESENTAR:', 0, 1, 'L');

        $this->SetFont('times', 'I', 8);
        $this->Cell(10);
        $this->Cell(130, 5, 'PARTIDA DE NACIMIENTO -  CERTIFICADO DE ESTUDIOS COMPLETOS NIVEL SECUNDARIO', 0, 1, 'L');
        $this->Cell(10);
        $this->Cell(68, 5, 'FOTOCOPIA DNI -  CERTIFICADO VECINDAD', 0, 1, 'L');
        $this->Cell(10);
        $this->Cell(65, 5, 'FOTO 4X4 -  CERTIFICADO BUENA SALUD', 0, 1, 'L');
        $this->Cell(10);
        $this->Cell(186, 5, 'La inscripción se considerará definitiva y completa, cuando el ingresante presente toda la documentación que respalde la Declaración Jurada', 0, 1, 'L');
        $this->Cell(10);
        $this->Cell(62, 5, 'de los Artículos 6° y 8° del decreto 4199/15', 0, 1, 'L');
        
    }

}



?>
