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
if ($accion == 'Buscar')  {
	extract($_POST);
	$lacedula = trim($_POST['cedula']);
	echo $lacedula. ' - ' .$accion;
	if ($lacedula) { 
		$cedula=$lacedula;
		$sql="SELECT * FROM ".$_SESSION['bdd']."especialidad where codigo = '$lacedula'";
		$result=$db_con->prepare($sql);
		$result->execute();
		$row= $result->fetch(PDO::FETCH_ASSOC);
		echo "<input type = 'hidden' value ='".$row['codigo']."' name='cedula'>"; 
		$cedula=$row['codigo'];
		$accion = 'Editar'; }
		else $accion = '';
}
if ($accion == 'Anadir1') {
	extract($_POST);
	$lafechanac=$_POST['lafechanac'];
	$lafechanacmysql=convertir_fecha($lafechanac);
	$numerocedula=$lacedula;
	$lacedula=ceroizq($lacedula,3);
	$sql = "select * from ".$_SESSION['bdd']."especialidad where codigo = '$lacedula'";
	$result=$db_con->prepare($sql);
	$res=$result->execute();
	if ($result->rowCount() > 0)
		die ('No se puede registrar a '.$row['ape_nom'].' ya existe ese codigo');
		
	$sql="INSERT INTO ".$_SESSION['bdd']."especialidad (
			codigo, nombre
			) 
		VALUES (
			'$lacedula', '$elapellido'
			)";
	echo $sql;

		if (!$res) die ("<p />El usuario $usuario no tiene permisos para añadir especialidad o ya existente.<br><br>".$sql);
		$accion="";
}

if ($accion == 'Editar1') {
	extract($_POST);
	$num = 1;
	$lafechanac=$_POST['lafechanac'];
	$hoy = date("Y-m-d");
	if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
	$sql="UPDATE ".$_SESSION['bdd']."especialidad SET nombre = '$elapellido'
		WHERE codigo = '$lacedula'";
	$result=$db_con->prepare($sql);
	$res=$result->execute();
	if (!$res) die ("<p />El usuario $usuario no tiene permisos para modificar especialidad <br><br>".$sql);
	$accion='';
}

if (!$accion) {
//	echo "<div id='div1'>";
	echo "<form action='regespe.php?accion=Buscar' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
    echo '  C&oacute;digo ';
	echo '<input name="cedula" type="text" id="cedula" value=""  size="3" maxlength="3" />';
	echo "<input class='btn btn-info' type = 'submit' value = 'Buscar'>";
	echo '</form>';

	echo "<table class='table' width='100%'><tr>";
	echo '<th><a href=?ord=ced_prof>C&oacute;digo</a></th><th><a href=?ord=nombre>Nombre</a>';
	echo '[ <a href="regespe.php?accion=Anadir">           Nueva Especialidad</a> ]</th></tr>';
	$ord = $_GET['ord'];
	if (!$ord) $ord='codigo';
	$conta = $_GET['conta'];
	if (!$_GET['conta']) {
		$conta = 1;
	}
	$sql = "SELECT COUNT(codigo) AS cuantos FROM ".$_SESSION['bdd']."especialidad";

	$result=$db_con->prepare($sql);
	$result->execute();
	$row= $result->fetch(PDO::FETCH_ASSOC);
	$numasi = $row[cuantos]; 
	
	
	$sql = "SELECT * FROM ".$_SESSION['bdd']."especialidad ORDER BY $ord "." LIMIT ".($conta-1).", 10";
	$rs=$db_con->prepare($sql);
	$rs->execute();

	if (pagina($numasi, $conta, 10, "Especialidad", $ord)) {$fin = 1;}

/*
// bucle de listado
	$fechactual="select now() as hoy";
	$fechactual=mysql_query($fechactual);
	$fechactual=mysql_fetch_assoc($fechactual);
	$fechactual=$fechactual['hoy'];
	$fechactual=substr($fechactual,0,10);
*/	
	while($row=$rs->fetch(PDO::FETCH_ASSOC)) {
		echo "<tr>";
		echo "<td class='centro'>";
		echo "<a href='regespe.php?accion=Editar&lacedula=".$row['codigo']."'>";
		echo $row['codigo']."</a></td>";
		echo "<td class='centro'>";
		echo trim($row['nombre'])."</a></td>";
		echo "</tr>";
	}
	echo "</table>";

	pagina($numasi, $conta, 10, "Especialidad", $ord);
}

if ($accion == "Anadir") {
	echo '<div id="div1">';
	echo "<form action='regespe.php?accion=Anadir1' name='form1' id='form1' enctype='multipart/form-data' method='post' onsubmit='return valsoc(form1)'>";
	$sql="SELECT * FROM ".$_SESSION['bdd']."especialidad WHERE codigo = 'xx'";
	$result=$db_con->prepare($sql);
	$res=$result->execute();
	if (!$res ) die ('Error 200-1 <br>'.$sql.'<br>');
	pantalla_socio($result,$accion, $db_con);
	echo "<input class='btn btn-success' type = 'submit' value = 'Grabar Datos'>";
	echo '</div>';
// 	echo "</form>\n";

}

if ($accion == "Editar") {
	echo '<div id="div1">';
	$sql="SELECT * FROM ".$_SESSION['bdd']."especialidad WHERE codigo = '".$lacedula."'";
	$result=$db_con->prepare($sql);
	$res= $result->execute();
	if (!$res )  die ('Error 200-1 <br>'.$sql.'<br>');
	$temp = "";
	echo "<form enctype='multipart/form-data' action='regespe.php?accion=Editar1' name='form1' id='form1' method='post' onsubmit='return valsoc(form1)'>";
	pantalla_socio($result,$accion);
	echo "<br><input class='btn btn-warning' type = 'submit' value = 'Confirmar cambios'></form>\n";
	echo '</div>';
}
?>

<?php include("pie.php");?></body></html>


<?php
function pantalla_socio($result,$accion, $db_con)
{
	$fila= $result->fetch(PDO::FETCH_ASSOC);
	if ($accion == 'Editar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
	if ($accion == 'Anadir') {
		$elcodigo=nuevo_codigo(); 
		$ingreso=date("d/m/Y", time());
		}
	else  $elcodigo=$fila['codigo'];
	echo "<input type = 'hidden' value ='".$elcodigo."' name='lacedula'>";
	echo '<fieldset><legend>Informaci&oacute;n General C&oacute;digo '.$elcodigo.'</legend>';
	echo '<table class="table" border="1">';
	echo '<tr>';
	echo '<td >Descripci&oacute;n</td>';
	echo '<td class="rojo"><input name="elapellido" type="text" id="elapellido" size="30" maxlength="30" onChange="conMayusculas(this)" value="'.$fila['nombre'].'" required />*</td>';
	echo '</tr>';
	echo '</table>';
	echo '</fieldset>';
}

function nuevo_codigo()
{
	$sql="select codigo from ".$_SESSION['bdd']."especialidad order by codigo desc limit 1";
	$result=$db_con->prepare($sql);
	$result->execute();
	$fila2= $result->fetch(PDO::FETCH_ASSOC);
	$ultimo=$fila2['codigo'];
	$contador = 0;
	$digitos=3;
	while ($contador < 120) {
//		echo '  -  '.$ultimo;
		$contador++;
		$ultimo++;
		$ultimo=ceroizq($ultimo,$digitos);
		$sql = "select codigo from ".$_SESSION['bdd']."especialidad where codigo = '$ultimo'";
		$result2=$db_con->prepare($sql);
		$result2->execute();
		if ($resulta2->rowCount() < 1) // consegui uno vacio y rompo el ciclo 
			return $ultimo;
	}
}
return $ultimo;

?>
