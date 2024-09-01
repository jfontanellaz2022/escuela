<?php
require('fpdf.php');

class PDF_MC_Table extends FPDF
{
var $widths;
var $aligns;

function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,5,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}

//Cabecera de pagina

function Header()
                                {

                                    $this->SetLeftMargin(15);
                                    $this->Image('../public/img/logo.jpg',15,4,119,13);
                                    $this->Ln(15);
                                    //Arial bold 15
                                    $this->SetFont('Times','B',10);
                                    //Movernos a la derecha
                                   //   $this->Cell(80);
                                    //T�tulo
                                    $this->SetFillColor(102,102,102);
                                    $this->SetTextColor(255,255,255);
                                    $this->Cell(180,5,'Materias a la que el Alumno se Inscribe',0,1,'C',true);
                                    $this->Ln(1);
                                    $this->SetFillColor(204,204,204);
                                    $this->SetTextColor(0,0,0);
                                    $this->Cell(180,5,'',0,1,'C',true);
                                    $this->Cell(39,5,'Apellido y Nombre:',0,0,'L',false);
                                    $this->Cell(70,5,utf8_decode($_SESSION['apellido']).', '.utf8_decode($_SESSION['nombre']),0,0,'L',false);
                                    $this->Cell(20,5,'DNI:',0,0,'L',false);
                                    $this->Cell(70,5,$_SESSION['usuario'],0,1,'L',false);

                                }



//Pie de p�gina
function FooterMio($idCalendario)
                                {
                                    //Posici�n: a 1,5 cm del final
                                    $this->SetY(-32);
                                    $this->SetX(25);
                                    //Arial italic 8

                                    //Numero de pagina
                                    $fechaHora=date("d-m-Y");
                                    $fecha=date("d-m-Y");
                                    //$cod=$_SESSION['usuario'].$idCalendario.$fecha;
                                    //$str=base64_encode($cod);

                                    //$this->SetFont('Arial','I',10);
                                    //$transaccion=utf8_decode('Transacción');
                                   // $this->Cell(40,10,'Fecha de '.$transaccion.': ',0,0,'C');
                                    //$this->SetFont('courier','B',10);
                                    //$this->Cell(80,10,$fechaHora,0,0,'L');

                                    //$this->SetX(90);
                                    //$this->Cell(40,10,$transaccion.': ',0,0,'C');
                                    //$this->SetFont('courier','B',14);
                                    //$this->Cell(180,10,$str,0,0,'L');

                                 }




}
?>
