<?php
session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);

include("conex.php");
include("funciones.php");

$suma=$reintegros=0;
// $suma='xx--'.$_GET['totalregistros'].'--xx';
$losregistros=$_GET['totalregistros'];
$tipo=$_GET['tipo'];
// $montoprestamo=$_GET['montoprestamo'];
$saldo=0;
for ($i=0;$i<$losregistros;$i++)
{
//	${'cancelar'.$i};
//	$variable=${'cancelar'.$i};
	$variable='cancelar'.($i+1);
//	echo 'la variable '.$$variable;
	if ($tipo == 'Laboratorio')
		$sql="select * from ".$_SESSION['bdd']."_costoslaboratorio where (nroregistro ='".$$variable."')";
	else 
		$sql="select * from ".$_SESSION['bdd']."_costos where (idregistro ='".$$variable."')";
//		echo $sql;
	$acosto=mysql_query($sql);
	$rcosto=mysql_fetch_assoc($acosto);
	$saldo+=$rcosto['costo'];
}
$aqui=$saldo;
$xconsumir=$cupo-($consumido+$aqui);

header("Content-Type: text/xml");
echo '<?xml version="1.0" encoding="utf-8"?>';
echo "<resultados>";
// echo utf8_encode("<cuota>$cuota</cuota>");		// sirve asi y como esta abajo tambien
echo "<aqui>".number_format($aqui,2,'.','')."</aqui>";
echo "<xconsumir>".number_format($xconsumir,2,'.','')."</xconsumir>";
echo "</resultados>";


?>