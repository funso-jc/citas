<?php
session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);

include("dbconfig.php");
include("funciones.php");
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
$codigomedico = $codigodr;
$nroconsulta = $nroregistro;
$ahora=ahora($db_con);
$sqlp="UPDATE ".$_SESSION['bdd']."consulta set codigomedico = '$codigodr', pasocons = 1, fechapaso = '$ahora' where numeroconsulta = '$nroconsulta'";
// echo $sqlp;
$resp=$db_con->prepare($sqlp);
try 
{
	$resp->execute();
	$sqlp="UPDATE ".$_SESSION['bdd']."interno set disponible = 0 where codigomedico = '$codigodr'";
	$resp=$db_con->prepare($sqlp);
} 
catch (Exception $e) 
{
 	echo $e->getMessage();
} 

?>