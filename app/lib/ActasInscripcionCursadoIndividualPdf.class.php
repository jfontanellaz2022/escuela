<?php
require_once('ActasPdf.class.php');
date_default_timezone_set("America/Argentina/Buenos_Aires");
setlocale(LC_TIME, 'es_AR.UTF-8','esp');

class ActasInscripcionCursadoIndividualPdf extends ActasPdf {
    private $dia,$mes,$anio,$pagina;
    private $alumno = [];


    public function setAlumno($alumno) {
        return $this->alumno = $alumno;
    }

    public function setPagina($pagina) {
        return $this->pagina = $pagina;
    }

    public function getPagina() {
        return $this->pagina;
    }

    public function setDia($dia) {
        return $this->dia = $dia;
    }

    public function setMes($mes) {
        return $this->mes = $mes;
    }

    public function setAnio($anio) {
        return $this->anio = $anio;
    }

    public function getAnio() {
        return $this->anio;
    }

    public function getNombreAnio($anio) {
        if ($this->getAnio()==1) return "PRIMER AÑO";
        else if ($this->getAnio()==2) return "SEGUNDO AÑO";
        else if ($this->getAnio()==3) return "TERCER AÑO";
        else if ($this->getAnio()==4) return "CUARTO AÑO";
        return null;
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
    
    public function header() {
        $this->setX(7);
        if ($this->pagina==1) {
            $this->SetFillColor(255,255,255);	

            $apellido_nombre = $this->alumno['apellido'] . ', ' . $this->alumno['nombre'];
            $dni = $this->alumno['documento'];
            $carrera = $this->alumno['carrera'];
            $this->SetFont('courier','B',10);
            $this->Cell(40,6,"APELLIDO Y NOMBRE: ",0,0,'L',TRUE);
            $this->SetFont('courier','',10);
            $this->Cell(75,6,$apellido_nombre,0,1,'L',TRUE);

            $this->setX(7);
            
            $this->SetFont('courier','B',10);
            $this->Cell(23,6,"DOCUMENTO: ",0,0,'L',TRUE);
            $this->SetFont('courier','',10);
            $this->Cell(20,6,$dni,0,1,'L',TRUE);

            $this->setX(7);
            
            $this->SetFont('courier','B',10);
            $this->Cell(23,6,"CARRERA: ",0,0,'L',TRUE);
            $this->SetFont('courier','',10);
            $this->Cell(20,6,$carrera,0,1,'L',TRUE);
        
        }
        $this->setX(7);
        $this->SetFillColor(255,255,255);	
        $this->SetFont('courier','B',10);
        $this->Cell(196,10,$this->getNombreAnio($this->getAnio()),1,1,'C',TRUE);
        $this->setX(7);
        $this->SetFillColor(255,255,255);	
        $this->SetFont('courier','B',10);
        $this->Cell(75,16,'ASIGNATURAS',1,0,'C',TRUE);
        $this->Cell(21,16,'CURSADO',1,0,'C',TRUE);
        $this->Cell(18,8,'P.D',1,0,'C',TRUE);
        $this->Cell(23,8,'Regularizó',1,0,'C',TRUE);
        $this->Cell(30,8,'EXÁMEN FINAL',1,0,'C',TRUE);
        $this->Cell(29,16,'OBSERVACIONES',1,1,'C',TRUE);
        $this->SetFont('courier','B',10);
        if ($this->getPagina()==1) {
            $this->setXY(103,41);
            $this->Cell(18,8,' Prom.D.',1,0,'C',TRUE);
            $this->Cell(13,8,'Nota',1,0,'C',TRUE);
            $this->Cell(10,8,'Año',1,0,'C',TRUE);
            $this->Cell(13,8,'Nota',1,0,'C',TRUE);
            $this->Cell(17,8,'Fecha',1,0,'C',TRUE);
        } else {
            $this->setXY(103,23);
            $this->Cell(18,8,' Prom.D.',1,0,'C',TRUE);
            $this->Cell(13,8,'Nota',1,0,'C',TRUE);
            $this->Cell(10,8,'Año',1,0,'C',TRUE);
            $this->Cell(13,8,'Nota',1,0,'C',TRUE);
            $this->Cell(17,8,'Fecha',1,0,'C',TRUE);
        }
        
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
        //$this->Cell(190,5,'Total de alumnos:_______________',0,1,'R',false);
        //$this->Cell(190,5,'Aprobados:_________',0,1,'R',false);
        //$this->Cell(190,5,'SAN CRISTOBAL:  '.$this->dia.'  de   '.ucfirst($mes_nombre).'  de  '.$this->anio.'          Desaprobados:_________',0,1,'R',false);
       // $this->Cell(190,5,'Ausentes:_________',0,1,'R',false);
    }

}



?>
