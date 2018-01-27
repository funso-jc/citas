<?php
header('Content-type: application/pdf');
/*
error_reporting(E_ALL);
ini_set('display_errors','1');
*/
session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);
include("dbconfig.php");
include("funciones.php");
/*
if (!$link OR !$_SESSION['empresa']) {
    include("head.php");
	//header("location: noempresa.php");
	exit;
} 
*/
define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/fpdf.php');
$pdf=new FPDF('P','mm','Letter');
//$pdf->Open();
$pdf->AddPage();
$sqle="select * from ".$_SESSION['bdd']."configuracion limit 1";
$rese=$db_con->prepare($sqle);
$rese->execute();
$rese=$rese->fetch(PDO::FETCH_ASSOC);
$linea=10;
$altura=5;

$izquierda=10;
$iniciofila=$linea;
$iniciocolumna=$izquierda;
$l1='Nombre Medico: ________________________________';
$l2='MPPS:________ CMEL:________ CI:________________';
$l3='Firma: _______________________________';
$l4='Paciente '.$_SESSION['nombrepaciente'];
$l5='CI: ' .$_SESSION['cedulabeneficiario']. ' Fecha : ' .substr(ahora($db_con),0,10);
$l6='Calle 7 con Prolongacion Avda Moran Diagonal Iglesia Claret';
$l7='Telefono: 0251-2524828. Barquisimeto Estado Lara';
// referencia especialistas
// $linea+=($altura*8);
$sqlt="select * from ".$_SESSION['bdd']."obreros where cedula = '".$_SESSION['cedulatitular']."'";
$rest=$db_con->prepare($sqlt);
$rest->execute();
$filat=$rest->fetch(PDO::FETCH_ASSOC);

$sqlnot="select ".$_SESSION['bdd']."referencias.realizado as fecha, examende as parte1, observacion, instituto as parte2 from ".$_SESSION['bdd']."referencias, ".$_SESSION['bdd']."ninstituto where (numcon_referencia = '".$_SESSION['numeroregistro']."') and ".$_SESSION['bdd']."referencias.tipo = 'Especialista' and ".$_SESSION['bdd']."referencias.especialista=".$_SESSION['bdd']."ninstituto.codmed order by ".$_SESSION['bdd']."referencias.realizado ";
$resultado=$db_con->prepare($sqlnot);
$resultado->execute();
$raya='_____________________________________________________________________';
while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) 
{
	$izquierda=10;
	$iniciofila=$linea;
	$iniciocolumna=$izquierda;
	$altura=encabezado($linea, $pdf, $rese, $altura, 3, $izquierda);
	$r1=$iniciofila.'Referido a: ' .$fila2['parte2'];
	$r2='Fecha '.substr(ahora($db_con),0,10);
	$r3='Titular: '.$filat['ape_nom'].' CI.'.$_SESSION['cedulatitular'];
	$r4='Beneficiario '.$_SESSION['nombrepaciente']. ' CI: ' .$_SESSION['cedulabeneficiario']. ' Edad: '.cedad(convertir_fechadmy($filat["fecha_nac"]));
;
	$linea+=$altura;
	$pdf->SetY($linea);
	$izquierda1=20;
	$izquierda2=150;
	$caracteres=80;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $r1, $caracteres);
	$linea+=$altura;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $r2, $caracteres);
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
	$linea+=$altura;
	$izquierda1+=15;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l1, $caracteres);
	$linea+=$altura;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l2, $caracteres);
	$linea+=$altura;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l3, $caracteres);
//	$linea+=$altura;
//	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l4, $caracteres);
//	$linea+=$altura;
//	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l5, $caracteres);
	$izquierda1-=15;
	$linea+=$altura;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l6, $caracteres);
	$linea+=$altura;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l7, $caracteres);
	
	
	$finalfila=$linea+($altura*5);
	$pdf->Rect($iniciocolumna, $iniciofila, 200, ($finalfila-$iniciofila), '');
}

// fin referencia especialistas

// referencia laboratorio
$linea+=($altura*8);
$iniciofila=$linea;
$iniciocolumna=$izquierda;
$sqlt="select * from ".$_SESSION['bdd']."obreros where cedula = '".$_SESSION['cedulatitular']."'";
$rest=$db_con->prepare($sqlt);
$rest->execute();
$filat=$rest->fetch(PDO::FETCH_ASSOC);
$sqlnot="select ".$_SESSION['bdd']."referencias.realizado as fecha, examende as parte1, observacion, especialista, instituto as parte2 from ".$_SESSION['bdd']."referencias, ".$_SESSION['bdd']."instituto where (numcon_referencia = '".$_SESSION['numeroregistro']."') and ".$_SESSION['bdd']."referencias.tipo = 'Laboratorio' and ".$_SESSION['bdd']."referencias.especialista=".$_SESSION['bdd']."instituto.codmed group by referencias.realizado, especialista,examende, observacion, especialista, instituto order by ".$_SESSION['bdd']."referencias.realizado ";
//echo $sqlnot;
$resultadoe=$db_con->prepare($sqlnot);
$resultadoe->execute();
while ($fila1 = $resultadoe->fetch(PDO::FETCH_ASSOC)) {
	$sqlnot="select realizado as fecha, examende as parte1, observacion as parte2 from ".$_SESSION['bdd']."referencias where (numcon_referencia = '".$_SESSION['numeroregistro']."') and tipo = 'Laboratorio' and especialista = '".$fila1['especialista']."' order by realizado ";
	$resultado=$db_con->prepare($sqlnot);
	$resultado->execute();

	$izquierda=10;
	$iniciofila=$linea;
	$iniciocolumna=$izquierda;
	$altura=encabezado($linea, $pdf, $rese, $altura, 3, $izquierda);
	$r1='Referido a: ' .$fila1['parte2'];
	$r2='Fecha '.substr(ahora($db_con),0,10);
	$r3='Titular: '.$filat['ape_nom'].' CI.'.$_SESSION['cedulatitular'];
	$r4='Beneficiario '.$_SESSION['nombrepaciente']. ' CI: ' .$_SESSION['cedulabeneficiario']. ' Edad: '.cedad(convertir_fechadmy($filat["fecha_nac"]));
;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $r1, $caracteres);
	$linea+=$altura;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $r2, $caracteres);
	$linea+=$altura;
	$linea+=$altura;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $r3, $caracteres);
	$linea+=$altura;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $r4, $caracteres);
	$linea+=$altura;
	$cuento = '';
	while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
		if ($cuento == '')
		$parte2=$fila2['parte2'];
		$cuento.=$fila2['parte1'] . '__X__ ';
	}
	$linea+=$altura;
	$pdf->SetY($linea);
	$izquierda1=20;
	$izquierda2=150;
	$caracteres=60;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $cuento, $caracteres);
	$linea=$lineasi;

	$izquierda1+=15;
	$linea+=$altura;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l1, $caracteres);
	$linea+=$altura;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l2, $caracteres);
	$linea+=$altura;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l3, $caracteres);
//	$linea+=$altura;
//	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l4, $caracteres);
//	$linea+=$altura;
//	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l5, $caracteres);
	$izquierda1-=15;
	$linea+=$altura;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l6, $caracteres);
	$linea+=$altura;
	$lineasi=detalle($pdf, $izquierda1, $linea, $altura, $l7, $caracteres);
	$linea+=$altura;

	$finalfila=$linea+($altura*3);
	$pdf->Rect($iniciocolumna, $iniciofila, 200, ($finalfila-$iniciofila), '');
	if ($linea > 150)
	{
		$pdf->AddPage();
		$linea=10;
	}
}

// fin referencia laboratorio

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
		$ancho = 200;
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
		$sql="select montoreti,date_format(fechareti, '%d/%m/%Y') AS fechaz FROM ".$_SESSION['bdd']."sgcaf700 WHERE codsoc='$codigo' ORDER BY fechareti";
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