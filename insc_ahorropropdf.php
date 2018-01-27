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
// phpinfo();
header('Content-type: application/pdf');
session_start();
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
/*
$orden=$_GET['orden'];
$fecha=$_GET['desde'];
if (strtoupper($orden)=='CODIGO')
	$orden='cod_prof';
else if (strtoupper($orden)=='CEDULA')
	$orden='ced_prof';
else if (strtoupper($orden)=='NOMBRE')
	$orden='nombre';
else $orden='ubic_prof';

$sql="select ced_prof, concat(trim(ape_prof),' ',nombr_prof) as nombre, statu_prof, netcheque, ctan_prof from sgcaf200, sgcaf700 where (cod_prof=codsoc) and (aprobado =  '$fecha')"; //   limit 30";*/
$token=$_GET['orden'];
$asiento=$_GET['comprobante'];
$sql="select * from  ".$_SESSION['bdd']."_sgcaf370,  ".$_SESSION['bdd']."_sgcaf200,  ".$_SESSION['bdd']."_sgcaf375 where (sgcaf370.nro_rec = '$token') and ( ".$_SESSION['bdd']."_sgcaf370.cod_prof =  ".$_SESSION['bdd']."_sgcaf200.cod_prof) and ( ".$_SESSION['bdd']."_sgcaf370.nro_rec =  ".$_SESSION['bdd']."_sgcaf375.nro_rec)";
$sql="select * from  ".$_SESSION['bdd']."_sgcaf370,  ".$_SESSION['bdd']."_sgcaf200 where ( ".$_SESSION['bdd']."_sgcaf370.nro_rec = '$token') and ( ".$_SESSION['bdd']."_sgcaf370.cod_prof =  ".$_SESSION['bdd']."_sgcaf200.cod_prof)";
 //  limit 20";
// echo $sql;
$asocio=mysql_query($sql);
$columna=3;
$rpl=300; 	// registros por listado
$crl=0;		// contador de registros por listado
$col_listado=0;
// $arrtitulo="'Lin.Nº','Código','Cédula','Apellidos y Nombres',";
$header[0]='Cuota N°';
$header[1]='Fecha';
$header[2]='Monto';
$header[3]='Amor.Cap.';
$header[4]='Amor.Acum.';
$header[5]='Interes';
$header[6]='Int.Acum.';
$header[7]='Pag.Acum.';
$alto=4;
$salto=$alto;
$w=array(15,20,20,20,20,20,20,20); // ,25,25,25,25,25,25);
$p[0]=30;
for ($posicion=1;$posicion<count($w);$posicion++) 
	$p[$posicion]=$p[$posicion-1]+$w[$posicion-1];

$pdf=new PDF('P','mm','Letter');
$pdf->Open();
$rsocio=mysql_fetch_assoc($asocio);
$linea=encabeza_l_prestamos($header,$w,$p,$pdf,$salto,$alto,$monto,$cuotas,$interes,$cuota,$pinteres,$rsocio,$token);
$ruta=$_SERVER["DOCUMENT_ROOT"]."/cacpcel/soportecontable/".$token.".pdf"; // 
$sql830="update ".$_SESSION['bdd']."_sgcaf830 set enc_soporte = LOAD_FILE('".$ruta."') where enc_clave='".$asiento."'";
//$pdf->Cell(70,$alto,'Recibi Conforme',0,0,'C',0);
/*
$pdf->SetFont('Arial','',10);
$pdf->SetX(0);
$pdf->MultiCell(0,0,$sql830,0,C,0);
*/

$pdf->Output();
$pdf->Output('soportecontable/'.$token.'.pdf');
// $sql830="update cacpcel_sgcaf830 set enc_soporte=LOAD_FILE(\"d:/provinet_colegio_200911.pdf\") WHERE enc_clave='16020501593'";
// update `cacpcel_sgcaf830`  set enc_soporte=LOAD_FILE("d:/provinet_colegio_200911.pdf") WHERE enc_clave='314'
// echo $sql830;
$r830=mysql_query($sql830);

$sql830="update ".$_SESSION['bdd']."_sgcaf830 set enc_desc1 = v"." where enc_clave='".$asiento."'";
$r830=mysql_query($sql830);
set_time_limit(30);
// $pdf->SetX($p[6]);		$pdf->Cell($w[6],$alto,number_format($tcuota,2,'.',','),0,0,'R');

////////////////////////////////////////////////////
function encabeza_l_prestamos($header,$w,$p,&$pdf,$salto,$alto,$monto,$cuotas,$interes,$cuota,$pinteres,$elsocio,$elprestamo)
{
$pdf->AddPage();
$linea=35;
$pdf->SetY($linea);
$pdf->SetX(0);
$pdf->SetFont('Arial','B',18);
$hoy = date("d")."-".date('m')."-".date("Y"); 
$hoy = convertir_fechadmy($_GET['desde']);
$pdf->MultiCell(0,0,"Comprobante de Ingreso Nro. ".$elprestamo,0,C,0);

$pdf->SetFont('Arial','',12);
$pdf->SetY($linea);
$pdf->SetX(170);
$pdf->Cell(20,0,''.date('d/m/Y h:i A'),0,0,'L'); 

$pdf->SetFont('Arial','B',18);
$pdf->SetY($linea);
$linea+=$alto;
$linea+=$alto;
$pdf->SetY($linea);
$pdf->MultiCell(0,0,"Por Bs. ".number_format($elsocio['monto'],2,'.',','),0,C,0);
$pdf->SetY($linea);
$linea+=$alto;
$linea+=$alto;
$linea+=$alto;
$pdf->SetY($linea);
$pdf->SetFont('Arial','',12);
$procesado=convertir_fechadmy($elsocio['proceso']);
$hemos= 'Hemos recibido del socio(a) '.trim($elsocio['ape_prof']). ' '.trim($elsocio['nombr_prof']);
$hemos.=' portador(a) de la Cédula de Identidad '.$elsocio['ced_prof'] .' identificado(a) con el código ';
$hemos.=$elsocio['cod_prof'] .' la cantidad de Bs '.num2letras($elsocio['monto'],true). ' (Bs. ';
$hemos.=number_format($elsocio['monto'],2,'.',',').') por concepto de: '; // .$elsocio['descri1'] ;
//$sql375="select *, sum(monto) as neto from  ".$_SESSION['bdd']."_sgcaf375,  ".$_SESSION['bdd']."_sgcaf310,  ".$_SESSION['bdd']."_sgcaf360 where nro_rec = '$elprestamo' and nropre_sdp=nro_pre and codpre_sdp=cod_pres group by nro_pre, ahorrovol";
$sql375="select *, sum(monto) as neto from  ".$_SESSION['bdd']."_sgcaf375 where nro_rec = '$elprestamo' group by nro_pre, ahorrovol";
//echo $sql375;
$mostrar=0;
if ($mostrar == 1)
	$hemos.=$sql375;
$result=mysql_query($sql375);
while ($r375 = mysql_fetch_assoc($result))
{
	if ($r375['ahorrovol'] == 'C')
		$hemos.=' Pago a prestamo Nro. '.$r375['nro_pre']. ' de '.trim($r375['descr_pres']).' de Bs . '.number_format($r375['neto'],2,',','.').' (Cap.='.number_format($r375['monto'],2,',','.').')/ ';
	if ($r375['ahorrovol'] == 'I')
		$hemos.='(Int.='.number_format($r375['neto'],2,',','.').') / ';
	if ($r375['ahorrovol'] == '')
		$hemos.=' Ahorros Bs . '. number_format($r375['neto'],2,',','.').' '.$r375['nro_pre'].' / ';
	if ($r375['ahorrovol'] == '1')
		$hemos.=' Ahorros Voluntario Bs . '.number_format($r375['neto'],2,',','.').' / ';
	$forma=$r375['forma'];
	$ndeposito=$r375['nro_che'];
	$banco=$r375['banco'];
	$fecha=$r375['fecha'];
}
$sqlbanco="select nombre from  ".$_SESSION['bdd']."_sgcaf000 where tipo=\"FormaPago\" and substr(nombre,1,1)='".$forma."'";
$resultado=mysql_query($sqlbanco);
$fila2 = mysql_fetch_assoc($resultado);
$forma=$fila2['nombre'];

$sqlbanco="select * from  ".$_SESSION['bdd']."_sgcaf843 where recibirpago=1 and cod_banco='$banco'";
$resultado=mysql_query($sqlbanco);
$fila2 = mysql_fetch_assoc($resultado);
$banco=$fila2['nombre_ban'];

//$hemos.=' s/comprobante contable '.$_SESSION['elasiento'].' procesado el '.$procesado.' segun '.$forma .' numero '.$ndeposito .' en '.$banco;
$hemos.=' RECIBIDO ASI '.$forma .' numero '.$ndeposito .' en '.$banco. ' TOTAL RECIBIDO =>'.number_format($elsocio['monto'],2,'.',',');

$pdf->SetX(10);
// $pdf->Cell(20,$alto*8,$hemos,0,0,'L',0);
$pdf->MultiCell(0,$alto*2,$hemos,0,'L'); 

$linea+=($alto*15);
$pdf->SetFont('Arial','I',10);
$pdf->SetY($linea);
$pdf->SetX(10);
$ahorros=ahorros($elsocio['ced_prof']);
$afectan=afectan($elsocio['ced_prof']);
$noafectan=noafectan($elsocio['ced_prof']);
$fianzas=fianzas($elsocio['cod_prof']);
$detalle='Ahorros='.number_format($ahorros,2,'.',',');
$detalle.='/Prestamos Afectan Disp. ='.number_format($afectan,2,'.',',');
$detalle.='/NO Afectan Disp. ='.number_format($noafectan,2,'.',',');
$detalle.='/Fianzas ='.number_format($fianzas,2,'.',',');
$detalle.='/Disponible ='.number_format(disponibilidad($ahorros,$afectan,$noafectan,$fianzas),2,'.',',');
$pdf->MultiCell(0,$alto,$detalle,0,'L'); 

$f=$fecha;
$fecha=substr($fecha,8,2).substr($fecha,5,2).substr($fecha,2,2).substr($fecha,11,2).substr($fecha,14,2);
$pista=$fecha.$_GET['orden'].$_GET['comprobante'];
$pista.=$elsocio['cod_prof'];
$pista.= '(ddmmaahhmm-recibo-comprobante-socio)';
$linea+=($alto);
$pdf->SetFont('Arial','I',10);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->MultiCell(0,$alto,$pista,1,'C'); 


return $linea;
}
?>
