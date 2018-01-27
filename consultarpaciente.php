<?php
session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);
include_once "dbconfig.php";

$cedula=$_GET["cedula"];
$sqlp="SELECT * FROM ".$_SESSION['bdd']."obreros where cedula = '$cedula'";
$resp=$db_con->prepare($sqlp);
$resp->execute();
$retirado = $existe=0;
$nombretitular = $nombrebeneficiario=" ";
$cedulatitular = $cedulabeneficiario = $estatus = $parentesco=" ";
if ($resp->rowCount() > 0)
{
	$fila=$resp->fetch(PDO::FETCH_ASSOC);
	$titular=1;
	$nombrebeneficiario = $nombretitular=$fila['ape_nom'];
	$cedulatitular=$cedula;
	$cedulabeneficiario=$cedula;
	$parentesco='Titular';
	$estatus = $fila['stat_emp'];
	if ($estatus == "")
			$estatus = 'No Definido';
	if (($estatus == 'Activo') or ($estatus == 'Jubilado'))
	{
		$retirado=0;
		$existe=1;
	}
	else $retirado=1;
}
else // puede que sea beneficiario
{
	$sqlp="SELECT * FROM ".$_SESSION['bdd']."familiar, ".$_SESSION['bdd']."obreros where ((cedulafam = '$cedula') and (".$_SESSION['bdd']."familiar.cedula = ".$_SESSION['bdd']."obreros.cedula))";
// echo $sqlp;
	$resp=$db_con->prepare($sqlp);
	$resp->execute();
	$existe=0;
	if ($resp->rowCount() > 0)
	{
		$fila=$resp->fetch(PDO::FETCH_ASSOC);
		$titular=0;
		$cedulatitular=$fila['cedula'];
		$cedulabeneficiario=$cedula;
		// echo 'titular '.$fila['ape_nom'];
		$nombretitular=$fila['ape_nom'];
		$nombrebeneficiario=$fila['ape_nomb'];
		$parentesco=$fila['parentesco'];
		$estatus = $fila['stat_emp'];
		if ($estatus == "")
			$estatus = 'No Definido';
		if (($estatus == 'Activo') or ($estatus == 'Jubilado'))
		{
			$retirado=0;
			$existe=1;
		}
		else $retirado=1;
	}
}

//echo '<?xml version="1.0">'; //  encoding="utf-8">';
// echo '<?xml version="1.0" encoding="ISO-8859-1">';
// echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">"; 
header("Content-Type: text/xml");
echo '<?xml version="1.0" encoding="utf-8"?>';
echo "<resultados>";
echo utf8_encode("<cuota>$cuota</cuota>");		// sirve asi y como esta abajo tambien
echo "<cedulatitular>".$cedulatitular."</cedulatitular>";
echo "<cedulabeneficiario>".$cedulabeneficiario."</cedulabeneficiario>";
echo "<nombretitular>".$nombretitular."</nombretitular>";
echo "<nombrebeneficiario>".$nombrebeneficiario."</nombrebeneficiario>";
echo "<existe>".$existe."</existe>";
echo "<titular>".$titular."</titular>";
echo "<parentesco>".$parentesco."</parentesco>";
echo "<estatus>".$estatus."</estatus>";
echo "<existe>".$existe."</existe>";
echo "</resultados>";
	


?>