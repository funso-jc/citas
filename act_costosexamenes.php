<?php
include("head.php");
include("paginar.php");
/*
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
/*
if ($_GET['emp'] == 1) {$_GET['n'] = 1;}

*/
if ($accion == 'Anadir') 
	$onload="onload=\"foco('lacedula')\""; 
else
	$onload="onload=\"foco('elcodigo')\"";
?>

<body <?php if (!$bloqueo) {echo $onload;}?>>


<?php
$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;
include("menusizda.php");
$cedula = $_GET['cedula'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
// include("conex.php");//CONEXION A LA BASE DE DATOS
$sql = "SELECT * from ".$_SESSION['bdd']."ninstituto where status = 1 order by instituto"; //ABRI LA TABLA CLIENTE 
$rs = $db_con->prepare($sql);
$rs->execute();
?>


<?php
/*
<!DOCTYPE html>
<html>
<head>
</head>


<!--INICIO DE CODIGO PHP PRINCIPAL-->
$var="";
$var1="";
$var2="";
$var3="";
$var4="";
$var5="";
*/
// echo 'txt '.$_POST["txtbus"];
extract($_POST);
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
//echo 'btn2'.$btn2;
// modificar costo examen laboratorio
if ($_POST["btn6"] == "Actualizar Costo")
{
	$ahora=ahora($db_con);
	$sql="UPDATE ".$_SESSION['bdd']."costoslaboratorio SET costo = '$costo', ip_modifica='$ip', fecharegistro='$ahora' where nroregistro='$idregistroc'";
	$rs = $db_con->prepare($sql);
	$res=$rs->execute();
	if (!$res) die ("<p />El usuario $usuario no tiene permisos para añadir costos .<br><br>".$sql);
	// echo $sql;
	$btn6=="";
	$btn4="";
	$btn="Buscar";
	$bus=$_POST["txtbus"];
	}
// modificar costo examen laboratorio
if ($_POST["btn6"] == "Modificar")
{
	$sql="SELECT * FROM ".$_SESSION['bdd']."costoslaboratorio WHERE nroregistro = '".$_POST['idregistrocosto']."'";
	$cs = $db_con->prepare($sql);
	$cs->execute();
	// echo $sql;
	$resulcostos=$cs->fetch(PDO::FETCH_ASSOC);
	echo '<td>';
	echo $resulcostos['descripcion'];
	echo '</td>';
	echo '<form name="femc" id="f1mc" action="" method="post">';
	echo '<td><input name="costo" type="costo" id="costo" value="'.$resulcostos['costo'].'" size="10" maxlength="10" /></td>';
	echo '<input type="hidden" name="txtbus" id="txtbus" value="'. $_POST["txtbus"].'">	';
	echo '<input type="hidden" name="tipo" id="tipo" value="'. $tipo.'">	';
	echo '<input type="hidden" name="idregistroc" id="idregistroc" value="'.$resulcostos['nroregistro'].'">';
	echo '<td><input type="submit" name="btn6"  value="Actualizar Costo" onClick="asdf(3)" /></td>';
	echo '</form>';
}
if ($btn4=="Actualizar Fechas") 
{
	$bus=$_POST["txtbus"];
//	die($fechahasta.' '.$fechadesde);
	$fechadesde=convertir_fecha($fechadesde);
	$fechahasta=convertir_fecha($fechahasta);
	$ahora=ahora($db_con);
	$sql="UPDATE ".$_SESSION['bdd']."costos SET fechadesde='$fechadesde', fechahasta='$fechahasta',  ipmodifica='$ip', fecharegistro='$ahora'  where idregistro='$idregistro'";
	try
	{
		$rs = $db_con->prepare($sql);
		$res = $rs->execute();
	}
	catch(PDOException $e){
		echo $e->getMessage();
		 // echo 'Fallo la conexion';
	}

	if (!$res) die ("<p />El usuario $usuario no tiene permisos para añadir costos .<br><br>".$sql);
	$btn4=="";
	$btn="Buscar";
}
//
// modificar fecha laboratorio
if ($_POST["btn5"] == "Modificar")
{
		$sql="SELECT * FROM ".$_SESSION['bdd']."costos WHERE idregistro = '".$_POST['idregistro']."'";
		$cs = $db_con->prepare($sql);
		$cs->execute();
//		echo $sql;
		$fila=$cs->fetch(PDO::FETCH_ASSOC);
			echo '<form name="fe" id="f1" action="" method="post">';
			echo "<table class='table' width='100%'";
			echo '<tr>';
			echo '<input type="hidden" name="txtbus" id="txtbus" value="'. $_POST["txtbus"].'">	';
			echo '<input type="hidden" name="tipo" id="tipo" value="'. $tipo.'">	';
			echo '<input type="hidden" name="idregistro" id="idregistro" value="'.$idregistro.'">';
			echo '<td>Desde</td>';
			echo '<input type="hidden" name="fechadesde" id="fechadesde" value="'.convertir_fechadmy($fila['fechadesde']).'"/>';
	echo '<td align="left">';
	echo '<span style="background-color: #ff8; cursor: default; "onmouseover="this.style.backgroundColor=\'#ff0\';" onmouseout="this.style.backgroundColor=\'#ff8\';" id="show_d2">'.convertir_fechadmy($fila['fechadesde']).'</span> </td>';
	echo '<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechadesde",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_d2",       // ID of the span where the date is to be shown
        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 

// desactivacion de 18 años pa tras

		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000) ||
							  (date.getTime() > today.getTime()+((365*18)*24*60*60*1000))							  
							  ) ? true : false;  }
    });
</script>
';


			echo '<td>Hasta</td>';
			echo '<input type="hidden" name="fechahasta" id="fechahasta" value="'.convertir_fechadmy($fila['fechahasta']).'"/>';
	echo '<td align="left">';
	echo '<span style="background-color: #ff8; cursor: default; "onmouseover="this.style.backgroundColor=\'#ff0\';" onmouseout="this.style.backgroundColor=\'#ff8\';" id="show_d3">'.convertir_fechadmy($fila['fechahasta']).'</span> </td>';
	echo '<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechahasta",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_d3",       // ID of the span where the date is to be shown
        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 

// desactivacion de 18 años pa tras

		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000) ||
							  (date.getTime() > today.getTime()+((365*18)*24*60*60*1000))							  
							  ) ? true : false;  }
    });
</script>
';


//			echo '<td>Monto</td>';
//			echo '<td><input name="costo" type="costo" id="msas" value="'.$fila['costo'].'" size="10" maxlength="10" /></td>';
			echo '</tr>';
		echo '</table>';
		echo '</table>';
		echo '<tr>';
		echo '<td colspan="6" align="center"><input type="submit" name="btn4" value="Actualizar Fechas" onClick="asdf(3)" /></td>';
}

//
if ($btn4=="Guardar") 
{

	$bus=$_POST["txtbus"];
//	die($fechahasta.' '.$fechadesde);
	$fechadesde=convertir_fecha($fechadesde);
	$fechahasta=convertir_fecha($fechahasta);
	if ($tipo != 'Laboratorio')
	{
		$ahora=ahora($db_con);
		$sql="INSERT INTO  ".$_SESSION['bdd']."costos (codigo, fechadesde, fechahasta, costo, ipregistro, fecharegistro, ipmodifica) VALUES ('$bus', '$fechadesde', '$fechahasta', '$costo', '$ip', '$ahora', '')";
		try
		{
			$rs = $db_con->prepare($sql);
			$res=$rs->execute();
		}
		catch(PDOException $e){
			echo $e->getMessage();
			 // echo 'Fallo la conexion';
		}

		if (!$res) die ("<p />El usuario $usuario no tiene permisos para añadir costos .<br>".$sql);
	}
	else
	{
		$ahora=ahora($db_con);
		$costo=0;
//		$sql="INSERT INTO  ".$_SESSION['bdd']."costos (codigo, fechadesde, fechahasta, costo, ipregistro, fecharegistro, ipmodifica) VALUES ('$bus', '$fechadesde', '$fechahasta', '$costo', '$ip', '$ahora', '')";
		$llamada="call sp_add_costos('".$bus."', '".$fechadesde."', '".$fechahasta."', '".$costo."', '".$ip."', '".$ahora."', '', @resultado)";
		$asig3="SET @resultado=0";
		$sentencia = $db_con->prepare($asig3);
		$sentencia->execute();
		try
		{
			$llamada = $db_con->prepare($llamada);
			$llamada->execute();
/*
			$rs = $db_con->prepare($sql);
			$db_con->beginTransaction(); 
			$res=$rs->execute();
			$db_con->commit();
			$ultimo=$db_con->lastInsertId();
*/
			$comando = "select @resultado as fino";
			$sentencia = $db_con->prepare($comando);
			$sentencia->execute();
			$fila=($sentencia->fetch( PDO::FETCH_ASSOC ));
			$ultimo=$fila['fino'];
		}
		catch(PDOException $e){
			$db_con->rollback();
			echo $e->getMessage();
			 // echo 'Fallo la conexion';
		}

		if ($ultimo == 0) die ("<p />El usuario $usuario no tiene permisos para añadir costos .<br><br>".$sql);
		// obtengo el ultimo id para relacionarlo
		$sql="select * from ".$_SESSION['bdd']."plantillalaboratorio order by id_registro";
		$result = $db_con->prepare($sql);
		$result->execute();
		$sqli="INSERT INTO ".$_SESSION['bdd']."costoslaboratorio (codigo, descripcion, registrocosto, costo, ip_nuevo, fecharegistro, ip_modifica) values ";
//		phpinfo();
/*
		$resultado=$result->fetchall();
		$json_string = json_encode($resultado);
		echo 'json=>'.$json_string;
*/
		while($resul=$result->fetch(PDO::FETCH_ASSOC))
		{
			$variable='costo'.$resul['id_registro'];
			$valor=$$variable;
			$ahora=ahora($db_con);
			$sqli.="('".$bus."', '".$resul['descripcion']."', '$ultimo', '" .$valor."', '$ip', '$ahora',''),";
		}
		$largo=strlen(trim($sqli));
		$sqli=substr($sqli,0,($largo-1));
		 // echo $sqli.'<br>';
		// die($sqli);
		try
		{
			$rs = $db_con->prepare($sqli);
			$res=$rs->execute();
		}
		catch(PDOException $e){
			echo $e->getMessage();
			 // echo 'Fallo la conexion';
		}
		if (!$res) die ("<p />El usuario $usuario no tiene permisos para añadir costos .<br><br>".$sql);
	}
	$btn4=="";
	$btn="Buscar";
}
//
if ($btn4=="Actualizar") 
{

	$bus=$_POST["txtbus"];
	$fechadesde=convertir_fecha($fechadesde);
	$fechahasta=convertir_fecha($fechahasta);
	if ($tipo != 'Laboratorio')
	{
		$ahora=ahora($db_con);
		$sql="UPDATE ".$_SESSION['bdd']."costos SET fechadesde='$fechadesde', fechahasta='$fechahasta', costo='$costo', ipmodifica='$ip', fecharegistro='$ahora'  where idregistro='$idregistro'";
	}
	try
	{
		$rs = $db_con->prepare($sql);
		$res=$rs->execute();
	}
	catch(PDOException $e){
		echo $e->getMessage();
		 // echo 'Fallo la conexion';
	}
	if (!$res) die ("<p />El usuario $usuario no tiene permisos para añadir costos .<br><br>".$sql);
	$btn4=="";
	$btn="Buscar";
}
//
if(isset($_POST["btn1"]) or isset($_POST["btn2"]) or isset($_POST["btn3"])){
	$btn=$_POST["btn1"];
	$bus=$_POST["txtbus"];
	if (($btn=="Buscar") or (($_POST["btn2"] == "Modificar") or ($_POST["btn3"] == "Agregar"))) 
	{
		$sql="SELECT * FROM ".$_SESSION['bdd']."ninstituto WHERE codmed = '$bus'";
		$cs = $db_con->prepare($sql);
		$res=$cs->execute();
		while($resul_tabla=$cs->fetcH(PDO::FETCH_ASSOC))
		{
			$direccion=$resul_tabla['direccion'];
			$horario=$resul_tabla['horario'];
			$nomfiscal=$resul_tabla['nomfiscal'];
			$especialidad=$resul_tabla['codesp'];
			$tipo=$resul_tabla['tipo'];
			$sql2="select * from ".$_SESSION['bdd']."especialidad where codigo = '$especialidad'";
			$cs2 = $db_con->prepare($sql2);
			$cs2->execute();
			$resul_tabla2=$cs2->fetch(PDO::FETCH_ASSOC);
			$especialidad=$resul_tabla2['nombre'];
		}
	}
	if (($_POST["btn2"] == "Modificar") or ($_POST["btn3"] == "Agregar"))
	{
		if ($_POST["btn3"] == "Agregar")
			$sql="SELECT * FROM ".$_SESSION['bdd']."costos WHERE idregistro = 'xx'";
		else 
			$sql="SELECT * FROM ".$_SESSION['bdd']."costos WHERE idregistro = '".$_POST['idregistro']."'";
		$cs = $db_con->prepare($sql);
		$res=$cs->execute();
		$fila=$cs->fetch(PDO::FETCH_ASSOC);
		$tipo=$_POST['tipo'];
//		echo 'tipo '.$tipo.$sql;
		if ($tipo != 'Laboratorio')
		{
			echo '<form name="fe" id="f1" action="" method="post">';
			echo "<table class='table' width='100%'";
			echo '<tr>';
			echo '<input type="hidden" name="txtbus" id="txtbus" value="'. $_POST["txtbus"].'">	';
			echo '<input type="hidden" name="tipo" id="tipo" value="'. $tipo.'">	';
			echo '<input type="hidden" name="idregistro" id="idregistro" value="'.$idregistro.'">';
			echo '<td><label>Desde</label></td>';
			echo '<input type="hidden" name="fechadesde" id="fechadesde" value="'.convertir_fechadmy($fila['fechadesde']).'"/>';
	echo '<td align="left">';
	echo '<span style="background-color: #ff8; cursor: default; "onmouseover="this.style.backgroundColor=\'#ff0\';" onmouseout="this.style.backgroundColor=\'#ff8\';" id="show_d2">'.convertir_fechadmy($fila['fechadesde']).'</span> </td>';
	echo '<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechadesde",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_d2",       // ID of the span where the date is to be shown
        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 

// desactivacion de 18 años pa tras

		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000) ||
							  (date.getTime() > today.getTime()+((365*18)*24*60*60*1000))							  
							  ) ? true : false;  }
    });
</script>
';


			echo '<td><label>Hasta</label></td>';
			echo '<input type="hidden" name="fechahasta" id="fechahasta" value="'.convertir_fechadmy($fila['fechahasta']).'"/>';
	echo '<td align="left">';
	echo '<span style="background-color: #ff8; cursor: default; "onmouseover="this.style.backgroundColor=\'#ff0\';" onmouseout="this.style.backgroundColor=\'#ff8\';" id="show_d3">'.convertir_fechadmy($fila['fechahasta']).'</span> </td>';
	echo '<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechahasta",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_d3",       // ID of the span where the date is to be shown
        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 

// desactivacion de 18 años pa tras

		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000) ||
							  (date.getTime() > today.getTime()+((365*18)*24*60*60*1000))							  
							  ) ? true : false;  }
    });
</script>
';


			echo '<td><label>Monto</label></td>';
			echo '<td><input class="form-control" name="costo" type="text" id="costo" value="'.$fila['costo'].'" size="10" maxlength="10" /></td>';
			echo '</tr>';
			echo '<tr>';
			if ($_POST["btn3"] == "Agregar")
				echo '<td colspan="6" align="center"><input class="btn btn-success" type="submit" name="btn4" value="Guardar" onClick="asdf(3)" /></td>';
			if ($_POST["btn2"] == "Modificar")
				echo '<td colspan="6" align="center"><input class="btn btn-warning" type="submit" name="btn4" value="Actualizar" onClick="asdf(3)" /></td>';
			echo '</tr>';
			echo '</form>';
		}

		else // laboratorio
		{
			echo '<form name="fe" id="f1" action="" method="post">';
			echo "<table class='table' width='100%'";
			echo '<tr>';
			echo '<input type="hidden" name="txtbus" id="txtbus" value="'. $_POST["txtbus"].'">	';
			echo '<input type="hidden" name="tipo" id="tipo" value="'. $tipo.'">	';
			echo '<input type="hidden" name="idregistro" id="idregistro" value="'.$idregistro.'">';
			echo '<td><label>Desde</label></td>';
			echo '<input type="hidden" name="fechadesde" id="fechadesde" value="'.convertir_fechadmy($fila['fechadesde']).'"/>';
	echo '<td align="left">';
	echo '<span style="background-color: #ff8; cursor: default; "onmouseover="this.style.backgroundColor=\'#ff0\';" onmouseout="this.style.backgroundColor=\'#ff8\';" id="show_d2">'.convertir_fechadmy($fila['fechadesde']).'</span> </td>';
	echo '<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechadesde",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_d2",       // ID of the span where the date is to be shown
        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 

// desactivacion de 18 años pa tras

		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000) ||
							  (date.getTime() > today.getTime()+((365*18)*24*60*60*1000))							  
							  ) ? true : false;  }
    });
</script>
';


			echo '<td><label>Hasta</label></td>';
			echo '<input type="hidden" name="fechahasta" id="fechahasta" value="'.convertir_fechadmy($fila['fechahasta']).'"/>';
	echo '<td align="left">';
	echo '<span style="background-color: #ff8; cursor: default; "onmouseover="this.style.backgroundColor=\'#ff0\';" onmouseout="this.style.backgroundColor=\'#ff8\';" id="show_d3">'.convertir_fechadmy($fila['fechahasta']).'</span> </td>';
	echo '<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechahasta",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_d3",       // ID of the span where the date is to be shown
        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 

// desactivacion de 18 años pa tras

		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000) ||
							  (date.getTime() > today.getTime()+((365*18)*24*60*60*1000))							  
							  ) ? true : false;  }
    });
</script>
';


//			echo '<td>Monto</td>';
//			echo '<td><input name="costo" type="costo" id="msas" value="'.$fila['costo'].'" size="10" maxlength="10" /></td>';
			echo '</tr>';
			echo '</table>';
			if ($_POST["btn3"] == "Agregar")
				$sql="select * from ".$_SESSION['bdd']."plantillalaboratorio order by id_registro";
			else
				$sql="select * from ".$_SESSION['bdd']."costoslaboratorio order by idregistro";

			echo "<table class='table' width='100%'";
			$columnas = $primera=0;
			$result = $db_con->prepare($sql);
			$res=$result->execute();
			$registros=0;
			while($resul=$result->fetch(PDO::FETCH_ASSOC))
			{
				if ($primera ==0)
				{
					echo '<tr>';
					$primera = 1;
				}
				$columnas++;
				$registros++;
				echo '<td>';
				echo $resul['descripcion'];
				echo '</td>';
				echo '<td><input name="costo'.$resul['id_registro'].'" type="text" id="costo'.$resul['id_registro'].'" value="'.$resul['costo'].'" size="10" maxlength="10" /></td>';
				if ($columnas == 4)
				{
					echo '</tr>';
					$columnas = $primera = 0;
				}
			}
			echo '</tr>';

			echo '</table>';
			echo '<tr>';
			echo '<input type="hidden" name="registros" id="registros" value="'.$registros.'">';
			if ($_POST["btn3"] == "Agregar")
				echo '<td colspan="6" align="center"><input class="btn btn-success" type="submit" name="btn4" value="Guardar" onClick="asdf(3)" /></td>';
			if ($_POST["btn2"] == "Modificar")
				echo '<td colspan="6" align="center"><input class="btn btn-warning" type="submit" name="btn4" value="Actualizar" onClick="asdf(3)" /></td>';
			echo '</tr>';
			echo '</form>';
		}
	}
 }
// --FIN DE CODIGO PHP PRINCIPAL-->
echo '<form name="fe" id="f1" action="" method="post">';
//echo '<table width="391" border="2" bgcolor="#99CCFF">';
echo "<table class='table' width='100%'";
echo '<tr>';
echo '<td width="379"><div align="center"><strong>Actualizaci&oacute;n de Costos de Estudios</strong></div></td>';
echo '</tr></table>';
echo '<table class="table" width="390" border="2">';
echo '<tr align="center" >';
echo '<td ><label>Especialista / Instituto </label></td>';
echo '<td ><select class="select" name="txtbus" id="txtbus" >';
echo '<option value="">Seleccione</option>';
$sql = "SELECT * from ".$_SESSION['bdd']."ninstituto where status = 1 order by instituto"; //ABRI LA TABLA CLIENTE 
$rs = $db_con->prepare($sql);
$rs->execute();
if($rs->rowCount()>0)
while($row = $rs->fetch(PDO::FETCH_ASSOC))
	echo '<option value="'.$row["codmed"].'"'.(($_POST["txtbus"]==$row["codmed"])?'selected':'').'>'.$row["instituto"].' </option>';
echo '</select></td>';
echo '<td width="61"><input class="btn btn-info" type="submit" name="btn1"  value="Buscar" onClick="asdf(3)" /></td>';
echo '</tr>';
echo '</table>';
// </center>
echo '<hr>';
echo '</form>';
// echo 'bus ='.$bus. ' isse'.isset($_POST["txtbuscar"]);
if (isset($bus) and ($btn=="Buscar"))
{
	echo '<form name="fe2" id="f12" action="" method="post">';
	echo "<table class='table' width='100%'";
	echo '<tr>';
	echo '<th>'.$direccion.'</th>';
	echo '<th>'.$horario.'</th>';
	echo '<th>'.$especialidad.'</th>';
	echo '</tr>';
	echo '<tr>';
	if ($nomfiscal == '')
		echo '<th colspan="3"><h1>DEBE ACTUALIZAR LOS DATOS FISCALES</h1></th>';
	else
		echo '<th>'.$nomfiscal.'</th>';
	echo '</tr>';
	// busco los precios que tengan 
	// si es especialista
	echo '<input type="hidden" name="tipo" id="tipo" value="'. $tipo.'">	';
	if ($tipo != 'Laboratorio')
	{
		$sql="select * from ".$_SESSION['bdd']."costos where codigo = '".$_POST["txtbus"]."' order by fechahasta desc limit 10";
	//	 echo $sql;
		echo '<input type="hidden" name="txtbus" id="txtbus" value="'. $_POST["txtbus"].'">	';
		$result = $db_con->prepare($sql);
		$res=$result->execute();
		if (!$res) die(' '.$sql);
		echo "<table class='table' width='100%'";
		echo '<tr>';
		echo '<th>'.'Inicia en'.'</th>';
		echo '<th>'.'Finaliza en'.'</th>';
		echo '<th>'.'Monto Bs.'.'</th>';
		echo '<th>'. ' '.'</th>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>';
		echo '</td>';
		echo '</tr>';
		if ($result->rowCount() > 0)
			while($resul=$result->fetch(PDO::FETCH_ASSOC))
			{
				echo '<tr>';
				echo '<td>';
				echo convertir_fechadmy($resul['fechadesde']);
				echo '</td>';
				echo '<td>'.convertir_fechadmy($resul['fechahasta']).'</td>';
				echo '<td>'.number_format($resul['costo'],2,'.',',').'</td>';
				echo '<form name="fem" id="f1m" action="" method="post">';
				echo '<input type="hidden" name="txtbus" id="txtbus" value="'. $_POST["txtbus"].'">	';
				echo '<input type="hidden" name="tipo" id="tipo" value="'. $tipo.'">	';
				echo '<input type="hidden" name="idregistro" id="idregistro" value="'.$resul['idregistro'].'">';
				echo '<td><input class="btn btn-warning" type="submit" name="btn2"  value="Modificar" onClick="asdf(3)" /></td>';
				echo '</form>';
				echo '</tr>';
			}
		echo '<tr>';
		echo '<td colspan="4" align="center"><input class="btn btn-success" type="submit" name="btn3"  value="Agregar" onClick="asdf(3)" /></td>';
		echo '</tr>';
	}
	else // laboratorio
	{
		$sql="select * from ".$_SESSION['bdd']."costos where codigo = '".$_POST["txtbus"]."' order by fechahasta desc limit 10";
		echo '<input type="hidden" name="txtbus" id="txtbus" value="'. $_POST["txtbus"].'">	';
		$result = $db_con->prepare($sql);
		$res=$result->execute();
		if (!$res) die(' '.$sql);
		echo "<table class='table' width='100%'";
		echo '<tr>';
		echo '<td>';
		echo '</td>';
		echo '</tr>';
		if ($result->rowCount() > 0)
		{
			echo '<tr>';
			echo '<th>'.'Inicia en'.'</th>';
			echo '<th>'.'Finaliza en'.'</th>';
			echo '</tr>';
			while($resul=$result->fetch(PDO::FETCH_ASSOC))
			{
				echo '<tr>';
				echo '<td>';
				echo convertir_fechadmy($resul['fechadesde']);
				echo '</td>';
				echo '<td>'.convertir_fechadmy($resul['fechahasta']).'</td>';
//				echo '<td>'.number_format($resul['costo'],2,'.',',').'</td>';
//				echo '<form name="fem" id="f1m" action="" method="post">';
				echo '<input type="hidden" name="txtbus" id="txtbus" value="'. $_POST["txtbus"].'">	';
				echo '<input type="hidden" name="tipo" id="tipo" value="'. $tipo.'">	';
				echo '<input type="hidden" name="idregistro" id="idregistro" value="'.$resul['idregistro'].'">';
				echo '<td><input type="submit" name="btn5"  value="Modificar" onClick="asdf(3)" /></td>';
				$idbusqueda=$resul['idregistro'];
//				echo '</form>';
				echo '</tr>';
				// busco los costos de los examenes
				$sqlc="select * from ".$_SESSION['bdd']."costoslaboratorio where (codigo = '".$_POST["txtbus"]."') and (registrocosto = '$idbusqueda') order by nroregistro";
				// echo $sqlc;
				//
				$resultcostos = $db_con->prepare($sqlc);
				$res=$resultcostos->execute();
				if (!$res) die($sql);
				$columnas = $primera = 0;
				if ($resultcostos->rowCount() > 0)
					while($resulcostos=$resultcostos->fetch(PDO::FETCH_ASSOC))
					{
						if ($primera ==0)
						{
							echo '<tr>';
							$primera = 1;
						}
						$columnas++;
						$registros++;
						echo '<td>';
						echo $resulcostos['nroregistro']. ' - '.$resulcostos['descripcion'];
						echo '</td>';
						echo '<td>'.number_format($resulcostos['costo'],2,'.',',').'</td>';
						echo '<form name="femco" id="f1mco" action="" method="post">';
						echo '<input type="hidden" name="txtbus" id="txtbus" value="'. $_POST["txtbus"].'">	';
						echo '<input type="hidden" name="tipo" id="tipo" value="'. $tipo.'">	';
						echo '<input type="hidden" name="idregistrocosto" id="idregistrocosto" value="'.$resulcostos['nroregistro'].'">';
						echo '<td><input type="submit" name="btn6"  value="Modificar" onClick="asdf(3)" /></td>';
						echo '</form>';
						if ($columnas == 5)
						{
							echo '</tr>';
							$columnas = $primera = 0;
						}
					}
//				echo '</form>';
			}
		}
		echo '<tr>';
		echo '<td colspan="4" align="center"><input class="btn btn-success" type="submit" name="btn3"  value="Agregar" onClick="asdf(3)" /></td>';
		echo '</tr>';
	}
}
echo '</table>';
echo '</form>';

?>

</body>

</html>