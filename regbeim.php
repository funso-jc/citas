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

// $link = @mysql_connect("localhost","root", "",'',65536) or die ("<p /><br /><p /><div style='text-align:center'>En estos momentos no hay conexi�n con el servidor, int�ntalo m�s tarde.</div>");
// mysql_select_db($_POST['sica'], $link);

echo '<script language="javascript">alert("No se imprime planilla porque no se tiene un modelo")</script>';
/*
session_start();
header('Content-type: application/pdf');

// include("fpdf/a_cookies.php");

extract($_GET);
extract($_POST);
extract($_SESSION);

include("conex.php");
if (!$link OR !$_SESSION['empresa']) {
	include("head.php");
	header("location: noempresa.php");
	exit;
}


define('FPDF_FONTPATH','fpdf/font/');
require('fpdf/mysql_table.php');
include("fpdf/comunes.php");
// include ("conex.php"); 

$pdf=new PDF('P','mm','Letter');
$pdf->Open();
$pdf->AddPage();

$linea=25;
$pdf->SetY($linea);
$pdf->SetX(0);
$concepto='A';
$pdf->MultiCell(0,0,"Planilla de Pre-Inscripci�n para ".($concepto=='I'?'Ingreso':$concepto=='R'?'Reingreso':'Actualizaci�n'),0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',7);
$linea+=5;
$pdf->SetX(165);
$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 

$consulta = "select * from ".$_SESSION['bdd']."_sgcaf200 where ced_prof='$cedula'";
$query = mysql_query($consulta);
		  
$socio = mysql_fetch_array($query);
$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(0,6,'Datos del Solicitante',0,0,'C',1);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);
$linea+=5;
$pdf->SetFont('Arial','',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(60,6,'Apellido(s) '.$socio['ape_prof'],0,0,'L',0);
$pdf->SetX(70);
$pdf->Cell(60,6,'Nombre(s) '.$socio['nombr_prof'],0,0,'L',0);
$pdf->SetX(130);
$pdf->Cell(40,6,'C�dula '.$socio['ced_prof'],0,0,'L',0);
$pdf->SetX(170);
$pdf->Cell(20,6,'C�digo '.$socio['cod_prof'],0,0,'L',0);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(60,6,'Fecha Nacimiento '.convertir_fechadmy($socio['fnaci_prof']),0,0,'L',0);
$pdf->SetX(70);
$pdf->Cell(50,6,'Lugar Nacimiento '.$socio['lnaci_prof'],0,0,'L',0);
$pdf->SetX(130);
$pdf->Cell(40,6,'Estado Civil '.$socio['civil_prof'],0,0,'L',0);
//$pdf->SetX(170);
// $pdf->Cell(20,6,'C�digo '.$socio['cod_prof'],0,0,'L',0);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,6,'Direcci�n '.trim($socio['dire1_prof']) . ' '.trim($socio['dire2_prof']),0,0,'L',0);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,6,'Tel�fono '.$socio['telf_prof'],0,0,'L',0);
$pdf->SetX(70);
$pdf->Cell(60,6,'Celular(es) '.$socio['celn_prof'] . ' / '.$socio['cel2n_prof'],0,0,'L',0);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(120,6,'E-mail '.trim($socio['mail_prof']),0,0,'L',0);
$pdf->SetX(130);
$pdf->Cell(60,6,'Sexo '.($socio['sexo_prof']==1?'Masculino':'Femenino'),0,0,'L',0);



$linea+=10;
$pdf->SetX(0);
$pdf->SetY($linea);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(0,6,'Informaci�n Laboral',0,0,'C',1);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);
$linea+=5;
$pdf->SetFont('Arial','',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(60,6,'Dependencia ' .$socio['nombr_empr'],0,0,'L',0);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(60,6,'Direcci�n ' .trim($socio['dire1_empr']). ' ' .trim($socio['dire2_empr']),0,0,'L',0);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(40,6,'Tel�fono ' .$socio['tele_empr'],0,0,'L',0);  
$pdf->SetX(50);
$pdf->Cell(25,6,'Extensi�n ' .$socio['ext_empr'],0,0,'L',0);
$pdf->SetX(75);
$pdf->Cell(35,6,'Fax '.$socio['fax_empr'],0,0,'L',0);
$pdf->SetX(110);
$pdf->Cell(45,6,'Cargo '.$socio['cargo'],0,0,'L',0);
$pdf->SetX(155);

$elcescuela=$socio['escuela'];
$sql="select codigo, nombre from ".$_SESSION['bdd']."_escuelas where codigo = '$elcescuela'";
$resultado=mysql_query($sql);
$fila2 = mysql_fetch_assoc($resultado);
$pdf->Cell(45,6,'Dependencia '.$fila2['nombre'],0,0,'L',0);
$eldecanato=$fila2['nombre'];

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$elcdpto=$socio['dept_prof'];
$sql="select escdpto, escuela from ".$_SESSION['bdd']."_sgcafeyd where escdpto = '$elcdpto'";
$resultado=mysql_query($sql);
$fila2 = mysql_fetch_assoc($resultado);
$pdf->Cell(90,6,'Departamento ' .$fila2['escuela'],0,0,'L',0);

$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(0,6,'Informaci�n Administrativa Interna',0,0,'C',1);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);

$linea+=5;
$pdf->SetFont('Arial','',8);
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(70,6,'Tipo de Afiliado ' .$socio['tipo_prof'],0,0,'L',0);
$pdf->SetX(70);
$pdf->Cell(100,6,'Fecha Ingreso '.convertir_fechadmy($socio['f_ing_capu']),0,0,'L',0);
$pdf->SetX(170);
$pdf->Cell(40,6,'Estatus '.$socio['statu_prof'],0,0,'L',0);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(70,6,'Tipo de Cuenta ' .$socio['tipo_cuenta'],0,0,'L',0);
$pdf->SetX(70);
$pdf->Cell(100,6,'Nro. Cuenta '.$socio['cta_nva'],0,0,'L',0);
$pdf->SetX(170);
$pdf->Cell(30,6,'% Retenci�n '.number_format($socio['aport_prof'],2,".",","),1,0,'L',0);

$consulta = "select * from ".$_SESSION['bdd']."_sgcaf220 where ced_afi='$cedula' order by afi_afi desc";
$query = mysql_query($consulta);
if (mysql_num_rows($query) > 0) {
$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(0,6,'Datos de Grupo Familiar',0,0,'C',1);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);

//T�tulos de las columnas
$linea+=5;
$pdf->SetY($linea);
$header=array('C�dula','Apellidos y Nombres','Parentesco','F.Nacimiento','Edad','% Herencia','S.V');

//Colores, ancho de l�nea y fuente en negrita
    $pdf->SetFillColor(200,200,200);
    $pdf->SetTextColor(0);
    $pdf->SetDrawColor(0,0,0);
    $pdf->SetLineWidth(.2);
    $pdf->SetFont('Arial','B',8);
	
//Cabecera
    $w=array(20,60,30,40,20,16,10);
    for($i=0;$i<count($header);$i++)
        $pdf->Cell($w[$i],7,$header[$i],1,0,'C',1);
    $pdf->Ln();
	
//Restauraci�n de colores y fuentes

    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',7);

//Buscamos y listamos los proveedores


$linea+=3;

while ($row = mysql_fetch_array($query))
        {
		
		 //posicion celda, alto,contenido,bordes que mostramos(left,right top botton),0, alineacion izquierda,relleno
		 //imprimo nombre, apellidos y localidad
		 $linea+=5;
		 $pdf->SetY($linea);
		 $pdf->Cell($w[0],5,$row["afi_afi"],0,0,'LRTB',0); 
		 $acotado = substr($row["nom_afi"], 0, 45);
         $pdf->Cell($w[1],5,$acotado,0,0,'LRTB',0);  
		 $pdf->Cell($w[2],5,$row["par_afi"],0,0,'LRTB');
		 $pdf->Cell($w[3],5,convertir_fechadmy($row["nac_afi"]),0,0,'LRTB',0);
         $pdf->Cell($w[4],5,cedad(convertir_fechadmy($row["nac_afi"])),0,0,'LRTB',0);
	     $pdf->Cell($w[5],5,number_format($row["por_afi"],2,".",","),0,0,'R',0);
		 $pdf->Cell($w[6],5,($row["sv_afi"]==1?'Si':'No'),0,0,'LRTB');
        };
}
$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->Cell(0,6,'SOLO PARA USO DEL CONSEJO DE ADMINISTRACION',0,0,'C',1);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);

$pdf->SetFont('Arial','',8);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(60,6,'Aprobado su ingreso e inclusi�n en N�mina desde el d�a _______ del mes de ___________ de __________ ',0,0,'L',0);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(60,6,'Negado (   )   Observaci�n___________________________________________________________________________________________ ',0,0,'L',0);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(60,6,'________________________________________________________________________________________________________ ',0,0,'L',0);
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(60,6,'Acta N�________________________________Fecha______________________________________________________________ ',0,0,'L',0);


$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFillColor(200,200,200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetLineWidth(.2);
$pdf->Cell(0,6,'CONSEJO DE ADMINISTRACION',0,0,'C',1);
$pdf->SetFillColor(255,255,255);
$pdf->SetTextColor(0);

$pdf->SetFont('Arial','',8);
//T�tulos de las columnas
$linea+=5;
$pdf->SetY($linea);
$header=array('Presidente(a)','Tesorero(a)','Secretario(a)','Sello');
$w=array(50,50,50,40);
for($i=0;$i<count($header);$i++)
      $pdf->Cell($w[$i],25,'',1,0,'C',1);
$pdf->Ln();
// $linea+=5;
$pdf->SetY($linea);
for($i=0;$i<count($header);$i++)
      $pdf->Cell($w[$i],3,$header[$i],0,0,'C',0);
$linea+=5;
$pdf->SetY($linea);
$header=array('_____________','___________','_____________','');
for($i=0;$i<count($header);$i++)
      $pdf->Cell($w[$i],15,$header[$i],0,0,'C',0);
$pdf->Ln();
$linea+=5;
$pdf->SetY($linea);
$header=array('Firma','Firma','Firma','');
for($i=0;$i<count($header);$i++)
      $pdf->Cell($w[$i],15,$header[$i],0,0,'C',0);

$linea+=20;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,0,'Firma Conforme',0,0,'C'); 
$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,0,'________________________',0,0,'C'); 
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,0,'C.I.:___________________',0,0,'C'); 

// SetAutoPageBreak();
$pdf->AddPage();
$pdf->SetX(0);

$linea=45;
$pdf->SetY($linea);
$pdf->SetX(0);
$concepto='A';
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"A U T O R I Z A C I O N   D E   D E S C U E N T O",0,C,0);
$pdf->SetY($linea);
$pdf->SetFont('Arial','',10);
$linea+=15;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(10,0,'     Yo, ',0,0,'L'); 
$pdf->SetFont('Arial','BU',10);
$pdf->SetX(25);
$pdf->Cell(100,0,trim($socio['ape_prof']).' '.trim($socio['nombr_prof']),0,0,'L'); 
$pdf->SetFont('Arial','',10);
$pdf->SetX(130);
$pdf->Cell(60,0,'mayor de edad, titular de la C�dula ',0,0,'L'); 
$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(25,0,'de Identidad ',0,0,'L'); 
$pdf->SetFont('Arial','BU',10);
$pdf->SetX(35);
$pdf->Cell(20,0,trim($socio['ced_prof']),0,0,'L'); 
$pdf->SetFont('Arial','',10);
$pdf->SetX(60);
$pdf->Cell(85,0,'trabajador(a) Obrero(a) del Decanato o Dependencia, ',0,0,'L'); 
$pdf->SetFont('Arial','BU',10);
$pdf->SetX(145);
$pdf->Cell(40,0,trim($eldecanato),0,0,'L'); 
$pdf->SetX(180);
$pdf->SetFont('Arial','',10);
$linea+=10;
$pdf->SetY($linea);
$sql="select * from ".$_SESSION['bdd']."_sgcaf100";
$query = mysql_query($sql);
$rempresa = mysql_fetch_assoc($query);
$cadena='de la '.trim($rempresa['patron_empr']).'. Autorizo al Consejo de Administraci�n de '.trim($rempresa['nombr1_empr']).' '.trim($rempresa['nombr2_empr']).' ('.trim($rempresa['alias_empr']).'), para que proceda a descontar la cantidad del '.number_format($socio['aport_prof'],2,".",",").'% de mi salario, por concepto de cuota como socio de '.trim($rempresa['alias_empr']);
$pdf->SetX(10);
$extraer=110;
$pdf->Cell($extraer,0,substr($cadena,0,$extraer),0,0,'L'); 
$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell($extraer,0,substr($cadena,$extraer,$extraer),0,0,'L'); 
$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell($extraer,0,substr($cadena,$extraer*2,$extraer),0,0,'L'); 
$linea+=40;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,0,'Firma Conforme',0,0,'C'); 
$linea+=20;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,0,'________________________',0,0,'C'); 
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,0,'C.I.:___________________',0,0,'C'); 
$linea+=65;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(0,0,'Barquisimeto: '.date('d/m/Y'),'R',0,0); 



$pdf->AddPage();
$pdf->SetX(0);

$linea=45;
$pdf->SetY($linea);
$pdf->SetX(0);
$concepto='A';
$pdf->SetFont('Arial','B',14);
$pdf->MultiCell(0,0,"AUTORIZACION DE DOMICILIACION DE PAGO CON CARGO EN CUENTA",0,C,0);
$linea+=5;
$pdf->SetY($linea);
$pdf->MultiCell(0,0,"(PERSONA NATURAL)",0,C,0);

$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFont('Arial','',10);
$pdf->Cell(10,0,'Se�ores:',0,0,'L'); 
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(10,0,'Banco Provincial S.A. Banco Universal',0,0,'L'); 
$linea+=5;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->Cell(10,0,'Presente.-',0,0,'L'); 

$linea+=10;
$pdf->SetY($linea);
$pdf->SetX(10);
$pdf->SetFont('Arial','',10);
$cuento ='   Yo, '.trim($socio['ape_prof']).' '.trim($socio['nombr_prof']).', titular de la C�dula de Identidad '.trim($socio['ced_prof']).' por la presente autorizo de manera amplia y suficiente, al Banco Provincial S.A. Banco Universal, (en lo sucesivo "El Banco"),';
$cuento.=' para debitar con cargo a mi cuenta N� '.trim($socio['nro_cuenta']).', que mantengo en "El Banco", las cantidades de dinero ';
$cuento.='correspondiente al pago de los recibos de facturaci�n que emita la ';
$cuento.=trim($rempresa['nombr1_empr']).' '.trim($rempresa['nombr2_empr']).' ('.trim($rempresa['alias_empr']).'), (en lo sucesivo denominado �La Prestadora del Servicio�), por conceptos de consumos derivados de mi afiliaci�n al Plan  Descuento a Banco. 
Considerando que los d�bitos autorizados no son efectuados por �El Banco� en fecha fijas, me comprometo, a fin de que pueda ejecutarse la presente autorizaci�n, a mantener en mi referida cuenta provisi�n de fondos suficientes y disponibles de forma tal que �EL Banco�, pueda efectuar, con cargo a la misma, los d�bitos autorizados. En consecuencia, expresamente libero a �El Banco� de toda responsabilidad con respecto de aquellas facturas que no resulten pagadas mediante d�bito a la cuenta, por no haber habido en la misma provisi�n de fondos disponibles y suficientes al momento de que debi� procesarse por parte de �El Banco� el pago autom�tico correspondiente a favor de la �Prestadora del Servicio�. No obstante lo expresado, si �El Banco� llega a pagar a la �Prestadora del Servicio�. por concepto de una facturaci�n de servicio a mi nombre una cantidad en exceso de la provisi�n de fondos existentes en mi cuenta, se considera esa cantidad pagada en exceso como el otorgamiento de un cr�dito a mi favor, por parte de �El Banco� En este supuesto, tal cr�dito ser� exigible por �El Banco� a partir del vencimiento del mes calendario en el cual �El Banco� efect�e el pago en exceso de la provisi�n, reserv�ndose �El Banco� el derecho de cobrarme intereses al tipo m�ximo cobrado por �El Banco� por concepto de sobregiros en cuenta. No obstante si en la cuenta llegase haber fondos disponibles, (provisi�n), para cubrir tal cr�dito total o parcialmente, �El Banco� podr� proceder a imputar tales cantidades, (provisi�n), al pago de los intereses y de capital derivado de la obligaci�n mencionada.
Con el otorgamiento de la presente autorizaci�n expresamente libero a �El Banco� de toda responsabilidad si alg�n recibo de facturaci�n emite a mi nombre por la �Prestadora del Servicio� no resulta pagado por este mecanismo de domiciliaci�n de pago con cargo a mi cuenta, por causas imputables a �El Banco�.
La presente autorizaci�n queda sujeta a la normativa que regula mi referida cuenta con �El Banco� y adem�s los t�rminos y condiciones siguientes:
1.	La revocatoria de la presente autorizaci�n deber� hacerla mediante comunicaci�n escrita dirigida a �El Banco�, y consignada en la agencia u oficina correspondiente a mi cuenta, con por lo menos sesenta (60) d�as de anticipaci�n a la fecha en que dicha revocatoria deba tener efecto. Como quiera que el plazo de sesenta (60) d�as se otorga en beneficio de El Banco �ste podr� renunciar al mismo y dar plenos efectos a la revocatoria en cualquier momento antes de que hubiesen transcurridos sesenta (60) d�as sin que requiera de su parte ning�n acto o aviso.
2.	La notificaci�n de cualquier modificaci�n o cambio que con relaci�n la presente autorizaci�n deba hac�rsele a la �Prestadora del Servicio� quedar� bajo mi �nica responsabilidad.
3.	Para todos sus efectos derivados  y consecuencias convengo que sea la ciudad de Caracas el domicilio especial, sin perjuicio para �El Banco� de poder ocurrir a otro conforme a la Ley, si lo considera conveniente.
La presente Autorizaci�n se otorga en fecha: '.date('d/m/Y').'


																			__________________________
																			FIRMA TITULAR DE LA CUENTA';

// $pdf->Cell(0,0,$cuento,0,0,'L'); 
$pdf->MultiCell(0,4,$cuento,0,'L'); 



// $pdf->MultiCell(0,8,$cadena,1,0,'L'); 


//$pdf->Cell(20,0,'Realizada el '.date('d/m/Y h:i A'),0,0,'C'); 


// $pdf->Cell(array_sum($w),0,'','T');	 
$pdf->Output();

function convertir_fechadmy($mifecha)
{
//	$mifecha=strtotime($mifecha);
//	echo $mifecha;
	$a=explode("-",$mifecha); 
	$elano=substr($a[0],0,2);
	if ($elano="20") $b=$a[2]."/".$a[1]."/".$a[0];
	else $b=$a[2]."/".$a[1]."/"."20".$a[0];
	if ($mifecha=='--') $b='00/00/0000';
return $b;
}

function cedad($fncido)
{

     $fdhoy = explode("@", date('d@m@Y'));
     $fpncido = explode('/', $fncido);

     if($fdhoy[1] == $fpncido[1])
     {
          if($fdhoy[0] >= $fpncido[0])
          {
               $edad = $fdhoy[2] - $fpncido[2];
          }else{
               $edad = $fdhoy[2] - $fpncido[2] - 1;
          }
     }elseif($fdhoy[1] <= $fpncido[1])
     {
          $edad = $fdhoy[2] - $fpncido[2] - 1;
     }elseif($fdhoy[1] > $fpncido[1])
     {
          $edad = $fdhoy[2] - $fpncido[2];
     }

     return $edad . ' a�os ';
}

*/
?> 
