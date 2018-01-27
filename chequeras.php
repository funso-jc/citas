<?php
include("head.php");
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
if ($accion == 'Anadir') 
	$onload="onload=\"foco('cta')\""; 
else
	$onload="onload=\"foco('nactivo')\"";
?>

<body <?php if (!$bloqueo) {echo $onload;}?>>

 
<?php
 
$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$cta = $_GET['cta'];
$_SESSION['nro']=$nro; 
$nactivo=$_GET['nactivo'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}

if ($accion == 'Anadir2') {
    //echo '1'; 
	extract($_POST);
	$codigo = $_POST['codigo'];
	$com_fechamysql=convertir_fecha($fech_reg);
    //echo $nactivo; 
	if ($codigo) {
	//echo '2'; 
	    $sql = "select * from ".$_SESSION['bdd']."_sgcaf845 where nro_ban= '$codigo' and  desde= '$desde' and nro_reg='$registro'";
		$result=mysql_query($sql);
		//echo $sql; 
		if (mysql_num_rows($result) > 0) die ('No se puede registrar esta Chequera ya existe');
        $sql="INSERT INTO ".$_SESSION['bdd']."_sgcaf845 (nro_ban, desde, hasta, fech_reg, ip, status) 
		VALUES ('$codigo', '$desde', '$hasta', '$com_fechamysql', '$ip', '$status')";
		//echo $sql;
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		echo 'REGISTRO REALIZADO EXITOSAMENTE'; 
		$codigo="";
		$accion="";
		}
}

if ($accion=="Borrar_Cheque") {
   // echo '<div id="div1">';
	echo "<form action='chequeras.php?accion=Editar&a=Borrar_Cheque1&codigo=".$codigo."&registro=".$registro."' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";
	$sql= "SELECT * FROM ".$_SESSION['bdd']."_sgcaf843";
	$result=mysql_query($sql);
	echo 'hola'; 
	echo $registro; 
    pantalla_act_borrar($result,$accion,$codigo,$numero,$nombre,$registro);
	echo "<input type = 'submit' value = 'Eliminar'>";
	//echo '</div>';
}

if ($accion=="Borrar_ChequeTodos") {
   // echo '<div id="div1">';
	echo "<form action='chequeras.php?accion=Borrar_ChequeTodos1' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";
	$sql= "SELECT * FROM ".$_SESSION['bdd']."_sgcaf843";
	$result=mysql_query($sql);
	echo 'hola'; 
	echo $registro; 
    pantalla_act_borrar_todos($result,$accion,$codigo,$nombre,$registro);
	echo "<input type = 'submit' value = 'Eliminar'>";
	//echo '</div>';
}


if ($accion=="Reutilizar") {
   // echo '<div id="div1">';
	echo "<form action='chequeras.php?accion=Editar&a=Reutilizar1&codigo=".$codigo."&codigon=".$codigon."&registro=".$registro."' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";
	$sql= "SELECT * FROM ".$_SESSION['bdd']."_sgcaf843";
	$result=mysql_query($sql);
	$nro=$codigo; 
	echo 'hola'; 
	echo $registro; 
    pantalla_act_reutilizar($result,$accion,$codigo,$numero,$nombre,$registro);
	echo "<input type = 'submit' value = 'Reutilizar'>";
	//echo '</div>';
}

if ($accion=="ReutilizarTodos") {
   // echo '<div id="div1">';
	//$sql= "SELECT *FROM sgcaf843";
	//$result=mysql_query($sql);
	$nro=$codigo; 
	//echo 'hola'; 
	echo $n_registros; 
		$sql="SELECT count(banco) as cuantos, banco, registro, nombre_ban FROM ".$_SESSION['bdd']."_sgcaf846, ".$_SESSION['bdd']."_sgcaf843 where estatus='+' and registro<>'$registro' and banco=nro_cta_ba and estado='1' and emitircheque='1' group by registro";
//		echo $sql; 
		    $a='0'; 
			$resultado=mysql_query($sql);
			while ($fila2 = mysql_fetch_assoc($resultado)) {
			if ($fila2['cuantos'] >= $n_registros)
			{
			$a=$a +1; 
			}
			}
			echo $cuantos; 
		if ($a=='0')
		{
		echo '<h2> NO hay banco con esta cantidad de cheques disponibles </ h2>';
		$accion='Editar'; 
		$codigo=$codigo; 
		$registro= $registro; 
		}
		if ($a<>'0')
		{
		echo "<form action='chequeras.php?accion=ReutilizarTodos1' name='form1' method='post' onsubmit='return explicacion_cheque(form1)'>";
		pantalla_act_reutilizartodos($result,$accion,$numero,$codigo,$registro);
		echo "<input type = 'submit' value = 'Reutilizar'>";
		}	 
	
}

if ($accion == 'Desactivar1') {
	extract($_POST);
	$codigo= $_POST['codigo'];
	if ($status == '+')
	{
	$accion='';
	}
	else if ($status == '-') {  
	$sql="UPDATE ".$_SESSION['bdd']."_sgcaf845 SET status