<?php
header('Content-type: application/pdf');
session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);
include("conex.php");
include("funciones.php");
if (!$link OR !$_SESSION['empresa']) {
    include("head.php");
	//header("location: noempresa.php");
	exit;
} 
define('FPDF_FONTPATH','fpdf_x/font/');
require('fpdf_x/mysql_table.php');
include("fpdf_x/comunes.php");
$pdf=new PDF('L','mm','Letter');
$pdf->Open();
$pdf->AddPage();
$sqle="select * from ".$_SESSION['bdd']."_configuracion limit 1";
$rese=mysql_query($sqle);
$rese=mysql_fetch_assoc($rese);
$linea=10;
$altura=5;
$arreglo=[10,95,180];
$izquierda=10;
$iniciofila=$linea;
$iniciocolumna=$izquierda;
$l1='Nombre Medico: ________________________________';
$l2='MPPS:________ CMEL:________ CI:________________';
$l3='Firma: _______________________________';
$l4='Paciente '.$_SESSION['nombrepaciente'];
$l5='CI: ' .$_SESSION['cedulabeneficiario']. ' Fecha : ' .substr(ahora(),0,10);
$l6='Calle 7 con Prolongacion Avda Moran Diagonal Iglesia Claret ';
$l6.='Telefono: 0251-2524828. Barquisimeto Estado Lara';

$raya='_____________________________________________________________________';
$ellado=-1;
$lineainicial=$linea;
$lineamasalta=0;

// informe
// $linea+=($altura*8);
$sqlt="select * from ".$_SESSION['bdd']."_titulares where cedula = '".$_SESSION['cedulatitular']."'";
$rest=mysql_query($sqlt);
$filat=mysql_fetch_assoc($rest);

$sqlnot="select realizado as fecha, dx as parte1, observacion as parte2 from ".$_SESSION['bdd']."_informe where nrocon_informe = '".$_SESSION['numeroregistro']."' order by realizado";
$resultado=mysql_query($sqlnot);
$sqlt="select * from ".$_SESSION['bdd']."_titulares where cedula = '".$_SESSION['cedulatitular']."'";
$rest=mysql_query($sqlt);
$filat=mysql_fetch_assoc($rest);
while ($fila2 = mysql_fetch_assoc($resultado)) 
{
	if ($ellado >= 2)
	{
		$ellado = -1;
		$linea=$lineamasalta;
		if ($linea > 150)
		{
			$pdf->AddPage();
			$lineamasalta=$linea=10;
		}
	}
	else 
	{
		$linea=$lineainicial;
		if ($linea > 150)
		{
			$pdf->AddPage();
			$lineamasalta=$linea=10;
		}
	}
	$ellado++;
	$izquierda=$arreglo[$ellado];
	$iniciofila=$linea;
	$iniciocolumna=$izquierda;
	$altura=encabezado($linea, $pdf, $rese, $altura, 1, $izquierda);
	$r3='Titular: '.$filat['ape_nom'].' CI.'.$_SESSION['cedulatitular'];
	$r4='Beneficiario '.$_SESSION['nombrepaciente']. ' CI: ' .$_SESSION['cedulabeneficiario']. ' Edad: '.cedad(convertir_fechadmy($filat["fecha_nac"]));
;
	$caracteres=51;
	$izquierda1=$izquierda+3;
	$lineasi=detalle($pdf, $izquierda1+25, $linea, $altura, 'INFORME MEDICO', $caracteres);
	$linea=$lineasi;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $r3, $caracteres);
	$linea=$lineasi;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $r4, $caracteres);
	$linea=$lineasi;
	$pdf->SetY($linea);
	$izquierda1=$izquierda;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $fila2['parte1'], $caracteres);
	$linea=$lineasi;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, 'Favor Realizar', $caracteres);
	$linea=$lineasi;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $fila2['parte2'], $caracteres);
	$linea=$lineasi;
	$linea+=($altura*2);
	$izquierda1+=10;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l1, $caracteres);
	$linea=$lineasi;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l2, $caracteres);
	$linea=$lineasi;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l3, $caracteres);
	$linea=$lineasi;
	$izquierda1-=10;
	$caracteres=60;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l6, $caracteres);
//	$linea+=$altura;
//	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l7, $caracteres);
	$linea=$lineasi;
	
	$lineamasalta=(($linea>$lineamasalta)?$linea:$lineamasalta);
//	$lineasi=detalle($pdf, $izquierda1, 150, $altura, 'linea + '.$lineamasalta. 'linea '.$linea, $caracteres);
	$finalfila=$linea+($altura*5);
	$lineamasalta+=($altura*5);
	
	$pdf->Rect($iniciocolumna, $iniciofila, 85, ($finalfila-$iniciofila), '');
}

// fin informe
	$lineasi=detalle($pdf, 150, 150, $altura, 'linea + '.$lineamasalta. 'linea '.$linea, $caracteres);

// justificativo
// $linea+=($altura*8);
$ellado-=1;
$lineainicial=$linea=$lineamasalta;
$sqlt="select * from ".$_SESSION['bdd']."_titulares where cedula = '".$_SESSION['cedulatitular']."'";
$rest=mysql_query($sqlt);
$filat=mysql_fetch_assoc($rest);

$sqlnot="select realizado as fecha, dx as parte1, observacion as parte2 from ".$_SESSION['bdd']."_informe where nrocon_informe = '".$_SESSION['numeroregistro']."' order by realizado";
$resultado=mysql_query($sqlnot);
$sqlt="select * from ".$_SESSION['bdd']."_titulares where cedula = '".$_SESSION['cedulatitular']."'";
$rest=mysql_query($sqlt);
$filat=mysql_fetch_assoc($rest);
while ($fila2 = mysql_fetch_assoc($resultado)) 
{
	if ($ellado >= 2)
	{
		$ellado = -1;
		$linea=$lineamasalta;
		if ($linea > 150)
		{
			$pdf->AddPage();
			$lineamasalta=$linea=10;
		}
	}
/*
	else 
	{
		$linea=$lineainicial;
		if ($linea > 150)
		{
			$pdf->AddPage();
			$lineamasalta=$linea=10;
		}
	}
*/
	$ellado++;
	$izquierda=$arreglo[$ellado];
	$iniciofila=$linea;
	$iniciocolumna=$izquierda;
	$altura=encabezado($linea, $pdf, $rese, $altura, 1, $izquierda);
	$r3=$ellado.'/'.$linea.'Titular: '.$filat['ape_nom'].' CI.'.$_SESSION['cedulatitular'];
	$r4='Beneficiario '.$_SESSION['nombrepaciente']. ' CI: ' .$_SESSION['cedulabeneficiario']. ' Edad: '.cedad(convertir_fechadmy($filat["fecha_nac"]));
;
	$caracteres=51;
	$izquierda1=$izquierda+3;
	$lineasi=detalle($pdf, $izquierda1+25, $linea, $altura, 'JUSTIFICATIVO', $caracteres);
	$linea=$lineasi;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $r3, $caracteres);
	$linea=$lineasi;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $r4, $caracteres);
	$linea=$lineasi;
	$pdf->SetY($linea);
	$izquierda1=$izquierda;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $fila2['parte1'], $caracteres);
	$linea=$lineasi;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, 'Favor Realizar', $caracteres);
	$linea=$lineasi;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $fila2['parte2'], $caracteres);
	$linea=$lineasi;
	$linea+=($altura*2);
	$izquierda1+=10;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l1, $caracteres);
	$linea=$lineasi;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l2, $caracteres);
	$linea=$lineasi;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l3, $caracteres);
	$linea=$lineasi;
	$izquierda1-=10;
	$caracteres=60;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l6, $caracteres);
//	$linea+=$altura;
//	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l7, $caracteres);
	$linea=$lineasi;
	
	$lineamasalta=(($linea>$lineamasalta)?$linea:$lineamasalta);
//	$lineasi=detalle($pdf, $izquierda1, 150, $altura, 'linea + '.$lineamasalta. 'linea '.$linea, $caracteres);
	$finalfila=$linea+($altura*5);
	$lineamasalta+=($altura*5);
	
	$pdf->Rect($iniciocolumna, $iniciofila, 85, ($finalfila-$iniciofila), '');
}

// fin justificativo






/*
$izquierda=10;
$altura=encabezado($linea, $pdf, $rese, $altura, 1, $izquierda);
$linea=10;
$izquierda=95;
$altura=encabezado($linea, $pdf, $rese, $altura, 1, $izquierda);
$linea=10;
$izquierda=180;
$altura=encabezado($linea, $pdf, $rese, $altura, 1, $izquierda);
*/

/*
//$pdf->AddPage();
	$izquierda=150;
	$pdf->SetX($izquierda);
	$pdf->MultiCell($ancho,$altura,$linea.'inicio '.$iniciofila. ' finalfila '.$finalfila,0,L,0);
*/
// $this->Image('fpdf/logo/logo.jpg',10,0,20);
$pdf->Output();
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
		$pdf->Cell(0,20,$aimprimir,0,0,L,0);
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
		$ancho = 200;
		$tamanoid=20;
		$tamanonombre=14;
		$tamanologo=20;
		$altura=5;
	}
//	$fila=mysql_fetch_assoc($fila2);
	$pdf->SetY($linea);
	$pdf->SetX($izquierda);
	$pdf->MultiCell(0,0, $pdf->Image('fpdf_x/logo/logo.jpg', $pdf->GetX()+$tamanologo, $pdf->GetY(), $tamanologo) ,0,"C");
	$pdf->SetFont('Arial','B',$tamanoid);
	$pdf->SetX($izquierda);
	$pdf->MultiCell($ancho,$altura,$fila['alias_empr'],0,C,0);
	$linea+=$altura;
	$pdf->SetY($linea);
	$pdf->SetX($izquierda);
	$pdf->SetFont('Arial','',$tamanonombre);
	$pdf->MultiCell($ancho,$altura,$fila['nombr1_empr'],0,C,0);
	$linea+=$altura;
	$pdf->SetY($linea);
	$pdf->SetX($izquierda);
	$pdf->SetFont('Arial','',$tamanonombre);
	$pdf->MultiCell($ancho,$altura,$fila['nombr2_empr'],0,C,0);
	$linea+=$altura;
	$pdf->SetY($linea);
	$pdf->SetX($izquierda);
	$pdf->SetFont('Arial','',$tamanonombre);
	$pdf->MultiCell($ancho,$altura,'RIF '.$fila['rif'],0,C,0);
	return $altura;
}

/*

$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6,'DATOS DEL ASOCIADO',0,0,'C',1);
$linea+=7;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(32,6,'Nombre del Asociado:',0,0,'L',0);
$pdf->SetX(42);
$pdf->Cell(60,6,$fila2['ape_prof'].''.$fila2['nombr_prof'],0,0,'L',0);
$pdf->SetX(102);
$pdf->Cell(13,6,'Cédula:',0,0,'L',0);
$pdf->SetX(115);
$pdf->Cell(40,6,$fila2['ced_prof'],0,0,'L',0);
$pdf->SetX(155);
$pdf->Cell(13,6,'Código:',0,0,'L',0);
$pdf->SetX(168);
$pdf->Cell(32,6,$fila2['cod_prof'],0,0,'L',0);
$linea+=7;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
$header=array('Fecha','Haberes Socio','Haberes Patrono','Descripción','Monto de Retiro','Fecha de Retiro');
//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',7);
//Cabecera
    $w=array(20,25,25,70,25,25);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],5,$header[$i],1,0,'C',1);
		$pdf->Ln();
//Restauración de colores y fuentes
  $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
	//Buscamos y listamos los proveedores
	 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
//	echo 'orden '.$orden;
//	echo 'ord '.$ord;
//	$ord= $orden;
$linea+=3;
	while ($row = mysql_fetch_array($result))
//	echo $sql;
       {
	 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
		 $linea+=5;
		 $pdf->SetY($linea);
		 $pdf->Cell($w[0],5,$row["fechax"],0,0,'C',0); 
		 $pdf->Cell($w[1],5,$row ["hab_prof"],0,0,'R',0);  
		 $pdf->Cell($w[2],5,number_format($row["hab_ucla"],2,".",","),0,0,'R',0);
		 $pdf->Cell($w[3],5,trim($row["descri"]),0,0,'L',0);
		 if ($linea>=245){
		 	$pdf->AddPage();
						$linea=25;
$linea=30;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"H I S T O R I A L E S   D E   H A B E R E S",0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
$linea+=2;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6,'DATOS DEL ASOCIADO',0,0,'C',1);
$linea+=7;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(32,6,'Nombre del Asociado:',0,0,'L',0);
$pdf->SetX(42);
$pdf->Cell(60,6,$fila2['ape_prof'].''.$fila2['nombr_prof'],0,0,'L',0);
$pdf->SetX(102);
$pdf->Cell(13,6,'Cédula:',0,0,'L',0);
$pdf->SetX(115);
$pdf->Cell(40,6,$fila2['ced_prof'],0,0,'L',0);
$pdf->SetX(155);
$pdf->Cell(13,6,'Código:',0,0,'L',0);
$pdf->SetX(168);
$pdf->Cell(32,6,$fila2['cod_prof'],0,0,'L',0);
$linea+=7;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
$header=array('Fecha','Haberes Socio','Haberes Patrono','Descripción','Monto de Retiro','Fecha de Retiro');
//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',7);
//Cabecera
    $w=array(20,25,25,70,25,25);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],5,$header[$i],1,0,'C',1);
		$pdf->Ln();
//Restauración de colores y fuentes
  $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
	 $linea+=3;
	//Buscamos y listamos los proveedores
	 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
//	echo 'orden '.$orden;
//	echo 'ord '.$ord;
//	$ord= $orden;
		 }
		 }
		$sql="select montoreti,date_format(fechareti, '%d/%m/%Y') AS fechaz FROM ".$_SESSION['bdd']."_sgcaf700 WHERE codsoc='$codigo' ORDER BY fechareti";
$resultado=mysql_query($sql);
        //Restauración de colores y fuentes
  $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
while($row1=mysql_fetch_assoc($resultado)) {
$linea+=5;
		 $pdf->SetY($linea);
		 $pdf->Cell($w[0],5,$row[""],0,0,'C',0); 
		 $pdf->Cell($w[1],5,$row [""],0,0,'R',0);  
		 $pdf->Cell($w[2],5,$row[""],0,0,'R',0);
		 $pdf->Cell($w[3],5,$row[""],0,0,'R',0);
         $pdf->Cell($w[4],5,number_format($row1["montoreti"],2,".",","),0,0,'R',0);
         $pdf->Cell($w[5],5,$row1["fechaz"],0,0,'R',0);		
		 
		 
		 if ($linea>=245){
		 	$pdf->AddPage();
						$linea=25;
$linea=30;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"H I S T O R I A L E S   D E   H A B E R E S",0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
$linea+=2;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6,'DATOS DEL ASOCIADO',0,0,'C',1);
$linea+=7;
$pdf->SetFont('Arial','B',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(32,6,'Nombre del Asociado:',0,0,'L',0);
$pdf->SetX(42);
$pdf->Cell(60,6,$fila2['ape_prof'].''.$fila2['nombr_prof'],0,0,'L',0);
$pdf->SetX(102);
$pdf->Cell(13,6,'Cédula:',0,0,'L',0);
$pdf->SetX(115);
$pdf->Cell(40,6,$fila2['ced_prof'],0,0,'L',0);
$pdf->SetX(155);
$pdf->Cell(13,6,'Código:',0,0,'L',0);
$pdf->SetX(168);
$pdf->Cell(32,6,$fila2['cod_prof'],0,0,'L',0);
$linea+=7;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
$header=array('Fecha','Haberes Socio','Haberes Patrono','Descripción','Monto de Retiro','Fecha de Retiro');
//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',7);
//Cabecera
    $w=array(20,25,25,70,25,25);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],5,$header[$i],1,0,'C',1);
		$pdf->Ln();
//Restauración de colores y fuentes
  $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
	 $linea+=3;
	//Buscamos y listamos los proveedores
	 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
//	echo 'orden '.$orden;
//	echo 'ord '.$ord;
//	$ord= $orden;
		 }
		 }	 
}
}
*/


	/*
	
	$pdf->SetY($linea);
	$pdf->SetFont('Arial','',7);
	$linea+=5;
	$pdf->SetX(215);
	$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 
	$linea+=2;
	$pdf->SetY($linea);
	$pdf->SetX(10);
	$pdf->SetFillColor(200,200,200);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.2);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(0,6,'DATOS DEL ASOCIADO',0,0,'C',1);
	$linea+=7;
	$pdf->SetFont('Arial','B',8);
	$pdf->SetY($linea);
	$pdf->SetX(10);
	$pdf->Cell(32,6,'Nombre del Asociado:',0,0,'L',0);
	$pdf->SetX(42);
	$pdf->Cell(60,6,$fila2['ape_prof'].''.$fila2['nombr_prof'],0,0,'L',0);
	$pdf->SetX(102);
	$pdf->Cell(13,6,'Cédula:',0,0,'L',0);
	$pdf->SetX(115);
	$pdf->Cell(40,6,$fila2['ced_prof'],0,0,'L',0);
	$pdf->SetX(155);
	$pdf->Cell(13,6,'Código:',0,0,'L',0);
	$pdf->SetX(168);
	$pdf->Cell(32,6,$fila2['cod_prof'],0,0,'L',0);
	$linea+=7;
	$pdf->SetY($linea);
	$pdf->SetX(10);
	$pdf->SetFillColor(200,200,200);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetLineWidth(.2);
	$pdf->SetFont('Arial','B',8);
	$header=array('Fecha','Haberes Socio','Haberes Patrono','Descripción','Monto de Retiro','Fecha','Haberes Socio','Haberes Patrono','Descripción','Monto de Retiro'); // ,'Fecha de Retiro');
	//Colores, ancho de línea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',7);
	//Cabecera
    $w=array(15,20,20,40,20,  15,20,20,40,20);
	$p=array(10,25,45,65,105, 125,140,160,180,220);
    for($i=i+n;$i<count($header);$i++)
	    $pdf->Cell($w[$i],5,$header[$i],1,0,'C',1);
		$pdf->Ln();
	//Restauración de colores y fuentes
	$pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);
	$linea+=3;
	return $linea;
}

function mostrar ($result, &$pdf, &$linea, $w, $p, $fila2)
{
	$alto=4;
	$posicion=0;
	while ($row = mysql_fetch_array($result))
//	echo $sql;
       {
	 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
		 $pdf->SetY($linea);
		 $pdf->SetX($p[$posicion]);
		 $pdf->Cell($w[0],$alto,$row["fechax"],$cuadro,0,'C',0); 
		if ($row['descri']!='Retiro de Haberes')
		{
			$posicion++;
			$pdf->SetX($p[$posicion]);
			$pdf->Cell($w[1],$alto,number_format($row["hab_prof"],2,".",","),$cuadro,0,'R',0);  
			$posicion++;
			$pdf->SetX($p[$posicion]);
			$pdf->Cell($w[2],$alto,number_format($row["hab_ucla"],2,".",","),$cuadro,0,'R',0);
			$posicion++;
			$pdf->SetX($p[$posicion]);
			$pdf->Cell($w[3],$alto,trim($row["descri"]),$cuadro,0,'L',0);
			$posicion++;
			$posicion++;
		}
		else 
		{
			$posicion++;
			$posicion++;
			$posicion++;
			$cuadro=1;
			$pdf->SetX($p[$posicion]);
			$pdf->Cell($w[3],$alto,trim($row["descri"]),$cuadro,0,'L',0);
			$posicion++;
			$pdf->SetX($p[$posicion]);
			$pdf->Cell($w[4],$alto,number_format($row["hab_prof"]+$row['hab_ucla']+$row['ret_opsu'],2,".",","),$cuadro,0,'R',0);
			$posicion++;
			$cuadro=0;
		}
		if ($posicion>8)
		{
			$posicion=0;
			 $linea+=$alto;
		}
		 if ($linea>=185){
			 encabezado($linea,$pdf,$fila2, $w, $p);
			 $linea+=$alto;
		 }

		 }
}
*/

?>