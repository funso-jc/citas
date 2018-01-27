<?php
/*  
     This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
include("head.php");
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
	*/ 
session_start();
header('Content-type: application/pdf');
extract($_GET);
extract($_POST);
extract($_SESSION);
include("conex.php");
if (!$link OR !$_SESSION['empresa']) {
    include("head.php");
	//header("location: noempresa.php");
	exit;
}
 define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/mysql_table.php');
include("fpdf/comunes.php");
require('funciones.php');
ini_set("memory_limit","120M");

$fechadescuento = explode('/',$fechadescuento);
$fechadescuento = $fechadescuento[2].'-'.$fechadescuento[1].'-'.$fechadescuento[0];
$sql1="CREATE TEMPORARY TABLE smoroso SELECT * FROM ".$_SESSION['bdd']."_sgcaf200 WHERE UPPER(statu_prof)='ACTIVO' ORDER BY cod_prof";
$res1=mysql_query($sql1) or die($sql1);
$sql2="CREATE TEMPORARY TABLE moroso SELECT *, DATEDIFF('$fechadescuento',ult_ret) AS diaspasados FROM ".$_SESSION['bdd']."_sgcaf310 WHERE (codsoc_sdp >= '".$inicio."' and codsoc_sdp <= '".$fin."' and (stapre_sdp = 'A' and renovado = 0)) ORDER BY codsoc_sdp";
$res2=mysql_query($sql2) or die($sql2);
$sql2="select * from moroso";
$res2=mysql_query($sql2) or die($sql2);
//echo $sql2.' ='.mysql_num_rows($res2);
$sql3="CREATE TEMPORARY TABLE pmoroso SELECT *, SPACE(20) as tipo, SPACE(45) as nombr_prof, SPACE(5) as cpc_prof, SPACE(40) as dir1_prof, SPACE(40) as dir2_prof, SPACE(60) as mail_prof, SPACE(40) as nombr_empr, SPACE(40) as dire1_empr, SPACE(40) as dire2_empr, SPACE(15) as tele_empr, SPACE(15) as telf_prof, SPACE(15) as celu_prof, SPACE(15) as fax_empr, moroso.ult_ret as ultpago FROM moroso WHERE (diaspasados >= $minimo AND diaspasados <= $maximo) ORDER BY cpc_prof ";
$res3=mysql_query($sql3) or die($sql3);
$sql4="SELECT * from smoroso";
$res4=mysql_query($sql4) or die($sql4);
//echo $sql4.' ='.mysql_num_rows($res4);
//echo $sql1.'<br>'.$sql2.'<br>'.$sql3.'<br>'.$sql4.'<br>';
// los que no tienen prestamos los elimino
while ($r4 = mysql_fetch_assoc($res4)){
	$sql5="select * from pmoroso where codsoc_sdp='".$r4['cod_prof']."'";
	$res5=mysql_query($sql5) or die($sql5);
	if (mysql_num_rows($res5) < 1)
	{
		$sql6="delete from moroso where codsoc_sdp='".$r4['cod_prof']."'";
		$res6=mysql_query($sql6) or die($sql6);
	}
}
/*
$sql4="select * from pmoroso";
$res4=mysql_query($sql4) or die($sql4);
echo $sql4.' ='.mysql_num_rows($res4);
*/
// completo datos
$sql4="SELECT * from pmoroso group by codpre_sdp";
$res4=mysql_query($sql4) or die($sql4);
while ($r4 = mysql_fetch_assoc($res4)){
	$sql5="select * from ".$_SESSION['bdd']."_sgcaf360 where cod_pres='".$r4['codpre_sdp']."'";
	$res5=mysql_query($sql5) or die($sql5);
	$r5=mysql_fetch_assoc($res5);
	$sql6="update pmoroso set tipo='".$r5['descr_pres']."' where codpre_sdp='".$r4['codpre_sdp']."'";
	$res6=mysql_query($sql6) or die($sql6);
}
$sql4="SELECT * from pmoroso group by codsoc_sdp";
$res4=mysql_query($sql4) or die($sql4);
while ($r4 = mysql_fetch_assoc($res4)){
	$sql5="select * from ".$_SESSION['bdd']."_sgcaf200 where cod_prof='".$r4['codsoc_sdp']."'";
	$res5=mysql_query($sql5) or die($sql5);
	$r5=mysql_fetch_assoc($res5);
	$sql6="update pmoroso set nombr_prof='".trim($r5['ape_prof']). ' '.trim($r5['nombr_prof'])."', cpc_prof='".trim($r5['cpc_prof'])."' where codsoc_sdp='".$r4['codsoc_sdp']."'";
//	echo $sql6;
	$res6=mysql_query($sql6) or die($sql6);
}
/*
$sql4="select * from pmoroso";
$res4=mysql_query($sql4) or die($sql4);
echo $sql4.' ='.mysql_num_rows($res4);
*/

// $sql_amor="select * from pmoroso order by codsoc_sdp, codpre_sdp"; //   limit 30"; //  limit 20";
$sql_amor="SELECT * from pmoroso group by codsoc_sdp order by codsoc_sdp";
// echo $sql_amor;
$a_amor=mysql_query($sql_amor) or die($sql_amor);
// echo $sql_amor.' ='.mysql_num_rows($a_amor);
$columna=3;
$rpl=300; 	// registros por listado
$crl=0;		// contador de registros por listado
$col_listado=0;
// $arrtitulo="'Lin.Nº','Código','Cédula','Apellidos y Nombres',";
$header[0]='# Prestamo';
$header[1]='Tipo';
$header[2]='Solicitado';
$header[3]='Ult.Pago';
$header[4]='Tiempo (dias)';
$header[5]='Saldo';
$header[6]='Cuota';
/*
$header[8]='NC/CC';
*/
$alto=3;
$salto=$alto;
$w=array(20,53,20,25,15,15,15); // ,15,20,15,10); // ,25,25,25,25,25,25);
$p[0]=20;
for ($posicion=1;$posicion<count($w);$posicion++) 
	$p[$posicion]=$p[$posicion-1]+$w[$posicion-1];
//$p=array(10,18,31,36,76,91,106,131,136,161,196,221,246);

$pdf=new PDF('P','mm','Letter');
$pdf->Open();
// $registros=mysql_num_rows($a_amor);
// set_time_limit($registros);
$sintitulo=false;
$primeravez = true;
$santerior = $tcuota = $sactual = $tinteres = 0;
$gsanterior = $gtcuota = $gsactual = $gtinteres = 0;
//$r_amor = mysql_fetch_assoc($a_amor);
$elanterior=$r_amor['codpre_sdp'];
$nombreprestamo=$r_amor['descr_pres'];
$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento);
/*
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell(0,$alto,$r_amor['descr_pres'],0,0,'LRTB',0); */
//mysql_data_seek ($a_amor, 0);		// volver al principio de la busqueda
$primeravez=true;
$gt=0;
while ($r_amor = mysql_fetch_assoc($a_amor)){
/*
	if ($primeravez == true)
		$elanterior == $r_amor['cod_prof'];
	else
		$primeravez=false;
	if ($elanterior == $r_amor['cod_prof']) 
	*/{
		$linea+=$salto;
		$pdf->SetY($linea);
		$pdf->SetX($p[0]);		$pdf->Cell($w[0]+$w[1]+$w[2],$alto,$r_amor['nombr_prof'].' / '.$r_amor['codsoc_sdp'].' / '.$r_amor['cod_prog'],0,0,'LRTB',0);
		$codigo=$r_amor['codsoc_sdp'];
		$sqlo="select * from pmoroso where codsoc_sdp='".$r_amor['codsoc_sdp']."' order by codpre_sdp";
		$ao=mysql_query($sqlo);
		$tcuota=0;
		while ($r_amor2 = mysql_fetch_assoc($ao)){
			$linea+=$salto;
			$pdf->SetY($linea);
			$cont++;
//		$pdf->SetX($p[0]);		$pdf->Cell($w[0],$alto,'nropre_sdp',0,0,'LRTB',0);
			$pdf->SetX($p[0]);		$pdf->Cell($w[0],$alto,$r_amor2['nropre_sdp'],0,0,'LRTB',0);
			$pdf->SetX($p[1]);		$pdf->Cell($w[1],$alto,$r_amor2["tipo"],0,0,'LRTB',0); 
			$pdf->SetX($p[2]);		$pdf->Cell($w[2],$alto,$r_amor["f_soli_sdp"],0,0,'LRTB',0);  
			$pdf->SetX($p[3]);		$pdf->Cell($w[3],$alto,convertir_fechadmy($r_amor2["ultpago"]),0,0,'LRTB');
			$pdf->SetX($p[4]);		$pdf->Cell($w[4],$alto,$r_amor2["diaspasados"],0,0,'R');
			$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format(($r_amor2["monpre_sdp"]-$r_amor2["monpag_sdp"]),2,',','.'),0,0,'R');
			$pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format(($r_amor2["cuota"]),2,',','.'),0,0,'R');
			$tcuota+=$r_amor2["monpre_sdp"]-$r_amor2["monpag_sdp"];
			$gtcuota+=$r_amor2["monpre_sdp"]-$r_amor2["monpag_sdp"];
		}
	}
	if ($linea>=220) {
		$linea+=$alto;
		$pdf->SetY($linea);
		$pdf->SetX($p[0]);
		$pdf->Cell(0,0,'  ',1,0,'L',0);
		$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$fechadescuento,$r_amor['descr_pres'], $r_amor['cuent_pres']);
		$sintitulo=false;
		$linea-=$alto;
		}
}

/*
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell(0,0,'  ',1,0,'L',0);
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[3]);		$pdf->Cell($w[3]+$w[4],$alto,'Subtotal '.trim($nombreprestamo),0,0,'R');
$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($santerior,2,'.',','),0,0,'R');
$pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($tcuota,2,'.',','),0,0,'R');

*/
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell(0,0,'  ',1,0,'L',0);
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetX($p[3]);
$pdf->SetFont('Arial','B',7);
$pdf->Cell($w[4],$alto,'Total General',0,0,'L',0);
//$pdf->SetX($p[5]);		$pdf->Cell($w[5],$alto,number_format($gsanterior,2,'.',','),0,0,'R');
$pdf->SetX($p[6]);		$pdf->Cell($w[4],$alto,number_format($gtcuota,2,'.',','),0,0,'R');
$pdf->SetFont('Arial','',7);
$pdf->Output();
set_time_limit(30);

////////////////////////////////////////////////////
function encabeza_l_prestamos($header,$w,$p,&$pdf,$salto,$alto,$fechadescuento)
{
$pdf->AddPage();
$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"Cartas de Cobranza al ".convertir_fechadmy($fechadescuento),0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(220);
$pdf->Cell(20,0,'Realizado el '.date('d/m/Y h:i A'),0,0,'L'); 
/*
//Títulos de las columnas
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetFont('Arial','B',10);
$pdf->SetX($p[0]);
$pdf->Cell(0,$alto,$nombreprestamo. '('.$cuenta.')',0,0,'L',0);
$pdf->SetFont('Arial','',7);
*/
$linea+=5;
$pdf->SetY($linea);
//$header=array($$arrtitulo);
//Colores, ancho de línea y fuente en negrita
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',7);
//Cabecera
for($i=0;$i<count($w);$i++){
	$pdf->SetY($linea);
	$pdf->SetX($p[$i]);
	$pdf->Cell($w[$i],$alto,$header[$i],1,0,'C',1);
}
//Restauración de colores y fuentes
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','',7);
$linea+=$salto;
$linea+=$salto;
$pdf->SetY($linea);
$pdf->SetX($p[0]);
$pdf->Cell(0,0,'  ',1,0,'L',0);
return $linea;
}
?>
