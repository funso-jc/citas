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
//	echo $lacedula. ' - ' .$accion;
	if ($lacedula) { 
		$cedula=$lacedula;
		$sql="SELECT * FROM ".$_SESSION['bdd']."obreros where cedula = '$lacedula'";
		$result=$db_con->prepare($sql);
		$result->execute();
		$row= $result->fetch(PDO::FETCH_ASSOC);
		echo "<input type = 'hidden' value ='".$row['ced_prof']."' name='cedula'>"; 
		$cedula=$row['ced_prof'];
		$accion = 'Editar'; }
		else $accion = '';
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
	$lacedula=ceroizq($lacedula,8);
	$sql = "select * from ".$_SESSION['bdd']."obreros where cedula = '$lacedula'";
	$result=$db_con->prepare($sql);
	$result->execute();
	if ($result->rowCount() > 0)
		die ('No se puede registrar a '.$row['ape_nom'].' ya existe esa cedula');
	if ($elemail) {
		$sql = "SELECT * FROM ".$_SESSION['bdd']."obreros WHERE mail = '$elemail'";
		$result=$db_con->prepare($sql);
		$result->execute();
		if ($result->rowCount() > 1)
			die ('No se puede asignar esta dirección de email, ya esta registrada ');
			while($row=$result->fetch(PDO::FETCH_ASSOC)) {
			if ($row['ced_prof'] <> $lacedula)
				die ('No se puede asignar esta dirección de email, ya esta registrada a nombre de '.$row['ape_prof'].' '.$row['nombr_prof']);
			}
		}
		$ahora=ahora($db_con);
		$sql="INSERT INTO ".$_SESSION['bdd']."obreros (
			cedula, ape_nom, dir1, dir2, sexo, fnacim, 
			stat_emp, zon_emp, cargo, estado, 
			telhab, celular, celular2, mail, 
			avisara, telavisa, trabajo, 
			ip_nuevo, ultima_act , ingreso, teltra, ip_modifica, lacedula
			) 
		VALUES (
			'$lacedula', '$elapellido', '$ladireccionh1', '$ladireccionh2', '$optsexo', '$lafechanacmysql', 
			'$elestatus', '$lazona', '$elcargo', '$elcivil', 
			'$eltelefonoh',  '$elcelular1', '$elcelular2', '$elemail', 
			'$avisara', '$eltelefonoa', '$eltrabajoa',
			'$ip_nuevo', '$ahora', '1001-01-01', '$eltrabajo', '', 0
			)";
// 		echo $sql;
	try
	{
		$res=$db_con->prepare($sql);
		$res->execute();
	}
	catch(PDOException $e){
		echo $e->getMessage();
		 // echo 'Fallo la conexion';
	}
		if (! $res)
			die ("<p />El usuario $usuario no tiene permisos para añadir Titulares o ya existente.<br><br>".$sql);
		$accion="";
}

if ($accion == 'Editar1') {
	extract($_POST);
	$num = 1;
	$lafechanac=$_POST['lafechanac'];
	$lafechanacmysql=convertir_fecha($lafechanac);
	if ($elemail) {
		$sql = "SELECT * FROM ".$_SESSION['bdd']."obreros WHERE mail = '$elemail'";
		$result=$db_con->prepare($sql);
		$result->execute();
		if ($result->rowCount() > 1)
			die ('No se puede asignar esta dirección de email, ya esta registrada ');
		while($row=$result->fetch(PDO::FETCH_ASSOC)) {
			if ($row['cedula'] <> $lacedula)
				die ('No se puede asignar esta dirección de email, ya esta registrada a nombre de '.$row['ape_nom']);
		}
	}
	$hoy = date("Y-m-d");
	if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
	$ahora=ahora($db_con);
	$sql="UPDATE ".$_SESSION['bdd']."obreros SET ape_nom = '$elapellido', dir1 = '$ladireccionh1',  
		dir2 = '$ladireccionh2', sexo = '$optsexo', fnacim = '$lafechanacmysql', stat_emp = '$elestatus', 
		zon_emp = '$lazona', cargo = '$elcargo', estado = '$elcivil', 
		telhab = '$eltelefonoh', celular = '$elcelular1', celular2 = '$elcelular2', mail = '$elemail', 
		avisara = '$avisara', telavisa = '$eltelefonoa', trabajo = '$eltrabajoa', 
		ultima_act = '$ahora', ip_modifica = '$ip'
		WHERE cedula = '$lacedula'";
	try
	{
	$result=$db_con->prepare($sql);
	$res=$result->execute();
	}
	catch(PDOException $e){
		echo $e->getMessage();
		 // echo 'Fallo la conexion';
	}
	if (!$res)
		die ("<p />El usuario $usuario no tiene permisos para modificar Titulares <br><br>".$sql);
	$accion='';
}

if (!$accion) {
//	echo "<div id='div1'>";
	echo "<form action='regtitular.php?accion=Buscar' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
    echo '  C&eacute;dula ';
	echo '<input name="cedula" type="text" id="cedula" value=""  size="10" maxlength="10" />';
	echo "<input class='btn btn-info' type = 'submit' value = 'Buscar'>";
	echo '</form>';

	echo "<table class='basica 100 hover' width='100%'><tr>";
	echo '<th><a href=?ord=ced_prof>C&eacute;dula</a></th><th><a href=?ord=ape_prof>Nombre</a>';
	echo '[ <a href="regtitular.php?accion=Anadir">           Nuevo Socio</a> ]</th><th>Nacio</th></tr>';
	$ord = $_GET['ord'];
	if (!$ord) $ord='cedula';
//	echo 'orden '.$orden;
//	echo 'ord '.$ord;
//	$ord= $orden;
	$conta = $_GET['conta'];
	if (!$_GET['conta']) {
		$conta = 1;
	}
$sql = "SELECT COUNT(cedula) AS cuantos FROM ".$_SESSION['bdd']."obreros";
echo $sql;
// echo $sql;
	$rs=$db_con->prepare($sql);
	$rs->execute();
	$row= $rs->fetch(PDO::FETCH_ASSOC);
	$numasi = $row[cuantos]; 
//echo $sql;	
	
	$sql = "SELECT cedula, ape_nom, fnacim FROM ".$_SESSION['bdd']."obreros ORDER BY $ord "." LIMIT ".($conta-1).", 20";
	echo $sql;
	$rs=$db_con->prepare($sql);
	$rs->execute();

	if (pagina($numasi, $conta, 20, "Titulares", $ord)) {$fin = 1;}

// bucle de listado
	$fechactual="select now() as hoy";
	$fechactual=$db_con->prepare($fechactual);
	$fechactual->execute();
	$fechactual=$fechactual->fetch(PDO::FETCH_ASSOC);
	$fechactual=$fechactual['hoy'];
	$fechactual=substr($fechactual,0,10);
	
	while($row=$rs->fetch(PDO::FETCH_ASSOC)) {
		echo "<tr>";
		echo "<td class='centro'>";
		echo "<a href='regtitular.php?accion=Editar&lacedula=".$row['cedula']."'>";
		echo $row['cedula']."</a></td>";
		echo "<td class='centro'>";
		echo trim($row['ape_nom'])."</a></td>";
		echo "<td class='centro'>";
		echo convertir_fechadmy($row['fnacim'])."</a></td>";
		echo "</td>";
		echo "</tr>";
	}

	echo "</table>";

	pagina($numasi, $conta, 20, "Titulares", $ord);
//	echo "</div>";
}

if ($accion == "Anadir") {
	echo '<div id="div1">';
	echo "<form action='regtitular.php?accion=Anadir1' name='form1' id='form1' enctype='multipart/form-data' method='post' onsubmit='return valsoc(form1)'>";
	$sql="SELECT * FROM ".$_SESSION['bdd']."obreros WHERE cedula= 'xx'";
	$result=$db_con->prepare($sql);
	$result->execute();
	pantalla_socio($result,$accion, $db_con);
	echo "<input class='btn btn-success' type = 'submit' value = 'Grabar Datos'>";
	echo '</div>';
// 	echo "</form>\n";

}

if ($accion == "Editar") {
	echo '<div id="div1">';
	$sql="SELECT * FROM ".$_SESSION['bdd']."obreros WHERE cedula= '".$lacedula."'";
	$result=$db_con->prepare($sql);
	$result->execute();
	$temp = "";
	echo "<form enctype='multipart/form-data' action='regtitular.php?accion=Editar1' name='form1' id='form1' method='post' onsubmit='return valsoc(form1)'>";
	pantalla_socio($result,$accion, $db_con);
	echo "<br><input class='btn btn-success' type = 'submit' value = 'Confirmar cambios'></form>\n";
	echo "<a class='btn btn-default' href='regbenef.php?cedula=".$lacedula."'>Actualizar Beneficiarios";
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
	echo '<table width="639" border="1">';
	echo '<tr>';
	echo '<td width="138">C&eacute;dula</td>';
	echo '<td width="173" class="rojo"> ';
//	if ($accion != 'Editar') 
	echo '<input name="lacedula" type="text" id="lacedula" value="'.$fila['cedula'].'" '.$lectura.' size="8" maxlength="8" required/>*</td>';
	echo '<td width="127">Apellido(s)/Nombre(s)</td>';
	echo '<td width="173" class="rojo"><input name="elapellido" type="text" id="elapellido" size="30" maxlength="30" onChange="conMayusculas(this)" value="'.$fila['ape_nom'].'" required />*</td>';
	echo '<tr>';
	echo '<td>Direcci&oacute;n</td>';
	echo '<td class="rojo" colspan="5">';
	echo '<input name="ladireccionh1" type="text" id="ladireccionh1" maxlength="40" onChange="conMayusculas(this)" value="'.$fila['dir1'].'" size="40" required />';
//	echo '</td><td>';
	echo '<input name="ladireccionh2" type="text" id="ladireccionh2" size="40" maxlength="40" onChange="conMayusculas(this)" value="'.$fila['dir2'].'" />*</td></tr>';
	echo '<tr>';
	echo '<td width="127">Tel&eacute;fono</td>';
	echo '<td width="173">';
	echo '<input name="eltelefonoh" type="text" id="eltelefonoh" size="11" maxlength="11" value="'.$fila['telhab'].'" /></td>';
	echo '<td width="138">Celular(es)</td>';
	echo '<td width="173" colspan="3">';
	echo '<input name="elcelular1" type="text" id="elcelular1" size="11" maxlength="11" onSubmit ="valida_telefono(this)" value="'.$fila['celular'].'" /> - ';
	echo '<input name="elcelular2" type="text" id="elcelular2" size="11" maxlength="11" value="'.$fila['celular2'].'"/></td></tr>';
	echo '<tr>';
	echo '<td>E-mail</td>';
	echo '<td colspan="5"><input name="elemail" type="text" id="elemail" size="80" maxlength="80" onChange="isEmailAddress(this)" value="'.$fila['mail'].'" /></td></tr>';
	echo '<tr>';
	echo '<td>Fecha de Nacimiento</td>';
	echo '<input type="hidden" name="lafechanac" id="lafechanac" value="'.convertir_fechadmy($fila['fnacim']).'"/>';
	echo '<td align="left">';
	echo '<span style="background-color: #ff8; cursor: default; "onmouseover="this.style.backgroundColor=\'#ff0\';" onmouseout="this.style.backgroundColor=\'#ff8\';" id="show_d2">'.convertir_fechadmy($fila['fnacim']).'</span> </td>';
	echo '<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "lafechanac",     // id of the input field
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
							  (date.getTime() > today.getTime()-((365*18)*24*60*60*1000))
							  ) ? true : false;  }
    });
</script>
';
	echo '<td>Sexo</td>';
	$elsexo=$fila['sexo_prof'];
	echo '<td><input type="radio" name="optsexo" value="1" ';
	if ($elsexo < 2) echo " checked";
	echo '/>Masculino';
	echo '<input type="radio" name="optsexo" value="2"';
	if ($elsexo == 2) echo " checked";
	echo '/>Femenino</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td colspan="1">Estado Civil </td><td>';
	$elcivil=$fila['estado'];
	$sql="select cvalor from ".$_SESSION['bdd']."configura where cparametro='Civil' order by cvalor	";
	$resultado=$db_con->prepare($sql);
	$resultado->execute();
	echo '<select name="elcivil" size="1">';
	while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
		echo '<option value="'.$fila2['cvalor'].'" '.(($elcivil==$fila2['cvalor'])?'selected':'').'>'.$fila2['cvalor'].'</option>';}
 	echo '</select> '; 

	echo '<td colspan="1">Estatus</td><td>';
	$elstatus=$fila['stat_emp'];
	echo '<select name="elestatus" size="1">';
	$sql="select cvalor from ".$_SESSION['bdd']."configura where cparametro='Estatus' order by cvalor	";
	$resultado=$db_con->prepare($sql);
	$resultado->execute();
	while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
		echo '<option value="'.$fila2['cvalor'].'" '.(($elstatus==$fila2['cvalor'])?'selected':'').'>'.$fila2['cvalor'].'</option>';}
 	echo '</select> '; 

	echo '</tr>';
	echo '<tr>';
	echo '<td colspan="1">Cargo</td><td>';
	$elcargo=$fila['cargo'];
	echo '<select name="elcargo" size="1">';
	$sql="select cvalor from ".$_SESSION['bdd']."configura where cparametro='Cargo' group by cvalor order by cvalor ";
	$resultado=$db_con->prepare($sql);
	$resultado->execute();
	while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
		echo '<option value="'.$fila2['cvalor'].'" '.(($elcargo==$fila2['cvalor'])?'selected':'').'>'.$fila2['cvalor'].'</option>';}
 	echo '</select> '; 
	
	echo '<td colspan="1">Zona</td><td>';
	$lazona=$fila['zon_emp'];
	echo '<select name="lazona" size="1">';
	$sql="select cvalor from ".$_SESSION['bdd']."configura where cparametro='Zona' group by cvalor order by cvalor ";
	$resultado=$db_con->prepare($sql);
	$resultado->execute();
	while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
		echo '<option value="'.$fila2['cvalor'].'" '.(($lazona==$fila2['cvalor'])?'selected':'').'>'.$fila2['cvalor'].'</option>';}
 	echo '</select> '; 

	echo '</tr>';


	echo '</table>';
	
	echo '</fieldset>';
	echo '<fieldset><legend>Informaci&oacute;n de Emergencia</legend>';
	echo '<tr></tr>';
	echo '<table width="639" border="1">';
	echo '<tr>';
	echo '<td width="58" >Avisar a</td>';
	echo '<td colspan="5"><input name="avisara" type="text" id="avisara" size="50" maxlength="50" onChange="conMayusculas(this)" value="'.$fila['avisara'].'"/></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Tel&eacute;fono<br></td>';
	echo '<td>';
	echo '<input name="eltelefonoa" type="text" id="eltelefonoa" size="11" maxlength="11" value="'.$fila['telavisa'].'" /></td>';
	echo '<td width="26">Trabajo</td>';
	echo '<td width="77">';
	echo '<input name="eltrabajoa" type="text" id="eltrabajoa" size="11" maxlength="11" value="'.$fila['trabajo'].'"/></td>';
	echo '</td>';
	echo '</tr>';
	echo '</table>';
	echo '</fieldset>';
}

?>
