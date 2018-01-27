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
if ($accion == 'Buscar')  
{
	extract($_POST);
	$lacedula = trim($_POST['cedula']);
	$elinstituto = trim($_POST['instituto']);
	$bus0 = $bus1 = 0;
//	echo $lacedula. ' - '.$elinstituto.' = ' .$accion;
	if ($lacedula) { 
		$cedula=$lacedula;
		$sql="SELECT * FROM ".$_SESSION['bdd']."ninstituto where codmed LIKE '%$lacedula%'";
		$result=$db_con->prepare($sql);
		$result->execute();
		$row= $result->fetch(PDO::FETCH_ASSOC);
		echo "<input type = 'hidden' value ='".$row['codmed']."' name='cedula'>"; 
		$lacedula=$row['codmed'];
		$accion = 'Editar'; 
		if ($result->rowCount() > 0)
			$bus0 = 1;
	}
	else if ($elinstituto) { 
		$sql="SELECT * FROM ".$_SESSION['bdd']."ninstituto where instituto LIKE '%$elinstituto%'";
		$result=$db_con->prepare($sql);
		$result->execute();
		$row= $result->fetch(PDO::FETCH_ASSOC);
		echo "<input type = 'hidden' value ='".$row['codmed']."' name='cedula'>"; 
		$lacedula=$row['codmed'];
		$accion = 'Editar'; 
		if ($result->rowCount() > 0)
			$bus1 = 1;
	}
	else $accion = '';
	if (($bus0 == 0) and ($bus1 == 0))
	{
		echo '<h1>No se encontraron datos con la informaci&oacute;n suministrada</h1>';
		$accion = '';
	}
}

if ($accion == 'Anadir1') {
	extract($_POST);
	$lafechanac=$_POST['lafechanac'];
	$lafechanacmysql=convertir_fecha($lafechanac);
	$numerocedula=$lacedula;
	$eltelefonoh=$eltelefonoh ;
	$elcelular1=$elcelular1;
	$elcelular2=$elcelular2;
	$eltelefonot=$eltelefonot;
//	$lacedula=ceroizq($lacedula,8);
	$sql = "select * from ".$_SESSION['bdd']."ninstituto where codmed = '$lacedula'";
	$result=$db_con->prepare($sql);
	$result->execute();
	if ($result->rowCount() > 0)
		die ('No se puede registrar a '.$row['instituto'].' ya existe esa cedula');
	if ($elemail) {
		$sql = "SELECT * FROM ".$_SESSION['bdd']."ninstituto WHERE email = '$elemail'";
		$result=$db_con->prepare($sql);
		$result->execute();
		if ($result->rowCount() > 1)
			die ('No se puede asignar esta dirección de email, ya esta registrada ');
			while($row=$result->fetch(PDO::FETCH_ASSOC)) {
			if ($row['codmed'] <> $lacedula)
				die ('No se puede asignar esta dirección de email, ya esta registrada a nombre de '.$row['instituto'].' '.$row['nombr_prof']);
			}
		}
		
		$ahora=ahora($db_con);
		$sql="INSERT INTO ".$_SESSION['bdd']."ninstituto (
			codmed, instituto, msas, colegio, direccion, direccion2, codesp, 
			horario, horario2, status, 
			email, cuentabanco, telefonos, celular, nomfiscal, dirfiscal1,
			dirfiscal2, celularpago, tipo, 
			ip_nuevo, realizado
			) 
		VALUES (
			'$lacedula', '$elapellido', '$msas', '$colegio', '$ladireccionh1', '$ladireccionh2', '$laespecialidad', 
			'$horario', '$horario2', '".isset($activo)."',  
			'$elemail', '$cuentabanco', '$eltelefonoh', '$elcelular1', '$nomfiscal', '$dirfiscal1',
			'$dirfiscal2', '$celularpago', '$caracteristica', 
			'$ip_nuevo', '$ahora'
			)";
// 		echo $sql;

		$result=$db_con->prepare($sql);
		$result->execute();
		if (!$result)
		 die ("<p />El usuario $usuario no tiene permisos para añadir Especialistas o ya existente.<br><br>".$sql);
		$accion="";
}


if ($accion == 'Editar1') {
	extract($_POST);
	$num = 1;
	$lafechanac=$_POST['lafechanac'];
	$lafechanacmysql=convertir_fecha($lafechanac);
	if ($elemail) {
		$sql = "SELECT * FROM ".$_SESSION['bdd']."ninstituto WHERE email = '$elemail'";
		try
		{
			$result=$db_con->prepare($sql);
			$result->execute();
			if ($result->rowCount() > 1)
				die ('No se puede asignar esta direcci&oacute;n de email, ya esta registrada ');
			while($row=$result->fetch(PDO::FETCH_ASSOC)) 
			{
				if ($row['codmed'] <> $lacedula)
					die ('No se puede asignar esta direcci&oacute;n de email, ya esta registrada a nombre de '.$row['instituto']);
			}
		}
		catch(PDOException $e){
			echo $e->getMessage();
			 // echo 'Fallo la conexion';
		}
	}
	$hoy = date("Y-m-d");
	if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
	$ahora=ahora($db_con);
	$sql="UPDATE ".$_SESSION['bdd']."ninstituto SET 
			instituto = '$elapellido', msas = '$msas', colegio = '$colegio', direccion = '$ladireccionh1', 
			direccion2 = '$ladireccionh2', codesp ='$laespecialidad', 
			horario = '$horario', horario2 = '$horario2', status = '".isset($activo)."',  
			email = '$elemail', cuentabanco = '$cuentabanco', telefonos = '$eltelefonoh', 
			celular =  '$elcelular1', nomfiscal = '$nomfiscal', dirfiscal1 = '$dirfiscal1',
			dirfiscal2 = '$dirfiscal2', celularpago = '$celularpago', 	tipo = '$caracteristica', 
			realizado = '$ahora', ip_modifica = '$ip'
		WHERE codmed = '$lacedula'";
		// echo $sql;
	try
	{
		$result=$db_con->prepare($sql);
		$result->execute();
	}
	catch(PDOException $e){
		echo $e->getMessage();
		 // echo 'Fallo la conexion';
	}
	if (!$result) die ("<p />El usuario $usuario no tiene permisos para modificar Especialistas <br><br>".$sql);
	$accion='';
}

?>
<?php 
if (!$accion) {
//	echo "<div id='div1'>";
	echo "<form action='regexternos.php?accion=Buscar' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
    echo 'C&eacute;dula/RIF ';
	echo '<input name="cedula" type="text" id="cedula" value=""  size="10" maxlength="10" />';
    echo 'Instituto ';
	echo '<input name="instituto" type="text" id="instituto" value=""  size="10" maxlength="10" />';
	echo "<input type = 'submit' value = 'Buscar'>";
	echo '</form>';

	echo "<table class='basica 100 hover' width='100%'><tr>";
	echo '<th><a href=?ord=ced_prof>C&oacute;digo</a></th><th><a href=?ord=ape_prof>Nombre/Instituci&oacute;n</a>';
	echo '[ <a href="regexternos.php?accion=Anadir">           Nuevo Especialista</a> ]</th><th>Direcci&oacute;n</th><th>Horario</th><th>Tel&eacute;fono</th></tr>';
	$ord = $_GET['ord'];
	if (!$ord) $ord='instituto';
//	echo 'orden '.$orden;
//	echo 'ord '.$ord;
//	$ord= $orden;
	$conta = $_GET['conta'];
	if (!$_GET['conta']) {
		$conta = 1;
	}
$sql = "SELECT COUNT(codmed) AS cuantos FROM ".$_SESSION['bdd']."ninstituto";

// echo $sql;
	$result=$db_con->prepare($sql);
	$result->execute();
	$row= $result->fetch(PDO::FETCH_ASSOC);
	$numasi = $row[cuantos]; 
	
	
	$sql = "SELECT * FROM ".$_SESSION['bdd']."ninstituto ORDER BY $ord "." LIMIT ".($conta-1).", 20";
	$rs=$db_con->prepare($sql);
	$rs->execute();
//	echo $sql;

	if (pagina($numasi, $conta, 20, "Especialistas", $ord)) {$fin = 1;}

// bucle de listado
/*
	$fechactual="select now() as hoy";
	$fechactual=mysql_query($fechactual);
	$fechactual=mysql_fetch_assoc($fechactual);
	$fechactual=$fechactual['hoy'];
	$fechactual=substr($fechactual,0,10);
*/	
	while($row=$rs->fetch(PDO::FETCH_ASSOC)) {
		echo "<tr>";
		echo "<td class='centro'>";
		echo "<a href='regexternos.php?accion=Editar&lacedula=".$row['codmed']."'>";
		echo $row['codmed']."</a></td>";
		echo "<td class='centro'>";
		echo trim($row['instituto'])."</a></td>";
		echo "<td class='centro'>";
		echo $row['direccion']."</a></td>";
		echo "</td>";
		echo "<td class='centro'>";
		echo $row['horario']."</a></td>";
		echo "</td>";
		echo "<td class='centro'>";
		echo $row['telefono']."</a></td>";
		echo "</td>";

		echo "<td class='centro'>";
		echo "<img src='imagenes/";
		if ($row['status'] == 1)
			echo "24-em-check.png'";
		else 
			echo "24-em-cross.png'";
		echo "width='16' height='16' border='0' title=";
		if ($row['status'] == 1)
			echo "'Activo' alt='Activo' />";		
		else 
		echo "'Inactivo' alt='Inactivo' />";
		echo "</tr>";
	}

	echo "</table>";

	pagina($numasi, $conta, 20, "Especialistas", $ord);
//	echo "</div>";
}
?>

<?php
if ($accion == "Anadir") {
	echo '<div id="div1">';
	echo "<form action='regexternos.php?accion=Anadir1' name='form1' id='form1' enctype='multipart/form-data' method='post' onsubmit='return valsoc(form1)'>";
	$sql="SELECT * FROM ".$_SESSION['bdd']."ninstituto WHERE codmed = 'xx'";
	$result=$db_con->prepare($sql);
	$result->execute();
	if (! $result) die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
	pantalla_socio($result,$accion, $db_con);
	echo '</div>';
	echo "<input class='btn btn-success' type = 'submit' value = 'Grabar Datos'>";
// 	echo "</form>\n";

}
if ($accion == "Editar") {
	echo '<div id="div1">';
	$sql="SELECT * FROM ".$_SESSION['bdd']."ninstituto WHERE codmed = '".$lacedula."'";
	$result=$db_con->prepare($sql);
	$result->execute();
	if (! $result) die ('Error 200-1 <br>'.$sql.'<br>');
	$temp = "";
	echo "<form enctype='multipart/form-data' action='regexternos.php?accion=Editar1' name='form1' id='form1' method='post' onsubmit='return valsoc(form1)'>";
	pantalla_socio($result,$accion, $db_con);
	echo "<br><input class='btn btn-warning' type = 'submit' value = 'Confirmar cambios'></form>\n";
	echo '</div>';
}
?>

<?php include("pie.php");?></body></html>


<?php
function pantalla_socio($result,$accion, $db_con)
{
	$deci=$_SESSION['deci'];
	$sep_decimal=$_SESSION['sep_decimal'];
	$sep_miles=$_SESSION['sep_miles'];
	$fila = $result->fetch(PDO::FETCH_ASSOC);
	echo "<input type = 'hidden' value ='".$fila['cedula']."' name='cedula'>";
	if ($accion == 'Editar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
	echo '<fieldset><legend>Informaci&oacute;n Personal </legend>';
	echo '<table class="table">';
	echo '<tr>';
	echo '<td colspan="2">C&eacute;dula / RIF</td>';
	echo '<td > ';
	echo '<input name="lacedula" type="text" id="lacedula" value="'.$fila['codmed'].'" '.$lectura.' size="10" maxlength="10" required/>*</td>';
	echo '<td>';
    echo 'M.S.A.S.';
	echo '</td>';
	echo '<td>';
	echo '<input name="msas" type="text" id="msas" value="'.$fila['msas'].'" '.$lectura.' size="10" maxlength="10" /></td>';
	echo '<td>';
    echo 'Colegio  ';
	echo '</td>';
	echo '<td>';
	echo '<input name="colegio" type="text" id="colegio" value="'.$fila['colegio'].'" '.$lectura.' size="10" maxlength="10" /></td></tr>';

	echo '<td colspan="2">Nombre Instituto-Especialista</td>';
	echo '<td colspan="3" class="rojo"><input name="elapellido" type="text" id="elapellido" size="80" maxlength="80" onChange="conMayusculas(this)" value="'.$fila['instituto'].'" required />*</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td  colspan="2">Direcci&oacute;n</td>';
	echo '<td class="rojo" colspan="5">';
	echo '<input name="ladireccionh1" type="text" id="ladireccionh1" maxlength="60" onChange="conMayusculas(this)" value="'.$fila['direccion'].'" size="60" required />';
//	echo '</td><td>';
	echo '<input name="ladireccionh2" type="text" id="ladireccionh2" size="60" maxlength="60" onChange="conMayusculas(this)" value="'.$fila['direccion2'].'" />*</td></tr>';
	echo '<tr>';
	echo '<td  colspan="2">Tel&eacute;fono</td>';
	echo '<td width="173">';
	echo '<input name="eltelefonoh" type="text" id="eltelefonoh" size="80" maxlength="80" value="'.$fila['telefonos'].'" /></td>';
	echo '<td width="138">Celular(es)</td>';
	echo '<td width="173" colspan="3">';
	echo '<input name="elcelular1" type="text" id="elcelular1" size="11" maxlength="11" onSubmit ="valida_telefono(this)" value="'.$fila['celular'].'" />';
	echo '</td></tr>';
	echo '<tr>';
	echo '<td  colspan="2">E-mail</td>';
	echo '<td colspan="5"><input name="elemail" type="text" id="elemail" size="80" maxlength="80" onChange="isEmailAddress(this)" value="'.$fila['email'].'" /></td></tr>';
	echo '<tr>';



	echo '</tr>';
	echo '</table>';
	echo '</fieldset>';
	
	echo '<fieldset><legend>Informaci&oacute;n Laboral </legend>';
	echo '<tr></tr>';
	echo '<table class="table">';
	echo '<tr>';
	echo '<td>Horario </td>';
	echo '<td class="rojo">';
	echo '<input name="horario" type="text" id="horario" maxlength="60" onChange="conMayusculas(this)" value="'.$fila['horario'].'" size="60" required />';
//	echo '</td><td>';
	echo '<input name="horario2" type="text" id="horario2" size="60" maxlength="60" onChange="conMayusculas(this)" value="'.$fila['horario2'].'" />*</td></tr>';
	$sql2='select * from '.$_SESSION['bdd'].'especialidad where codigo = "'.$fila['codesp'].'"';
	$result=$db_con->prepare($sql2);
	$result->execute();
	$fila2= $result->fetch(PDO::FETCH_ASSOC);
	$especialidad=$fila['codesp'];
	echo '<td>Especialidad </td>';
	echo '<td>';
	echo '<select name="laespecialidad" size="1">';
	$sql="select codigo, nombre from ".$_SESSION['bdd']."especialidad order by nombre ";
	$result=$db_con->prepare($sql);
	$result->execute();
	while ($fila2 = $result->fetch(PDO::FETCH_ASSOC)) {
		echo '<option value="'.$fila2['codigo'].'" '.(($especialidad==$fila2['codigo'])?'selected':'').'>'.$fila2['nombre'].'</option>';}
 	echo '</select> '; 
    echo '<td>Activo </td><td align="left">';
	echo "<input name='activo' id='activo' type='checkbox' ".($fila['status']==1?'checked ':'')." ></td>";
	echo '</td>';
	echo '<td colspan="1">Tipo </td><td>';
	$laaracteristica=$fila['tipo'];
	echo '<select name="caracteristica" size="1">';
	$sql="select cvalor from ".$_SESSION['bdd']."configura where cparametro='Caracteristica' order by cvalor	";
	$result=$db_con->prepare($sql);
	$result->execute();
	while ($fila2 = $result->fetch(PDO::FETCH_ASSOC)) {
		echo '<option value="'.$fila2['cvalor'].'" '.(($laaracteristica==$fila2['cvalor'])?'selected':'').'>'.$fila2['cvalor'].'</option>';}
 	echo '</select> '; 
	echo '</td>';
	echo '</tr>';

	echo '</table>';
	echo '</fieldset>';

	echo '<fieldset><legend>Informaci&oacute;n Financiera </legend>';
	echo '<table class="table">';
	echo '<tr>';
		echo '<td  >Nro de Cuenta </td>';
		echo '<td ><input name="cuentabanco" type="text" id="cuentabanco" size="20" maxlength="20" onChange="conMayusculas(this)" value="'.$fila['cuentabanco'].'" required /></td>';
		echo '<td >Celular Aviso de Pago</td>';
		echo '<td >';
		echo '<input name="celularpago" type="text" id="celularpago" size="11" maxlength="11" value="'.$fila['celularpago'].'"/></td>';
	echo '</tr>';
	echo '</table>';
	echo '</fieldset>';
	echo '<br>';

	echo '<fieldset><legend>Informaci&oacute;n Fiscal </legend>';
	echo '<tr></tr>';
	echo '<table class="table">';
	echo '<tr>';
	echo '<td >Nombre </td>';
	echo '<td ><input name="nomfiscal" type="text" id="nomfiscal" size="80" maxlength="80" onChange="conMayusculas(this)" value="'.$fila['nomfiscal'].'" required /></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Direcci&oacute;n</td>';
	echo '<td class="rojo">';
	echo '<input name="dirfiscal1" type="text" id="dirfiscal1" maxlength="80" onChange="conMayusculas(this)" value="'.$fila['dirfiscal1'].'" size="60" required />';
//	echo '</td><td>';
	echo '<input name="dirfiscal2" type="text" id="dirfiscal2" size="80" maxlength="80" onChange="conMayusculas(this)" value="'.$fila['dirfiscal2'].'" />*</td>';
	echo '</tr>';
	echo '</table>';
	echo '</fieldset>';
}

?>
