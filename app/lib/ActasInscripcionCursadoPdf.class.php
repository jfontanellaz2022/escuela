<?php
require_once('ActasPdf.class.php');
date_default_timezone_set("America/Argentina/Buenos_Aires");
setlocale(LC_TIME, 'es_AR.UTF-8','esp');

class ActasInscripcionCursadoPdf extends ActasPdf {
    private $dia,$mes,$anio,$aprobados,$desaprobados,$ausentes;

    public function setDia($dia) {
        return $this->dia = $dia;
    }

    public function setMes($mes) {
        return $this->mes = $mes;
    }

    public function setAnio($anio) {
        return $this->anio = $anio;
    }

    public function setAprobados($aprobados) {
        return $this->aprobados = $aprobados;
    }

    public function setDesaprobados($desaprobados) {
        return $this->desaprobados = $desaprobados;
    }

    public function setAusentes($ausentes) {
        return $this->ausentes = $ausentes;
    }

    public function formateaDigitos($cantidad) {
        if ($cantidad==1) return "1 (UNO)";
        else if ($cantidad==2) return "2 (DOS)";
        else if ($cantidad==3) return "1 (TRES)";
        else if ($cantidad==4) return "1 (CUATRO)";
        else if ($cantidad==5) return "1 (CINCO)";
        else if ($cantidad==6) return "1 (SEIS)";
        else if ($cantidad==7) return "1 (SIETE)";
        else if ($cantidad==8) return "1 (OCHO)";
        else if ($cantidad==9) return "1 (NUEVE)";
    }

    public function formateaCantidades($cantidad) {
        if ($cantidad==0) return "NINGUNO";
        else if ($cantidad==1) return "1 (UNO)";
        else if ($cantidad==2) return "2 (DOS)";
        else if ($cantidad==3) return "1 (TRES)";
        else if ($cantidad==4) return "1 (CUATRO)";
        else if ($cantidad==5) return "1 (CINCO)";
        else if ($cantidad==6) return "1 (SEIS)";
        else if ($cantidad==7) return "1 (SIETE)";
        else if ($cantidad==8) return "1 (OCHO)";
        else if ($cantidad==9) return "1 (NUEVE)";
        else if ($cantidad==10) return "1 (DIEZ)";
        else if ($cantidad==11) return "1 (ONCE)";
        else if ($cantidad==12) return "1 (DOCE)";
        else if ($cantidad==13) return "1 (TRECE)";
        else if ($cantidad==14) return "1 (CATORCE)";
        else if ($cantidad==15) return "1 (QUINCE)";
        else if ($cantidad==16) return "1 (DIECISEIS)";
        else if ($cantidad==17) return "1 (DIECISIETE)";
        else if ($cantidad==18) return "1 (DIECIOCHO)";
        else if ($cantidad==19) return "1 (DIECINUEVE)";
        else if ($cantidad==20) return "1 (VEINTE)";
        else if ($cantidad==30) return "30 (TREINTA)";
        else if ($cantidad==40) return "40 (CUARENTA)";
        else if ($cantidad==50) return "50 (CINCUENTA)";
        else if ($cantidad>=21 || $cantidad<=29) {
            return "VEINTI" . $this->formateaDigitos($cantidad-20);
        } else if ($cantidad>=31 || $cantidad>=39) {
            return "TREINTA Y " . $this->formateaDigitos($cantidad-30);
        } else if ($cantidad>=41 || $cantidad>=49) {
            return "CUARENTA Y " . $this->formateaDigitos($cantidad-30);
        } else if ($cantidad>=51 || $cantidad>=59) {
            return "CINCUENTA Y " . $this->formateaDigitos($cantidad-30);
        };
        
    }
    
     
     // Page footer
    public function Footer() {
        
        // Position at 15 mm from bottom
        $this->SetY(-35);
        // Set font
        //$this->SetFont('helvetica', 'I', 8);
        // Page number
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        //$mes=$this->saca_mes(date('m'));
        $miFecha = gmmktime(12,0,0,$this->mes,$this->dia,$this->anio);
        //echo "aaa" . strftime("%A, %d de %B de %Y", $miFecha);exit;
        $mes_nombre = strftime("%B", $miFecha);

        //N�mero de p�gina
        /*$this->SetFont('courier','B',10);
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
        $this->Cell(45,10,'____________________',0,1,'L',false);*/
        $this->Cell(190,5,'Total de alumnos:_______________',0,1,'R',false);
        //$this->Cell(190,5,'Aprobados:_________',0,1,'R',false);
        $this->Cell(190,5,'SAN CRISTOBAL:  '.$this->dia.'  de   '.ucfirst($mes_nombre).'  de  '.$this->anio.'          Desaprobados:_________',0,1,'R',false);
       // $this->Cell(190,5,'Ausentes:_________',0,1,'R',false);
    }

}



?>
