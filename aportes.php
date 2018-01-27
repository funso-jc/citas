<?php
/*
update temp_ahorros set cod_prof = (select cod_prof from sgcaf200 where temp_ahorros.cedula = sgcaf200.ced_prof)
*/
include("head.php");
include("paginar.php");
?>
<script language="javascript">

function abrirVentana(elorden, comprobante)
{
window.open("aportespdf.php?orden="+elorden,"parte1","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");	
}
</script>
<?php
extract($_GET);
extract($_POST);
extract($_SESSION);


if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
?>
<body <?php if (!$bloqueo) {echo $onload;}?>>
<script src="ajaxahorro.js" type="text/javascript"></script>


<?php
set_time_limit(300);	

$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$cedula = $_GET['cedula'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
// echo 'aretenciones' .$aretenciones[0];
if (!$accion) {
//	echo "<div id='div1'>";
	$sqldis="select * from ".$_SESSION['bdd']."_sgcaf210 order by f_ult_apo desc limit 1";
	$arcdis=mysql_query($sqldis);
	$regdis=mysql_fetch_assoc($arcdis);
	if (mysql_num_rows($arcdis) < 1)
		{
			$fechasiguiente='2015-01-01';
			$montosiguiente=0.00;
		}
	else 
	{
		$fechasiguiente=$regdis['f_ult_apo'];
		$montosiguiente=$regdis['monto'];
	}
	$sqldis = "SELECT DATE_ADD('".$fechasiguiente."',INTERVAL 30 DAY) AS siguiente";
	$arcdis=mysql_query($sqldis);
	$regdis=mysql_fetch_assoc($arcdis);
	$fechasiguiente=$regdis['siguiente'];
	
	echo "<form action='aportes.php?accion=sinpago' name='form1' method='post'>";
	echo "<fieldset><legend>Información para Distribución</legend>";
	echo "<table  class='basica 100 hover' width='100%' border='0'>";
	echo '<tr>';
	echo '<td>Fecha</td>';
	echo '<td>';
?>
	<input type="hidden" name="fechasiguiente" id="fechasiguiente" value=" <?php  echo convertir_fechadmy($fechasiguiente); ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_lafecharetiro" 
   ><?php  echo convertir_fechadmy($fechasiguiente); ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechasiguiente",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_lafecharetiro",       // ID of the span where the date is to be shown
        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 

// desactivacion de 18 años pa' tras


		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000))
							  (date.getTime() > today.getTime()-(1*24*60*60*1000)) 
							  // || date.getTime() > today.getTime()+(10*24*60*60*1000))	date.getDay() == 0 || 
							  ) ? true : false;  }
    });
</script>

<?php
	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td>Monto</td>';
    echo '<td align="right"><input name="montosiguiente" align="right" type="text" id="montosiguiente" size="14" maxlength="14" value="'.$montosiguiente.'"/> "</td>';
	echo '</tr>';

	echo '<tr>';
	echo '<td colspan="2" align="center"><input type="submit" name="Submit" value="Enviar" />';
	echo '</td></tr>';
	echo '</form>';
}

if (($accion == "sinpago")) { 
	echo "<form action='aportes.php?accion=imprimir' name='form1' method='post'>";
	$fechasiguiente=convertir_fecha($_POST['fechasiguiente']);
	echo '<input type="hidden" name="montosiguiente" value = "'.$_POST['montosiguiente'].'">';
	echo '<input type="hidden" name="fechasiguiente" value = "'.$fechasiguiente.'">';
	echo 'Creando tabla de distribución<br>';
	$sqldis="delete from ".$_SESSION['bdd']."_sgcadistribuye where procesado = 0";
	$arcdis=mysql_query($sqldis);
	
	$sqldis = "CREATE TEMPORARY TABLE distribuye SELECT cod_prof, ced_prof, concat(ape_prof,' ',nombr_prof) as nombr_prof, cpc_prof, statu_prof, f_ing_capu, 0000000000.00 AS distribucion, '".$fechasiguiente."' AS faport_emp, 0 AS solvente, ultap_prof, 0 AS procesado FROM ".$_SESSION['bdd']."_sgcaf200 WHERE ((upper(statu_Prof)='ACTIVO') AND (f_ing_capu <= '".$fechasiguiente."')) ORDER BY cod_prof";
	$arcdis=mysql_query($sqldis);
/*
	echo $sqldis.'<br>';
		$sqldis = "CREATE TABLE ".$_SESSION['bdd']."_sgcadistribuye SELECT cod_prof, ced_prof, nombr_prof, cpc_prof, statu_prof, f_ing_capu, 0000000000.00 AS distribucion, '".$fechasiguiente."' AS faport_emp, 0 AS solvente, ultap_prof FROM ".$_SESSION['bdd']."_sgcaf200 WHERE ((upper(statu_Prof)='ACTIVO') AND (f_ing_capu <= '".$fechasiguiente."')) ORDER BY cod_prof";
	$arcdis=mysql_query($sqldis);
*/
	echo 'Verificando socios Solventes<br>';
	$sqldis="SELECT * FROM distribuye ";
	$arcdis=mysql_query($sqldis);
	while ($row = mysql_fetch_assoc($arcdis))
	{
		$diatraso=0;
		$solvencia=condicion($row['cod_prof'],$fechasiguiente, false,$diatraso);
		if ($solvencia == true)
		{
			$sqlsol="UPDATE distribuye set solvente = 1 where cod_prof = '".$row['cod_prof']."'";
			$ressol=mysql_query($sqlsol) or die('Fallo solvencia');
//			echo $ressol;
		}
//		mostrar_condicion($solvencia, $diatraso);
	}
	echo 'Excluyendo socios Insolventes<br>';
//	$sqldis="DELETE FROM distribuye WHERE solvente = 0";
//	$arcdis=mysql_query($sqldis);

	echo 'Obteniendo cantidad de socios Solventes<br>';
	$sqldis="SELECT count(cod_prof) as cuantos FROM distribuye WHERE solvente = 1";
	$arcdis=mysql_query($sqldis);
	$ssolvents = mysql_fetch_assoc($arcdis);
	$ssolvents = $ssolvents['cuantos'];
	$reparto = ($_POST['montosiguiente'] / $ssolvents);
	
	echo "Asignando monto a repartir ".number_format($reparto,2,".",',')." a $ssolvents Socios Solventes <br>";
	$sqlsol="UPDATE distribuye set distribucion = $reparto where solvente = 1";	
	$arcdis=mysql_query($sqlsol) or die('Fallo reparto 1');
	echo 'Datos listos para obtener reporte<br>';

	$sqlsol="SELECT * FROM distribuye";	
	$arcdis=mysql_query($sqlsol) or die('Fallo respaldo 2');
	while ($row = mysql_fetch_assoc($arcdis))
	{
		$cod=$row['cod_prof'];
		$ced=$row['ced_prof'];
		$nom=$row['nombr_prof'];
		$cpc=$row['cpc_prof'];
		$sta=$row['statu_prof'];
		$ing=$row['f_ing_capu'];
		$dis=$row['distribucion'];
		$apo=$row['faport_emp'];
		$sol=$row['solvente'];
		$ult=$row['ultap_prof'];
		$pro=$row['procesado'];
		$sqlsoc="INSERT INTO ".$_SESSION['bdd']."_sgcadistribuye (cod_prof, ced_prof, nombr_prof, cpc_prof, statu_prof, f_ing_capu, distribucion, faport_emp, solvente, ultap_prof, procesado) VALUES ('$cod', '$ced', '$nom', '$cpc', '$sta', '$ing', '$dis', '$apo', '$sol', '$ult', '$pro')";
//		echo $sqlsoc;
		$arcsoc=mysql_query($sqlsoc) or die('Fallo respaldo 3 $cod' . $sqlsoc);
	}

//	echo '<input type="submit" name="Submit" value="Emitir Reporte" />';
	echo '<input type="submit" name="Submit" value="Emitir Reporte" onClick="abrirVentana(';
	echo "'";
	echo $fechasiguiente;
//	echo "&c1=".$c1."&cu=".$cu."&ia=".$ia."&ac=".$ac."&ta=".$ta."&tc=".$tc."&i1=".$i1;
//	echo "&otro=".$better_token.$better_token;
//	echo "&aa=$capital&bb=$cuotas&ia=$ia[$cuotas]&cc=$cu[1]&dd=$interes&socio=";
//	echo trim($r_200['ape_prof']). ', '.trim($r_200['nombr_prof']);
//	echo "&prestamo=".trim($r_310['descr_pres']);
$elasiento='';
	echo "&comprobante=";
	echo $elasiento;
	echo "'";
	echo ');">  ';
	
	echo '</form>';
}
if ($accion == "imprimir") 
{
	echo "<form action='aportes.php?accion=Asiento' name='form1' method='post' onsubmit='return realiza_asiento_montepio(form1)'>";
	echo '<input type="hidden" name="fechasiguiente" value = "'.$fechasiguiente.'">';
	echo '<input type="hidden" name="montosiguiente" value = "'.$_POST['montosiguiente'].'">';
	echo '<input type="submit" name="Submit" value="Generar Asiento Contable" />';
	echo '</form>';
}

if ($accion == "Asiento")
{
	$sqlsol="select * from ".$_SESSION['bdd']."_sgcaf000 where tipo = 'CtaAporteVisado'";
	$arcdis=mysql_query($sqlsol);
	$arcdis=mysql_fetch_assoc($arcdis);
	$ctaaportesocio=trim($arcdis['nombre']).'-';
	$sqlsol="select * from ".$_SESSION['bdd']."_sgcaf000 where tipo = 'Ing.xDist.Visado'";
	$arcdis=mysql_query($sqlsol);
	$arcdis=mysql_fetch_assoc($arcdis);
	$ingvisado=trim($arcdis['nombre']);

	$sqlsol="select * from ".$_SESSION['bdd']."_sgcaf000 where tipo = 'visadoxpagar'";
	$arcdis=mysql_query($sqlsol);
	$arcdis=mysql_fetch_assoc($arcdis);
	$visadoxpagar=trim($arcdis['nombre']);

	
	$hoy = $comprobante = fechaactual(); // $_POST['fechasiguiente'];
	$comprobante = explode("-", $comprobante);
	$asiento = $comprobante[2].$comprobante[1].$comprobante[0].'011';
	//
	$asiento=$hoy;
	$asiento=explode('-',$asiento);
	$asiento=$asiento[0].$asiento[1].$asiento[2];
	$ultimo="select (con_compr+1) as nuevo from ".$_SESSION['bdd']."_sgcaf8co limit 1";
	$aultimo=mysql_query($ultimo);
	$rultimo=mysql_fetch_assoc($aultimo);
	$elultimo=$rultimo['nuevo'];
	$elultimo=ceroizq($elultimo,3);
	$ultimo="update ".$_SESSION['bdd']."_sgcaf8co set con_compr ='$elultimo' limit 1";
	$aultimo=mysql_query($ultimo);
	
	$asiento.=$elultimo;
	//
	
	echo "Generando Comprobante / Registros contables del asiento <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong><br>";
	$desc='Aporte Visado correspondiente al '.convertir_fechadmy($fechasiguiente);
	$desc1='Aporte Visado al '.convertir_fechadmy($fechasiguiente);
	$explicacion=$desc;
	$b=fechaactual(); // $_POST['fechasiguiente']; // explode('/',$fechadelpago);
	$sql = "INSERT INTO ".$_SESSION['bdd']."_sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '".$b."', '$desc','',0,0,0,0,0,0,0,\"$explicacion\")"; 
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".mysql_error()."<br>".$sql);
	$token=$_POST['fechasiguiente'];
$ruta=$_SERVER["DOCUMENT_ROOT"]."/cacpcel/sistemacajadeahorro/soportecontable/".$token.".pdf"; // 

$sql830="update ".$_SESSION['bdd']."_sgcaf830 set enc_soporte = LOAD_FILE('".$ruta."') where enc_clave='".$asiento."'";
$r830=mysql_query($sql830) or die('Fallo 830');
	$sqldis="select * from ".$_SESSION['bdd']."_sgcadistribuye where solvente = 1 and faport_emp='$fechasiguiente' order by cod_prof";
//	echo $sqldis;
	$arcdis=mysql_query($sqldis);
	$concepto=$desc1;
	$thaber = 0;
	$referencia ='';
	while ($row = mysql_fetch_assoc($arcdis))
	{
		$debe = $monto=$row['distribucion'];
		$registro = $row['registro'];
		$thaber += $debe;
		$socio=$row['cod_prof'];
		$sqlsocio="UPDATE  ".$_SESSION['bdd']."_sgcaf200 set ultap_emp='$b', ultapm_emp='$monto', faport_emp='$b', hab_f_empr=hab_f_empr+".$monto." where cod_prof='$socio'";
		$ressoc=mysql_query($sqlsocio) or die("Fallo actualizar $socio");
		$cuenta1=$ctaaportesocio.substr($socio,1,4);
		agregar_f820($asiento, $b, '-', $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
		$sqlsocio="INSERT INTO ".$_SESSION['bdd']."_fhis200 (cod_prof, hab_prof, hab_ucla, fecha, descri, pago, ip) VALUES ('$socio', 0, '$monto', '$b', '$concepto', '$hoy', '$ip')";
		$ressoc=mysql_query($sqlsocio) or die("Fallo actualizar historico $socio");
		$sqlsoc="UPDATE  ".$_SESSION['bdd']."_sgcadistribuye set procesado = 1 where registro = '$registro'"; 
		$ressoc=mysql_query($sqlsoc) or die("Fallo procesado $socio");
	}
	$sqlsoc="UPDATE  ".$_SESSION['bdd']."_sgcadistribuye set procesado = 1 where solvente = 0 and faport_emp='$fechasiguiente'"; 
	$ressoc=mysql_query($sqlsoc) or die("Fallo procesado 4");
	$debe = $noentregado=$_POST['montosiguiente']-$thaber;
	$cuenta1=$ingvisado;
	agregar_f820($asiento, $b, '-', $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
	$debe = $_POST['montosiguiente'];
	$cuenta1=$visadoxpagar;
	agregar_f820($asiento, $b, '+', $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
	$sqlsol="delete from  ".$_SESSION['bdd']."_sgcaf210 where f_ult_apo='".$fechasiguiente."'";
$r830=mysql_query($sqlsol) or die('Fallo 830-2');
	
	$sqlsol="INSERT INTO  ".$_SESSION['bdd']."_sgcaf210 (f_ult_apo, monto, fechapago) VALUES ('$fechasiguiente', $montosiguiente, '".fechaactual()."')";
	$arcdis=mysql_query($sqlsol) or die ('Fallo f210 '.$sqlsol);
	$token=$_POST['fechasiguiente'];
	$ruta=$_SERVER["DOCUMENT_ROOT"]."/cacpcel/sistemacajadeahorro/soportecontable/".$token.".pdf"; // local
	$ruta="/public_html/sistemacajadeahorro/soportecontable/".$token.".pdf"; // 
	
$sql830="update ".$_SESSION['bdd']."_sgcaf210 set reporte = LOAD_FILE('".$ruta."') where f_ult_apo='".$fechasiguiente."'";
 echo $sql830;
$r830=mysql_query($sql830) or die('Fallo 830-2');
	
	set_time_limit(30);	
	echo 'Proceso finalizado...<br />' ;	
}

/*
	if ($accion == "pedirarchivo") {
		echo '<div id="div1">';
		echo '<fieldset>';
		echo '<legend>Fecha de Nomina: '.$fechaaporte."</legend><br>";
		$lafecha=convertir_fecha($fechaaporte);
		$sql = "select * from t_his200 where fecha = '$lafecha' limit 1";
		$rs=(mysql_query($sql)) or die ("<p />Estimado usuario $usuario contacte al administrador Código Nomi-3- <br>".mysql_error()."<br>".$sql);
		if (mysql_num_rows($rs) > 0)
			die ("<h1>No se puede procesar nómina con esta fecha. Ya existe otra con la misma fecha ($fechaaporte)</h1>");
		echo '<form action="aportes.php" method="post" name="form1" enctype="multipart/form-data">';
		echo '<input type="hidden" name="aportespagos" value = "on"/>';
		echo '<input type="hidden" name="accion" value="verificar">';
		echo '<input type="hidden" name="fechaaporte" value="'.$fechaaporte.'" /> Aportes No pagados <br />';
		echo '<input name="archivo" type="file" value="Examinar"><br>';
		echo '<input type="submit" name="Submit" value="Procesar" />';
//		echo "<td><img src='imagenes/animadas/checklist_sm_wht.gif' width='36' height='36' border='0' /></td>";
//		echo 'Verificando archivo <br>';
		echo '</fieldset>';
		echo '</form>';
	}

	if ($accion == "verificar") {
		echo '<div id="div1">';
		//------------------------------------------
		$copiado = 'SI';		// cambiar a no y resolver este problema
		if(@$_FILES['archivo']!=='') // {
			$nueva_ruta='/nominas/';
			$ruta_total = $_SERVER['DOCUMENT_ROOT'].$nueva_ruta;
			$ruta_total = $_SERVER['DOCUMENT_ROOT']."/cajaweb/nominas/".$_FILES['archivo']['name'];
			$BASENAMES = basename( $_FILES['archivo']['name']);
			$nuevo_nombre=$BASENAMES;
		if (is_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'])) {
//		    copy($HTTP_POST_FILES['archivo']['tmp_name'], "/nominas");
			} else {
		    	echo "Possible file upload attack. Filename: " . $HTTP_POST_FILES['archivo']['name'];
			}
			//------------------------------------------

			if (move_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'], $ruta_total))
			{
				echo '<fieldset><legend>Fecha de la Nomina: '.$fechaaporte.'</legend><br>';
				//	$archivo_name = $_POST['archivo_name'];
				$archivo_name = $nuevo_nombre; 
				$original = $archivo_name;
			//	echo 'http: '.$HTTP_POST_FILES['archivo']['tmp_name'];
				$extension = explode(".",$archivo_name);
				$num = count($extension)-1;
				if (1 == 1) { // (strtoupper($extension[$num]) == "TXT") {
					if($copiado = 'SI') { // $archivo_size < 60000) {
			//			 if (1 == 1) { // (move_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'], "nominas/".$archivo)) {
			//			 if (move_uploaded_file($archivo_name, $archivo_name)) {
						// if(!copy($archivo, "nominas/".$archivo_name)) {
			//				echo "error al copiar el archivo"; }
			//			else { // echo "archivo subido con exito <br>";
							// separar el archivo con los datos
						procesar($archivo_name,$fechaaporte,$ip);
			//				}
					}
				else
					{ echo "el archivo supera los 60kb"; }
				}
				else
					{ echo "el formato de archivo no es valido, solo .txt => ".$original; }
				echo '</fieldset>';
			}
			else die('fallo la copia');
				
		echo '</div>';
		set_time_limit(30);
	}
	if ($accion == "preparar") { 
		// preparar la impresion
		echo '<div id="div1">';
		// imprimo y preparo proceso
		echo '<fieldset>';
		echo "<td><img src='imagenes/animadas/printingjob_md_wht.gif' width='36' height='36' border='0' /></td>";
		echo "<a target=\"_blank\" href=\"aportesimp.php?elarchivo=$elarchivo&proceso=$proceso&fechaaporte=$fechaaporte\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Reporte</a><br>"; 
		echo "<td><img src='imagenes/animadas/checklist_sm_wht.gif' width='36' height='36' border='0' /></td>";
//		echo 'Verificando archivo <br>';
		echo "<a href=\"aportes.php?accion=almacenar&elarchivo=$elarchivo&aportespagos=on&proceso=$proceso&fechaaporte=$fechaaporte\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Guardar información</a>"; 
		echo '</fieldset>';
		echo '</div>';
	}

	if ($accion == "almacenar") { 
		// preparar la impresion
		echo '<div id="div1">';
		echo '<fieldset><legend>Procesando información...</legend><br />' ;
		$sql="select * from sgcanomi where proceso='$proceso'";
		$rs=(mysql_query($sql)) or die ("<p />Estimado usuario $usuario contacte al administrador Código Nomi-3- <br>".mysql_error()."<br>".$sql);
		$registros=mysql_num_rows($rs);
		if ($registros < 30)
			set_time_limit(30);	
		else set_time_limit($registros);	
		echo 'Agregando información...<br />' ;
		$emonto = $emonto2 = 0;
		while ($row = mysql_fetch_assoc($rs))
		{
			$sql2="insert into t_his200 (cod_prof, hab_prof, hab_ucla, fecha, ip, proceso,cedula) values ('".$row[socio]."', '".$row[monto]."', '".$row[monto2]."', '".$row[fecha]."', '$ip', '".$row[proceso]."', '".$row[cedula]."')";
			$emonto+=$row[monto];
			$emonto2+=$row[monto2];
			$lafechaa=$row[fecha];
			$rs2=(mysql_query($sql2)) or die ("<p />Estimado usuario $usuario contacte al administrador Código Nomi-4- <br>".mysql_error()."<br>".$sql2);
			$sqls="select * from sgcaf200 where cod_prof='".$row['socio']."'";
			$sqlas=mysql_query($sqls);
			$sqlrs=mysql_fetch_assoc($sqlas);
			if ($sqlrs['f_ing_capu'] == '0000-00-00')
			{
				echo 'Actualizando estatus de '.$sqlrs['ape_prof'].' '.$sqlrs['nombr_prof'].' '.$sqlrs['ced_prof'].'<br>';
				$sqls="update sgcaf200 set statu_prof='Activo', f_ing_capu='".$row['fecha']."' where cod_prof='".$row['socio']."'";
				$sqlas=mysql_query($sqls);
				
			}
		}	
		echo 'Información Agregada...<br />' ;
		$lafecha=explode("-",$lafechaa);
		$asiento=$lafecha[2].$lafecha[1].$lafecha[0].'010';
		$b=explode("/",$fechaaporte); // $rs[fecha]; 12/1/2011
		$b=$b[2].'-'.$b[1].'-'.$b[0];
		$explicacion='';
//		echo 'Generando Comprobante Contable...'.$asiento.'<br />' ;

	echo "Realizando Abonos / Registros contables del asiento <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong><br>";

		$desc='Nomina x Cobrar Retencion/Aporte del '.$fechaaporte;
		$explicacion=$desc;
		$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '$b', '$desc','',0,0,0,0,0,0,0,\"$explicacion\")"; 
		if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".mysql_error()."<br>".$sql);
		$sql="select nombre from sgcaf000 where tipo='NomxCobr'";
		$result=mysql_query($sql) or  die ("El usuario $usuario no tiene permiso para consultar configuración <br>".mysql_error());
		$row = mysql_fetch_assoc($result);
		$elcargo='+';
		$debe=$emonto;
		$haber=0;
		$concepto=$desc;
		$referencia=$asiento;
		$cuenta1=$row[nombre];
		agregar_f820($asiento, $b, '+', $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
		$row = mysql_fetch_assoc($result);
		$cuenta1=$row[nombre];
		$debe=$emonto2;
		agregar_f820($asiento, $b, '+', $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
		$elcargo='-';
		$debe=$emonto;
		$haber=$emonto;
		$row = mysql_fetch_assoc($result);
		$cuenta1=$row[nombre];
		agregar_f820($asiento, $b, '-', $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
		$row = mysql_fetch_assoc($result);
		$cuenta1=$row[nombre];
		$debe=$emonto2;
		$haber=$emonto2;
		agregar_f820($asiento, $b, '-', $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
		set_time_limit(30);	
		echo 'Proceso finalizado...<br />' ;
		echo '</fieldset>';
		echo '</div>';
	}
else
{
	// estan pagando
	if (($accion == "ssinpago")) { //  and ($aportespagos == 'on')) {
	echo '<fieldset><legend>Resumen Para Retencion/Aporte</legend>';
	echo '<table align="center" class="basica 100 hover" width="300" border="1">';
	echo '<tr><td>Ahorro Socio</td><td>';
	echo '<input type="text" name="totalnominasocio" id="totalnominasocio" size="8" maxlengt="8"  value=0.00 readonly="readonly"></td></tr>';
	echo '<tr><td>Total Registros Socio</td><td>';
	echo '<input type="text" name="totalregistrosocio" id="totalregistrosocio" size="5" maxlengt="5"  value=0  readonly="readonly"></td></tr>';
	echo '<tr><td>Ahorro UCLA</td><td>';
	echo '<input type="text" name="totalnominaucla" id="totalnominaucla" size="8" maxlengt="8"  value=0.00 readonly="readonly"></td></tr>';
	echo '<tr><td>Total Registros UCLA</td><td>';
	echo '<input type="text" name="totalregistroucla" id="totalregistroucla" size="5" maxlengt="5"  value=0  readonly="readonly"></td></tr>';
	echo '<tr><td>Total Nominas </td><td>';
	echo '<input type="text" name="totalnominas" id="totalnominas" size="8" maxlengt="8"  value=0.00 readonly="readonly"></td></tr>';
	echo '<tr><td>Total Registros</td><td>';
	echo '<input type="text" name="totalregistros" id="totalregistros" size="5" maxlengt="5"  value=0  readonly="readonly"></td></tr>';
	echo '</table>';
	echo '</legend>';
//	echo '<input type="submit" name="Submit" value="Realizar Proceso Retencion/Aporte (Asientos Contables)" />';
	echo '</form>';
	echo '</fieldset>';

		echo '<div id="div1">';
		echo "<form action='aportes.php?accion=fechadepago' name='form1' method='post'>";
		$sql='select fecha, count(fecha) as cantidad, sum(hab_prof) as socio, sum(hab_ucla) as ucla from t_his200 group by fecha order by fecha desc';
		$resultado=mysql_query($sql);
//		seleccionando varios items
		echo "<table class='basica 100 hover' width='100%'>";
		$registrosr = $registrosa = 0;
		while ($fila2 = mysql_fetch_assoc($resultado)) {
			if (($fila2['cantidad'] > 0) and (($fila2['socio'] > 0) or ($fila2['ucla'] > 0)))
				if ($fila2['socio'] > 0)
				{
					$registrosr++;
					echo '<td class="centro azul">';
					echo '<input type="checkbox" id="retencion'.$registrosr.'" name="retencion'.$registrosr.'" value="'.$fila2["fecha"] .'"  onClick="relacion_ahorro()" > </td>';
					echo '<td class="centro azul">Retención</td>';
					echo '<td class="centro azul">' .convertir_fechadmy($fila2['fecha']).' </td><td  class="dcha azul">'.$fila2['cantidad'].' </td><td  class="dcha azul"> '.number_format($fila2['socio'],2,'.',',').'</td></tr>';
//					echo '<td class="centro azul"><input type="checkbox" id="retencion'.$registrosr.'" name="retenciones[]" value='.$fila2["fecha"] .'  onClick="relacion_ahorro()" > </td>' .'<td class="centro azul">Retención</td> <td class="centro azul">' .convertir_fechadmy($fila2['fecha']).' </td><td  class="dcha azul">'.$fila2['cantidad'].' </td><td  class="dcha azul"> '.number_format($fila2['socio'],2,'.',',').'</td></tr>';
				}
				if ($fila2['ucla'] > 0)
				{
					$registrosa++;
					echo '<td class="centro rojo"><input type="checkbox" id="aporte'.$registrosa.'" name="aporte'.$registrosa.'" value='.$fila2["fecha"] .'  onClick="relacion_ahorro()" > </td>' .'<td class="centro rojo">Aportes</td> <td class="centro rojo">' .convertir_fechadmy($fila2['fecha']).' </td><td class="rojo dcha">'.$fila2['cantidad'].' </td><td  class="rojo dcha"> '.number_format($fila2['ucla'],2,'.',',').'</td></tr>';
//					echo '<td class="centro rojo"><input type="checkbox" id="aporte'.$registrosa.'" name="aportes[]" value='.$fila2["fecha"] .'  onClick="relacion_ahorro()" > </td>' .'<td class="centro rojo">Aportes</td> <td class="centro rojo">' .convertir_fechadmy($fila2['fecha']).' </td><td class="rojo dcha">'.$fila2['cantidad'].' </td><td  class="rojo dcha"> '.number_format($fila2['ucla'],2,'.',',').'</td></tr>';
				}
			}
		echo '</table>';		
		echo "<input type = 'hidden' value ='".$registrosr."' name='registrosr' id='registrosr'>";
		echo "<input type = 'hidden' value ='".$registrosa."' name='registrosa' id='registrosa'>";
		echo '<input type="submit" name="Submit" value="Enviar" />';
		echo '</form>';
		echo '</div>';
	}
	if (($accion == "fechadepago")) { //  and ($aportespagos == 'on')) {
		echo '<div id="div1">';
//		echo "<form action='aportes.php?accion=quepagaron&fechadeaporte=$fechadeaporte&' name='form1' method='post'>"; // una sola fecha
		echo "<form action='aportes.php?accion=fechaseleccionada&' name='form1' method='post'>";
		// para varias fechas busco en mysql y lo E
		$eltotal=0;
		$proceso = time();
		$proceso = date("Y-m-d h:i:s",$proceso);
		for ($i=0; $i<$_POST['registrosr'];$i++) {
//			echo "<br />value $i = ".$_POST['retenciones'][$i];
			$variable='retencion'.($i+1);
//			echo '$'.$variable;
//			echo '$$'.$_POST[$variable];
			if ($$variable != "")
			{
//				$sql='select sum(hab_prof) as socio from t_his200 where fecha="'.$_POST['retenciones'][$i].'" group by fecha';
				$sql='select sum(hab_prof) as socio from t_his200 where fecha="'.$$variable.'" group by fecha';
//			echo $sql.'<br>';
				$resultado=mysql_query($sql);
				$fila2 = mysql_fetch_assoc($resultado);
				$eltotal+=$fila2['socio'];
//				$sql="insert into sgcatnom (proceso, fecha, tipo) values ('$proceso', '".$_POST['retenciones'][$i]."', 'R')";
				$sql="insert into sgcatnom (proceso, fecha, tipo) values ('$proceso', '".$_POST[$variable]."', 'R')";
//			echo $sql;
				$resultado=mysql_query($sql) or die(mysql_error());
			}
		}
		$x=0;
		for ($i=0; $i<$_POST['registrosa'];$i++) {
//			echo "<br />value $i = ".$_POST['aportes'][$i];
			$variable='aporte'.($i+1);
//			echo '$'.$variable;
//			echo '$$'.$_POST[$variable];
			if ($$variable != "")
			{
				$sql='select sum(hab_ucla) as ucla from t_his200 where fecha="'.$$variable.'" group by fecha';
//			$sql='select sum(hab_ucla) as ucla from t_his200 where fecha="'.$_POST['aportes'][$i].'" group by fecha';
//				echo $sql.'<br>';
				$resultado=mysql_query($sql);
				$fila2 = mysql_fetch_assoc($resultado);
				$eltotal+=$fila2['ucla'];
//				$sql="insert into sgcatnom (proceso, fecha, tipo) values ('$proceso', '".$_POST['aportes'][$i]."', 'A')";
				$sql="insert into sgcatnom (proceso, fecha, tipo) values ('$proceso', '".$_POST[$variable]."', 'A')";
				$resultado=mysql_query($sql)  or die(mysql_error());
//				echo $sql.'<br>';
			}
		}
//		$eret=array_envia($aretenciones);
		echo '<h1>Monto de los Pagos a Realizar '.number_format($eltotal,2,'.',',').'</h1>';
		//
		echo 'Fecha en que se realizo el pago: ';
	$hoy = date("d/m/Y");
    $fechanueva=explode('/',$hoy);
	$fechanueva=$fechanueva[1].'/'.$fechanueva[0].'/'.$fechanueva[2];
	$sqlano='select substr(fech_ejerc,1,4) as ano from sgcaf100';
	$sqlfano=mysql_query($sqlano);
	$sqlrano=mysql_fetch_assoc($sqlfano);
	$rango=$sqlrano['ano'];
	$sqlano='select substr(now(),1,4)';
	$sqlfano=mysql_query($sqlano);
	$sqlrano=mysql_fetch_assoc($sqlfano);
	if ($sqlrano['ano'] > $rango)
		$rango.=', '.$sqlrano['ano'];
	?>
	<input type="hidden" name="fechadelpago" id="fechadelpago" value=" <?php  echo $fechanueva; ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_ingcapu" 
   ><?php  echo '  / /  '; ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechadelpago",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_ingcapu",       // ID of the span where the date is to be shown
//        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        daFormat       :    "%B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 
		range          :     <?php echo $rango; ?>,

// desactivacion de 18 años pa' tras


				    });
</script>
<?php		
		
		echo '<br>Indique el número de referencia: ';
		echo '<input type="text" name="referencia" size="8" maxlengt="8"  />';
		echo '<input type="submit" name="Submit" value="Continuar" />';
		echo '<input type="hidden" name="proceso" value="'.$proceso.'">';
		echo '</form>';
		echo '</div>';		
	}
	if (($accion == "fechaseleccionada")) { //  and ($aportespagos == 'on')) {
		echo '<div id="div1">';
		$fechapago=convertir_fecha($fechadelpago);
		$sql='insert into t_210 (f_ult_apo, tipo_soc) values ("'.$fechapago.'","T")';
		$result=mysql_query($sql);
//		$sql='select * from t_210 where f_ult_apo="'.$fechapago . '" and tipo_soc ="T"';
		$sql='select * from t_210 where tipo_soc = "T"';
		$result=mysql_query($sql);
		$fila1 = mysql_fetch_assoc($result);
// 		$sql='delete from t_210 where f_ult_apo="'.$fechapago . '" and tipo_soc ="T"';
		$sql='delete from t_210 where tipo_soc = "T"';
		$result=mysql_query($sql);
		$comprobante = $fila1['f_ult_apo'];
//		echo 'fecha generada '.$comprobante;
		$comprobante = explode("-", $comprobante);
		$asiento = $comprobante[2].$comprobante[1].$comprobante[0].'011';

//		echo 'Generando Comprobante Contable...'.$asiento.'<br />' ;
		echo "Generando Comprobante / Registros contables del asiento <strong><a target=\"_blank\" href='editasi2.php?asiento=$asiento'>$asiento </a></strong><br>";
		$desc='Cancelación Retencion y/o Aporte realizada el '.$fechadelpago;
		$explicacion=$desc;
		$b=explode('/',$fechadelpago);
		$b=$b[2].'-'.$b[1].'-'.$b[0];
//		echo 'la fecha '.$b;
		$sql = "INSERT INTO sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$asiento', '".$b."', '$desc','',0,0,0,0,0,0,0,\"$explicacion\")"; 
		if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".mysql_error()."<br>".$sql);
$ruta=$_SERVER["DOCUMENT_ROOT"]."/cacpcel/soportecontable/".$token.".pdf"; // 
$sql830="update ".$_SESSION['bdd']."_sgcaf830 set enc_soporte = LOAD_FILE('".$ruta."') where enc_clave='".$asiento."'";

		$eltotal=$totalaporte=$totalahorro=0;
		$sql='select * from sgcatnom where proceso="'.$proceso.'"';
		$resultado=mysql_query($sql);
		while ($fila1 = mysql_fetch_assoc($resultado)) {
			$sql="select * from t_his200 where fecha='".$fila1['fecha']."' order by cod_prof";
//			echo $sql.'<br>';
			$resulta2=mysql_query($sql);
			$registros=mysql_num_rows($resulta2);
			$descripcion=($fila1['tipo']=='R'?'Ret.':'Apt.').' del '.convertir_fechadmy($fila1['fecha']).' pagado el ('.$fechadelpago.')';
			echo '<h2>'.$descripcion.'</h2><br>';

			$sql="insert into aportep (tipo, fecha) values ('".$fila1['tipo']."','".$fila1['fecha']."')";
			$resulta_aportep=mysql_query($sql);
//			echo $sql;

			$subtotal = 0;			
			$cuantos =0;
			while ($enfila2 = mysql_fetch_assoc($resulta2)) {
//				echo $enfila2['cod_prof'].'<br>';
				set_time_limit($registros);	
				if ($fila1['tipo']=='R') {
					$monto=$enfila2['hab_prof'];
					$totalahorro+=$monto;
					$sql1="insert into fhis200 (cod_prof, hab_prof, fecha, descri, pago, ip) values ('".$enfila2['cod_prof']."',$monto, '". $enfila2['fecha']."', '$descripcion', '$fechapago', '$ip')";
					$sql2="update t_his200 set hab_prof = 0 where numreg =". $enfila2['numreg'];
					$sql3="update sgcaf200 set hab_f_prof = hab_f_prof + ".$monto.", ultap_prof='".$enfila2['fecha']."', ultapm_prof='".$monto."' where cod_prof ='". $enfila2['cod_prof']."'";
					if (!mysql_query($sql1)) die ("El usuario $usuario no tiene permiso para HF200-1.<br>".mysql_error()."<br>".$sql1);
					if (!mysql_query($sql2)) die ("El usuario $usuario no tiene permiso para T200-1.<br>".mysql_error()."<br>".$sql2);
					if (!mysql_query($sql3)) die ("El usuario $usuario no tiene permiso para F200-1.<br>".mysql_error()."<br>".$sql3);
					$cuantos++;
					}
				else {
					$monto=$enfila2['hab_ucla'];
					$totalaporte+=$monto;
					$sql1="insert into fhis200 (cod_prof, hab_ucla, fecha, descri, pago, ip) values ('".$enfila2['cod_prof']."',$monto, '". $enfila2['fecha']."', '$descripcion', '$fechapago', '$ip')";
					$sql2="update t_his200 set hab_ucla = 0 where numreg =". $enfila2['numreg'];
					$sql3="update sgcaf200 set hab_f_empr = hab_f_empr + ".$monto.", ultap_emp='".$enfila2['fecha']."', ultapm_emp='".$monto."' where cod_prof ='". $enfila2['cod_prof']."'";
					if (!mysql_query($sql1)) die ("El usuario $usuario no tiene permiso para HF200-2.<br>".mysql_error()."<br>".$sql1);
					if (!mysql_query($sql2)) die ("El usuario $usuario no tiene permiso para T200-2.<br>".mysql_error()."<br>".$sql2);
					if (!mysql_query($sql3)) die ("El usuario $usuario no tiene permiso para F200-2.<br>".mysql_error()."<br>".$sql3);
					$cuantos++;
					}
				$subtotal+=$monto;
				$eltotal+=$monto;
			}
// 			echo 'cuantos '.$cuantos;
			// genero registro parcial del pago
			if ($fila1['tipo']=='R') {
				$sql3="select nombre from sgcaf000 where tipo='NomxPag2+'";	 // cargo a ahorros x distribuir
				hacer_asiento('NomxPag2+',$subtotal,'+',$descripcion,$asiento,$b,$referencia);
				$sql4="select nombre from sgcaf000 where tipo='NomxPag2-'";	 // abono a cuenta x cobrar ahorros
				hacer_asiento('NomxPag2-',$subtotal,'-',$descripcion,$asiento,$b,$referencia);
			}
			else {
				$sql3="select nombre from sgcaf000 where tipo='NomxPag3+'";	// cargo a aportes x distribuir
				$sql4="select nombre from sgcaf000 where tipo='NomxPag3-'";	 // abono cuenta x cobrar aportes
				hacer_asiento('NomxPag3+',$subtotal,'+',$descripcion,$asiento,$b,$referencia);
				hacer_asiento('NomxPag3-',$subtotal,'-',$descripcion,$asiento,$b,$referencia);
			}
		}
		// elimino la info para no crear basura 
		$sql='delete from sgcatnom where proceso="'.$proceso.'"';
		$resultado=mysql_query($sql);
		hacer_asiento('NomxPag1+',$eltotal,'+',$desc,$asiento,$b,$referencia);
		if ($totalahorro != 0) 
			hacer_asiento('NomxPag4-',$totalahorro,'-','Retenciones cancelada el '.$fechadelpago,$asiento,$b,$referencia);
		if ($totalaporte != 0) 
			hacer_asiento('NomxPag5-',$totalaporte,'-','Aportes cancelado el '.$fechadelpago,$asiento,$b,$referencia);
		echo '<h2>Proceso Completado </h2><br>';
		echo '</div>';		
		set_time_limit(30);	
}
}

function hacer_asiento($cuentabuscar, $monto, $debcre, $desc,$asiento,$fechadelpago,$referencia)
{
		$sql="select nombre from sgcaf000 where tipo='".$cuentabuscar."'";	
		$result=mysql_query($sql) or  die ("El usuario $usuario no tiene permiso para consultar configuración <br>".mysql_error());
		$row = mysql_fetch_assoc($result);
		$elcargo=$debcre;
//		if ($elcargo == '+') {
			$debe=$monto;
			$haber=0; // }
		$concepto=$desc;
// 		$referencia=$asiento;
		$cuenta1=$row[nombre];
		echo 'Generando registro '.$concepto.'<br>';
		agregar_f820($asiento, $fechadelpago, $elcargo, $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$referencia,'','S',0);
}

function array_envia($array) {
    $tmp = serialize($array);
    $tmp = urlencode($tmp);
    return $tmp;
} 

function array_recibe($url_array) {
    $tmp = stripslashes($url_array);
    $tmp = urldecode($tmp);
    $tmp = unserialize($tmp);
   return $tmp;
} 

function procesar($archivo_name,$fechaaporte,$ip)
{
	echo 'Verificación de archivo <br>';
	$lines = file('nominas/'.$archivo_name);
	$faltoalguno=0;
	set_time_limit($lines);
	foreach ($lines as $line_num => $linea) {
		$datos = explode("|", $linea);
		$cedula=ceroizq(trim(substr($datos[0],0,8)),8);
		$cedula = 'V-'.$cedula;
		$sql='select ape_prof, nombr_prof from sgcaf200 where ced_prof="'.$cedula.'"';
		$result=mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para generar consulta<br>".mysql_error()."<br>".$sql);
		if (mysql_num_rows($result) == 0) {
			echo 'La cédula '.$cedula.' no esta registrada <br>';
			$faltoalguno = 1; }
					
// 					echo substr($datos[0],0,8).' - '.substr($datos[0],10,4).' - '.substr($datos[0],16,10).'<br>';
//					echo $datos[0].' - '.$datos[1].' - '.$datos[2].' - '.$datos[3].' - '.$datos[4].' - '.$datos[5].' - '.$datos[6].' - '.$datos[7].'<br>';
				}
		if ($faltoalguno == 0) 
		{
			$lafecha=convertir_fecha($fechaaporte);
//			echo $lafecha;
			$proceso = time();
			$proceso = date("Y-m-d h:i:s",$proceso);
			echo 'Convirtiendo archivo <br>';
			foreach ($lines as $line_num => $line) {
				$datos = explode("|", $line);
				$codigo=substr($datos[0],10,4);
				$monto=trim(substr($datos[0],14,12));
				$cedula=ceroizq(trim(substr($datos[0],0,8)),8);
				$cedula = 'V-'.$cedula;
				$sql='select ape_prof, nombr_prof, cod_prof from sgcaf200 where ced_prof="'.$cedula.'"';
				$result=mysql_query($sql) or die ("<p />El usuario $usuario no tiene permisos para generar consulta<br>".mysql_error()."<br>".$sql);
				$row_socio=mysql_fetch_assoc($result);
				$socio = $row_socio['cod_prof'];
				$sql="select * from sgcanomi where cedula = '$cedula' and proceso='$proceso'";
				$rs=(mysql_query($sql)) or die ("<p />Estimado usuario $usuario contacte al administrador Código Nomi-1- <br>".mysql_error()."<br>".$elsql); 
				$row=mysql_num_rows($rs);
				if ($row == 0)
				{ 
					//echo '.';
					$sql="insert into sgcanomi (archivo, fecha, cedula, socio, nombre, codigo, monto, proceso, ip) VALUES ('$archivo_name','$lafecha','$cedula', '$socio', '".trim($row_socio['ape_prof'])." ,".trim($row_socio['nombr_prof'])."', '$codigo', '$monto', '$proceso', '$ip')";
				}
				else {
					// echo ':';
					$sql = "update sgcanomi set codigo2='$codigo', monto2='$monto' where cedula = '$cedula' and proceso='$proceso'";
					}
				$rs=(mysql_query($sql)) or die ("<p />Estimado usuario $usuario contacte al administrador Código Nomi-2- <br>".mysql_error()."<br>".$sql);
				}
				echo "<a href=\"aportes.php?accion=preparar&aportespagos=on&elarchivo=$archivo_name&proceso=$proceso&fechaaporte=$fechaaporte\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Continuar proceso</a>"; 
			}

}

// ALTER TABLE `cacpcel_sgcaf210` ADD `monto` DECIMAL( 12, 2 ) NOT NULL ;
// ALTER TABLE `cacpcel_sgcaf210` ADD PRIMARY KEY ( `f_ult_apo` ) 
ALTER TABLE `cacpcel_sgcadistribuye` ADD `registro` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ;
ALTER TABLE `cacpcel_sgcaf210` ADD `fechapago` DATE NOT NULL ,
ADD `reporte` LONGBLOB NOT NULL ;
delete from cacpcel_sgcaf210;
 TRUNCATE TABLE `cacpcel_sgcadistribuye` ;
  TRUNCATE TABLE `cacpcel_fhis200` ;
  
*/
?>

<?php //include("pie.php");?>

</body></html>

