<?php
session_start();
// $self = $_SERVER['PHP_SELF'];
// header("refresh:7; url=$self");
extract($_GET);
extract($_POST);
extract($_SESSION);
/*
$tiempoentreconsulta = 7;
$maximaespera = $tiempoentreconsulta * 10;
set_time_limit($maximaespera);
*/
include("conex.php");
$numeroregistro=$_GET['numeroregistro'];
$sql = "SELECT COUNT(nrocon_diagnostico) AS cuantos FROM ".$_SESSION['bdd']."_diagnosticos WHERE (nrocon_diagnostico = '$numeroregistro')";
$rs = mysql_query($sql);
$row= mysql_fetch_array($rs);
$numasi = $row[cuantos]; 

$ord="";
pagina($numasi, $conta, 20, "Diagn&oacute;sticos", $ord);
echo "<table class='basica 100 hover' width='100%'><tr>";
echo '<th>Descripci&oacute;n</a></th><th>Diagn&oacute;tico';
echo '[ <a href="diagnostico.php?accion=Anadir">Nuevo Diagn&oacute;stico</a> ]</th>';
echo '<th>Notificaci&oacute;n</a></th><th>Primera Vez</th>';
echo '</tr>';

$sql = "SELECT * FROM ".$_SESSION['bdd']."_diagnosticos WHERE (nrocon_diagnostico = '$numeroregistro')";
$rs = mysql_query($sql);

while($row=mysql_fetch_assoc($rs)) {
	echo "<tr>";
	echo "<td class='centro'>";
	echo "<a href='regespe.php?accion=Editar&lacedula=".$row['codigo']."'>";
	echo $row['codigo']."</a></td>";
	echo "<td class='centro'>";
	echo trim($row['nombre'])."</a></td>";
	echo "</tr>";
}
echo "</table>";

pagina($numasi, $conta, 20, "Diagn&oacute;sticos", $ord);

/*

$codigo=$_GET["codigodr"];
$ahora=ahora();
$sqlp="UPDATE ".$_SESSION['bdd']."_internos set disponible = 1, hfdisponibilidad = '$ahora' where (codigomedico = '$codigo')";
$resp=mysql_query($sqlp);

$a = $b = 1;
// while ($a == $b)
{
	$sqlp="SELECT * FROM ".$_SESSION['bdd']."_consulta where (pasocons = 1 and codigomedico = '$codigo') order by fechconsul limit 1";
	// echo $sqlp.'<br>';
	$resp=mysql_query($sqlp);
	$retirado = $existe=0;
	$nombretitular = $nombrebeneficiario=" ";
	$cedulatitular = $cedulabeneficiario = $estatus = $parentesco=" ";
	$medico = $codigo;
//	echo mysql_num_rows($resp);
	if (mysql_num_rows($resp) > 0)
	{
		$b=0;
		$fila=mysql_fetch_assoc($resp);
		$titular=0;
		$cedulatitular=$fila['cedtitular'];
		$cedulabeneficiario=$fila['cedbenefic'];
		$nombrepaciente = $fila['nombrepaciente'];
		$motivo = $fila['motivocon'];
		$observacion = $fila['obsgeneral'];
		$numeroregistro=$fila['numeroconsulta'];
		$sql2="select ape_nom from ".$_SESSION['bdd']."_titulares where cedula = '$cedulatitular'";
		$res2=mysql_query($sql2);
		$fila2=mysql_fetch_assoc($res2);
		$nombretitular=$fila2['ape_nom'];
		$existe = 2;
	}
//	sleep(7);
}

//echo '<?xml version="1.0">'; //  encoding="utf-8">';
// echo '<?xml version="1.0" encoding="ISO-8859-1">';
// echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">"; 

header("Content-Type: text/xml");
echo '<?xml version="1.0" encoding="utf-8"?>';
echo "<resultados>";
echo "<cedulatitular>".$cedulatitular."</cedulatitular>";
echo "<cedulabeneficiario>".$cedulabeneficiario."</cedulabeneficiario>";
echo "<nombretitular>".$nombretitular."</nombretitular>";
echo "<nombrebeneficiario>".$nombrepaciente."</nombrebeneficiario>";
echo "<motivo>".$motivo."</motivo>";
echo "<existe>".$existe."</existe>";
echo "<medico>".$medico."</medico>";
echo "<observacion>".$observacion."</observacion>";
echo "<numeroregistro>".$numeroregistro."</numeroregistro>";
echo "</resultados>";
*/
	
?>