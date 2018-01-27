<?php

// create user 'sololectura'@'localhost' identified by 'EXZtaZnjpersOyuEtkSf';
$local=false;
if ($local == true)
{
	$Usuario="sm_usuario";
	$Password="t3wp0r@1";
	$Servidor="localhost";
	$bdd="smobrero"; // "smobrerosmobrero";
}
else
{
	$Usuario="jhernandez";
	$Password="nene14";
	$Servidor="localhost";
	$bdd="smobrero"; // "smobrerosmobrero";
}
	try{
		$db_con = new PDO("mysql:host={$Servidor};dbname={$bdd}",$Usuario,$Password);
		$db_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e){
		echo $e->getMessage();
		 // echo 'Fallo la conexion';
	}

?>