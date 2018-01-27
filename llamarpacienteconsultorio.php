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
include("dbconfig.php");
include("funciones.php");

$codigo=$_GET["codigodr"];
$ahora=ahora($db_con);
$sqlp="UPDATE ".$_SESSION['bdd']."internos set disponible = 1, hfdisponibilidad = '$ahora' where (codigomedico = '$codigo')";
$resp=$db_con->prepare($sqlp);
$resp->execute();

$a = $b = 1;
// while ($a == $b)
{
	$sqlp="SELECT * FROM ".$_SESSION['bdd']."consulta where ((pasocons = 1) and codigomedico = '$codigo') order by fechconsul limit 1";
	// echo $sqlp.'<br>';
	$resp=$db_con->prepare($sqlp);
	$resp->execute();
	$retirado = $existe=0;
	$nombretitular = $nombrebeneficiario=" ";
	$cedulatitular = $cedulabeneficiario = $estatus = $parentesco=" ";
	$medico = $codigo;
	if ($resp->rowCount() > 0)
	{
		$b=0;
		$fila=$resp->fetch(PDO::FETCH_ASSOC);
		$titular=0;
		$cedulatitular=$fila['cedtitular'];
		$cedulabeneficiario=$fila['cedbenefic'];
		$nombrepaciente = $fila['nombrepaciente'];
		$motivo = $fila['motivocon'];
		$observacion = $fila['obsgeneral'];
		$numeroregistro=$fila['numeroconsulta'];
		$_SESSION['numeroregistro']=$numeroregistro;
		$_SESSION['cedulatitular']=$cedulatitular;
		$_SESSION['cedulabeneficiario']=$cedulabeneficiario;
		$_SESSION['nombrepaciente']=$nombrepaciente;
		$_SESSION['motivo']=$motivo;
		$_SESSION['observaciong']=$observacion;
		$sql2="select ape_nom from ".$_SESSION['bdd']."obreros where cedula = '$cedulatitular'";
		$res2=$db_con->prepare($sql2);
		$res2->execute();
		$fila2=$res2->fetch(PDO::FETCH_ASSOC);
		$nombretitular=$fila2['ape_nom'];
		$existe = 2;
	}
//	sleep(7);
}

//echo '<?xml version="1.0">'; //  encoding="utf-8">';
// echo '<?xml version="1.0" encoding="ISO-8859-1">';
// echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">"; 
//	echo '<script language="javascript">alert("llame")</script>';

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
	
?>