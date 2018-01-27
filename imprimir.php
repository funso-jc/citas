<?php
/*
error_reporting(E_ALL);
ini_set('display_errors','1');
*/
header('Content-type: application/pdf');
session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);
include("dbconfig.php");
include("funciones.php");
define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdf.php');
/*
require('fpdf_x/mysql_table.php');
include("fpdf_x/comunes.php");
*/
$pdf=new FPDF('L','mm','Letter');
//$pdf->Open();
$pdf->AddPage();
$sqle="select * from ".$_SESSION['bdd']."configuracion limit 1";
$rese=$db_con->prepare($sqle);
$rese->execute();
$rese=$rese->fetch(PDO::FETCH_ASSOC);
$linea=10;
$altura=5;

// indicaciones 
$sqlnot="select realizado as fecha, rp as parte1, ind as parte2 from ".$_SESSION['bdd']."tratamiento where nrocon_recipe = '".$_SESSION['numeroregistro']."' order by realizado";
//$linea+=($altura*3);
//$linea=60;
$izquierda=10;
$iniciofila=$linea;
$iniciocolumna=$izquierda;
$altura=encabezado($linea, $pdf, $rese, $altura, 2, $izquierda);
$linea=10;
$izquierda=140;
$altura=encabezado($linea, $pdf, $rese, $altura, 2, $izquierda);
$pdf->SetY($linea);
$izquierda=10;
$ancho=140;
$pdf->SetX($izquierda);
$pdf->MultiCell($ancho,$altura,'Rp.',0,'L',0);
$izquierda=140;
$pdf->SetX($izquierda);
$pdf->MultiCell($ancho,$altura,'Indicaciones',0,'L',0);
$res=$db_con->prepare($sqlnot);
$res->execute();
// echo $sqlnot;
while ($fila2 = $res->fetch(PDO::FETCH_ASSOC)) 
{
	$linea+=$altura;
	$pdf->SetY($linea);
	$izquierda1=20;
	$izquierda2=150;
	$caracteres=60;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $fila2['parte1'], $caracteres);
	$lineasd=detalle($pdf, $izquierda2, $linea, $altura, $fila2['parte2'], $caracteres);
	$linea=($lineasi>$lineasd?$lineasi:$lineasd);
}
$linea+=($altura*2);
$l1='Nombre Medico: ________________________________';
$l2='MPPS:________ CMEL:________ CI:________________';
$l3='Firma: _______________________________';
$l4='Paciente '.$_SESSION['nombrepaciente'];
$l5='CI: ' .$_SESSION['cedulabeneficiario']. ' Fecha : ' .substr(ahora($db_con),0,10);
$l6='Calle 7 con Prolongacion Avda Moran Diagonal Iglesia Claret';
$l7='Telefono: 0251-2524828. Barquisimeto Estado Lara';
$izquierda1+=10;
$izquierda2+=10;
$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l1, $caracteres);
$lineasd=detalle($pdf, $izquierda2, $linea, $altura, $l1, $caracteres);
$linea+=$altura;
$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l2, $caracteres);
$lineasd=detalle($pdf, $izquierda2, $linea, $altura, $l2, $caracteres);
$linea+=$altura;
$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l3, $caracteres);
$lineasd=detalle($pdf, $izquierda2, $linea, $altura, $l3, $caracteres);
$linea+=($altura*2);
$izquierda1-=15;
$izquierda2-=15;
$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l4, $caracteres);
$lineasd=detalle($pdf, $izquierda2, $linea, $altura, $l4, $caracteres);
$linea+=$altura;
$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l5, $caracteres);
$lineasd=detalle($pdf, $izquierda2, $linea, $altura, $l5, $caracteres);
$linea+=$altura;
$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l6, $caracteres);
$lineasd=detalle($pdf, $izquierda2, $linea, $altura, $l6, $caracteres);
$linea+=$altura;
$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l7, $caracteres);
$lineasd=detalle($pdf, $izquierda2, $linea, $altura, $l7, $caracteres);
$linea+=$altura;

$finalfila=$linea+($altura*4);
$finalcolumna=$izquierda;

$pdf->Rect($iniciocolumna, $iniciofila, 130, ($finalfila-$iniciofila), '');
$pdf->Rect($iniciocolumna+130, $iniciofila, 130, ($finalfila-$iniciofila), '');

$linea+=($altura*8);

// fin indicaciones
// referencia especialistas
// $linea+=($altura*8);
$sqlt="select * from ".$_SESSION['bdd']."obreros where cedula = '".$_SESSION['cedulatitular']."'";
$rest=$db_con->prepare($sqlt);
$rest->execute();
$filat=$rest->fetch(PDO::FETCH_ASSOC);

$sqlnot="select ".$_SESSION['bdd']."referencias.realizado as fecha, examende as parte1, observacion, instituto as parte2 from ".$_SESSION['bdd']."referencias, ".$_SESSION['bdd']."ninstituto where (numcon_referencia = '".$_SESSION['numeroregistro']."') and ".$_SESSION['bdd']."referencias.tipo != 'Laboratorio' and ".$_SESSION['bdd']."referencias.especialista=".$_SESSION['bdd']."ninstituto.codmed order by ".$_SESSION['bdd']."referencias.realizado ";
$resultado=$db_con->prepare($sqlnot);
//echo $sqlnot;
$resultado->execute();
$raya='_________________________________________________________________________________________';
while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) 
{
	$izquierda=10;
	$iniciofila=$linea;
	$iniciocolumna=$izquierda;
	$altura=encabezado($linea, $pdf, $rese, $altura, 2, $izquierda);
	$r1=$iniciofila.'Referido a: ' .$fila2['parte2'];
	$r2='Fecha '.substr(ahora($db_con),0,10);
	$r3='Titular: '.$filat['ape_nom'].' CI.'.$_SESSION['cedulatitular'];
	$r4='Beneficiario '.$_SESSION['nombrepaciente']. ' CI: ' .$_SESSION['cedulabeneficiario']. ' Edad: '.cedad(convertir_fechadmy($filat["fnacim"]));
;
	$linea+=$altura;
	$pdf->SetY($linea);
	$izquierda1=20;
	$izquierda2=150;
	$caracteres=90;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $r1, $caracteres);
	$lineasi=detalle($pdf, 220, $linea, $altura, $r2, $caracteres);
	$linea+=$altura;
	$linea+=$altura;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $r3, $caracteres);
	$linea+=$altura;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $r4, $caracteres);
	$linea+=$altura;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, 'Valoracion por Especialista', $caracteres);
	$linea+=$altura;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $raya, $caracteres);
	$linea+=$altura;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $raya, $caracteres);
	$linea+=$altura;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $raya, $caracteres);
	$finalfila=$linea+($altura*5);
	$pdf->Rect($iniciocolumna, $iniciofila, 260, ($finalfila-$iniciofila), '');
}

// fin referencia especialistas
$pdf->Output();
$pdf2=new PDF('P','mm','Letter');
$pdf2->Open();
$pdf2->AddPage();

// referencia laboratorio
// $linea+=($altura*8);
$sqlt="select * from ".$_SESSION['bdd']."obreros where cedula = '".$_SESSION['cedulatitular']."'";
$rest=$db_con->prepare($sqlt);
$rest->execute();
$filat=$rest->fetch(PDO::FETCH_ASSOC);

$sqlnot="select ".$_SESSION['bdd']."_referencias.realizado as fecha, examende as parte1, observacion, instituto as parte2 from ".$_SESSION['bdd']."referencias, ".$_SESSION['bdd']."ninstituto where (numcon_referencia = '".$_SESSION['numeroregistro']."') and ".$_SESSION['bdd']."referencias.tipo = 'Laboratorio' and ".$_SESSION['bdd']."referencias.especialista=".$_SESSION['bdd']."ninstituto.codmed order by ".$_SESSION['bdd']."referencias.realizado ";
$resultado=$db_con->prepare($sqlnot);
$resultado->execute();
$raya='_________________________________________________________________________________________';
while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) 
{
	$izquierda=10;
	$iniciofila=$linea;
	$iniciocolumna=$izquierda;
	$altura=encabezado($linea, $pdf, $rese, $altura, 3, $izquierda);
	$r1=$iniciofila.'Referido a: ' .$fila2['parte2'];
	$r2='Fecha '.substr(ahora($db_con),0,10);
	$r3='Titular: '.$filat['ape_nom'].' CI.'.$_SESSION['cedulatitular'];
	$r4='Beneficiario '.$_SESSION['nombrepaciente']. ' CI: ' .$_SESSION['cedulabeneficiario']. ' Edad: '.cedad(convertir_fechadmy($filat["fnacim"]));
;
	$linea+=$altura;
	$pdf2->SetY($linea);
	$izquierda1=20;
	$izquierda2=150;
	$caracteres=90;
	$lineasi=detalle($pdf2, $izquierda1, $linea, $altura, $r1, $caracteres);
	$lineasi=detalle($pdf2, 220, $linea, $altura, $r2, $caracteres);
	$linea+=$altura;
	$linea+=$altura;
	$lineasi=detalle($pdf2, $izquierda1, $linea, $altura, $r3, $caracteres);
	$linea+=$altura;
	$lineasi=detalle($pdf2, $izquierda1, $linea, $altura, $r4, $caracteres);
	$linea+=$altura;
	$lineasi=detalle($pdf2, $izquierda1, $linea, $altura, 'Valoracion por Especialista', $caracteres);
	$linea+=$altura;
	$lineasi=detalle($pdf2, $izquierda1, $linea, $altura, $raya, $caracteres);
	$linea+=$altura;
	$lineasi=detalle($pdf2, $izquierda1, $linea, $altura, $raya, $caracteres);
	$linea+=$altura;
	$lineasi=detalle($pdf2, $izquierda1, $linea, $altura, $raya, $caracteres);
	$finalfila=$linea+($altura*5);
	$pdf2->Rect($iniciocolumna, $iniciofila, 260, ($finalfila-$iniciofila), '');
}

// fin referencia laboratorio


//$pdf->AddPage();
	$izquierda=150;
	$pdf->SetX($izquierda);
	$pdf->MultiCell($ancho,$altura,$linea.'inicio '.$iniciofila. ' finalfila '.$finalfila,0,L,0);

// $this->Image('fpdf/logo/logo.jpg',10,0,20);
$pdf2->Output();
// guardar esta historia // para que? 

function detalle ($pdf, $izquierda, $lineainterna, $altura, $cuento, $caracteres)
{
	$cuento = trim($cuento);
	$actual=0;
	while ($actual < strlen($cuento)) 
	{
		$aimprimir = substr($cuento,$actual, $caracteres);
		$lineainterna+=$altura;
		$pdf->SetY($lineainterna);
		$pdf->SetX($izquierda);
		$pdf->Cell(0,20,$aimprimir,0,0,'L',0);
		$actual+=$caracteres;
	}

	return $lineainterna;
}

// una pastilla cada ocho horas si hay fiebre por ocho dias o 
// una cada cuatro horas si se siente maluco o tiene dolor de 
// cabeza o malestar en el cuerpo


function encabezado(&$linea, $pdf, $fila, $altura, $ancho, $izquierda)
{
	if ($ancho == 1)
	{
		$ancho = 85;
		$tamanoid=12;
		$tamanonombre=8;
		$tamanologo=8;
		$altura=3;
	}
	if ($ancho == 2)
	{
		$ancho = 130;
		$tamanoid=16;
		$tamanonombre=12;
		$tamanologo=12;
		$altura=4;
	}
	if ($ancho == 3)
	{
		$ancho = 260;
		$tamanoid=20;
		$tamanonombre=14;
		$tamanologo=20;
		$altura=5;
	}
	$pdf->SetFont('Arial','B',$tamanoid);
	$pdf->SetY($linea);
	$pdf->SetX($izquierda);
	$pdf->MultiCell(0,0, $pdf->Image('fpdf_x/logo/logo.jpg', $pdf->GetX()+$tamanologo, $pdf->GetY(), $tamanologo) ,0,"C");
	$pdf->SetFont('Arial','B',$tamanoid);
	$pdf->SetX($izquierda);
	$pdf->MultiCell($ancho,$altura,$fila['alias_empr'],0,'C',0);
	$linea+=$altura;
	$pdf->SetY($linea);
	$pdf->SetX($izquierda);
	$pdf->SetFont('Arial','',$tamanonombre);
	$pdf->MultiCell($ancho,$altura,$fila['nombr1_empr'],0,'C',0);
	$linea+=$altura;
	$pdf->SetY($linea);
	$pdf->SetX($izquierda);
	$pdf->SetFont('Arial','',$tamanonombre);
	$pdf->MultiCell($ancho,$altura,$fila['nombr2_empr'],0,'C',0);
	$linea+=$altura;
	$pdf->SetY($linea);
	$pdf->SetX($izquierda);
	$pdf->SetFont('Arial','',$tamanonombre);
	$pdf->MultiCell($ancho,$altura,'RIF '.$fila['rif'],0,'C',0);
	return $altura;
}

?>