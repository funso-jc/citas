<?php
session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);

include("dbconfig.php");
include("funciones.php");
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}

// reviso... si no ha pasado no lo agrego, si paso lo coloco con el mismo medico
$sqlp="select now() as hoy";
$result=$db_con->prepare($sqlp);
$result->execute();
$fila=$result->fetch(PDO::FETCH_ASSOC);
// echo $fila['hoy'];
$hoy = substr($fila['hoy'],0,10);
$medicoqueatendio='';
/*
$cedulatitular='09377388';
$cedulabeneficiario='09377388';
*/
$sqlp="SELECT * FROM ".$_SESSION['bdd']."consulta WHERE (cedtitular = '$cedulatitular') and (cedbenefic = '$cedulabeneficiario') and substr(fechconsul,1,10) = '$hoy'";
// echo $sqlp;
$result=$db_con->prepare($sqlp);
$result->execute();
$sinproblema = 0;

//echo $sqlp;
//echo '<script language="javascript">alert("llll");</script>';

while($row=$result->fetch(PDO::FETCH_ASSOC)) {
	if ($row['pasocon'] == 0) // aun tiene una cita abierta // no incluir
	{
//		 echo "<script languaje='javascript'>alert('Tiene Cita Abierta... No puede abrir otra')</script>";
		 $sinproblema = 1;
 	}
	if ($row['pasocon'] == 1) $medicoqueatendio = $row['codigomedico'];
}
if ($sinproblema == 0)
{
	$ahora=ahora($db_con);
	$sqlp="INSERT INTO ".$_SESSION['bdd']."consulta (fechconsul, cedtitular, cedbenefic, motivocon, obsgeneral, ip, realizado, codigomedico, nombrepaciente, usuario, pc, anunciado, pasocons, fechapaso, fechaimpr, chequeado, fechatermino, justificativo) VALUES ('$ahora', '$cedulatitular', '$cedulabeneficiario', '$motivo', '$observacion', '$ip', '$ahora', '$medicoqueatendio', '$nombrepaciente', 'x', 'x', '$ahora', 0, '$ahora', '$ahora', 0, '$ahora', 0)";
 	// echo $sqlp;
	try
	{
		$result=$db_con->prepare($sqlp);
		$result->execute();
	}
	catch(PDOException $e){
		echo $e->getMessage();
		 // echo 'Fallo la conexion';
	}
}

//echo '<?xml version="1.0">'; //  encoding="utf-8">';
// echo '<?xml version="1.0" encoding="ISO-8859-1">';
// echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">"; 
header("Content-Type: text/xml");
echo '<?xml version="1.0" encoding="utf-8"?>';
echo "<resultados>";
echo "<sinproblema>".$sinproblema."</sinproblema>";

/*
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
*/	
echo "</resultados>";


?>