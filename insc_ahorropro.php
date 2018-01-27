<?php  
// faltaria meter intereses de mora al prestamo
include("head.php");
include("paginar.php");
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
$mostrarregresar=0;
?>
<script language="Javascript" src="selec_fecha.js" type='text/javascript'></script>
<script language="javascript">

function abrirVentana(elorden, comprobante)
{
window.open("recingpdf.php?orden="+elorden,"parte1","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");	
// window.open("insc_ahorropropdf.php?orden="+elorden,"parte1","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");	
}
</script>

<script src="ajxing.js" type="text/javascript"></script>
<?
if ($accion == 'Anadir') 
	$onload="onload=\"foco('lacedula')\"";
else
	if ($accion =='EscogeRetiro')
		$onload="onload=\"foco('ret_socio')\"";
	else 
		if ($accion == 'Buscar') 
			$onload="onload=\"foco('elretiro')\""; 
		else $onload="onload=\"foco('cedula')\"";
?>
<body <?php if (!$bloqueo) {echo $onload;}?>>

<?php
$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$cedula = $_GET['cedula'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}

/*
if ($accion == "Renovar") {	// seleccionar el tipo de prestamo nuevo de renovacion
	$_SESSION['numeroarenovar']=$_GET['nropre'];
}
if ($accion == "Renovacion") {	// selecciono el tipo de prestamo
	$mostrarregresar=1;
	echo '<div id="div1">';
	$cedula = $_GET['cedula'];
	$elprestamo = $_GET['nropre'];
	$temp = "";
	echo "<form enctype='multipart/form-data' action='insc_ahorropro.php?accion=Solicitar' name='form1' id='form1' method='post' onsubmit='return valpre(form1)'";
	echo "<input type = 'hidden' value ='".$cedula."' name='cedula'>";
	echo "<input type = 'hidden' value ='".$elprestamo."' name='elprestamo'>";
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
	$sql_360="select * from ".$_SESSION['bdd']."_sgcaf360 where cod_pres='$elprestamo'";
	$a_360=mysql_query($sql_360);
	$r_360=mysql_fetch_assoc($a_360);
	$sql_310="select * from ".$_SESSION['bdd']."_sgcaf310 where (cedsoc_sdp='$micedula') and (codpre_sdp='$elprestamo') and (stapre_sdp='A') and (! renovado)";
	$a_310=mysql_query($sql_310);
	if ((! $r_360['masdeuno']) and (mysql_num_rows($a_310) >= 1))	
			echo '<h2>No puede tener mas de un préstamo de este tipo</h2>';
	else {
		pantalla_completar_prestamo($cedula,$elprestamo);
	}
	echo '</form>';
	echo '</div>';
}	// fin de ($accion == "Renovacion")
*/

//----------------------------
if ($accion == 'Buscar')  {
	extract($_POST);
	$elcodigo = trim($_POST['elcodigo']);
	$lacedula = trim($_POST['cedula']);
	if (! $cedula) {
		$lacedula = $_SESSION['cedulasesion']; 
		}
	else 
		$_SESSION['cedulasesion']=$_POST['cedula'];
	if ($lacedula) { //  != ' ') {
		$sql="SELECT * FROM ".$_SESSION['bdd']."_sgcaf200 where ced_prof = '$lacedula'";
		$result=mysql_query($sql);
		$row= mysql_fetch_assoc($result);
		echo "<input type = 'hidden' value ='".$row['ced_prof']."' name='cedula'>"; 
		$cedula=$row['ced_prof'];

//		$accion = 'Editar'; 
		$topevoluntario=20;
		$topeprestamos=35;
		$topepago=60;

		echo '<div id="div1">';
		$sql="SELECT * FROM ".$_SESSION['bdd']."_sgcaf200 WHERE ced_prof= '".$cedula."'";
		$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
		echo "<input type = 'hidden' value ='".$cedula."' name='cedula'>";
		$temp = "";
		echo "<form enctype='multipart/form-data' action='insc_ahorropro.php?accion=GenerarRecibos' name='form1' method='post' onsubmit='return siono(".'"Estan correctos los datos suministrados para la generacion del recibo"'.")'>";

/*
		echo '<div id="pago" style="color:blue; background:yellow;padding:20px;position:fixed;top:'.$topevoluntario.'%;left:60%; font-size: 14px">';
		echo '<fieldset><legend>Solo para Ahorros Voluntarios</legend>';
		echo '<table>';
		echo '<tr>';
		echo '<td align="right" >Monto en Ahorros Voluntarios </td><td>'; // colspan="1"
		echo '<td class="centro azul"><input type="textbox" maxlength="12" size="12" id="cancelarahvol1" name="cancelarahvol1" value="0" onBlur="revisarmontoahorro(\'cancelarahvol1\')">';
		echo '</td></tr>';
		echo '</table>';
		echo '</fieldset>';
		echo '</div>';
*/
/*
// ------------------- si tiene prestamos y quiere abonar o pagar
		$estacedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,3);
		$sql2 = "SELECT * FROM ".$_SESSION['bdd']."_sgcaf310, ".$_SESSION['bdd']."_sgcaf360 WHERE (cedsoc_sdp = '$estacedula' and stapre_sdp='A' and (! renovado)) and codpre_sdp=cod_pres ORDER BY codpre_sdp";
//		echo $sql;
		$rs = mysql_query($sql2);
		if (mysql_num_rows($rs) > 0)
		{
			$i2=0;
			$registrosp=0;
			echo '<div id="pago" style="color:green; background:GreenYellow;padding:20px;position:fixed;top:'.$topeprestamos.'%;left:60%; font-size: 12px">';
			echo '<fieldset><legend>Solo Para Abono Capital / Pago Total (AUN EN PRUEBA - no usar todavia)</legend>';
			echo "<table class='basica 100 hover' width='400'><tr>";
			echo '<th colspan="1">Número/Descripcion</th><th>Saldo</th>';
			while($r1_310=mysql_fetch_assoc($rs)) 
			{
				$cedula	= $r1_310['cedsoc_sdp'];
				$nropre	= $r1_310['nropre_sdp'];
				$tipo	= $r1_310['codpre_sdp'];
				$nombreprestamo = $r1_310['descr_pres'];
				$ctacapital=trim($r1_310['cuent_pres']).'-'.substr($r1_310[codsoc_sdp],1,4);
				$ctainteres= $r1_310['otro_int'];
		
				$sqlpro= "SELECT * FROM ".$_SESSION['bdd']."_proyecciones where nroprestamo = '$nropre' and tipoprestamo='$tipo' and cedula='$cedula' and pagado = 'N'  order by nrocuota limit 1";
//			echo $sqlpro;
				$apro=mysql_query($sqlpro);
				if (mysql_num_rows($apro) == 0) // no esta la proyeccion la creo
				{
					crear_proyeccion($nropre, $tipo, $r1_310['monpre_sdp'], $r1_310['f_1cuo_sdp'] , $r1_310['interes_sd'], $r1_310['nrocuotas'], $r1_310['int_dif'], $cedula, $ctacapital, $ctainteres, $nombreprestamo);
				};
				$apro=mysql_query($sqlpro);
				$r1_310=mysql_fetch_assoc($apro);
				$lacuenta=$r1_310['ctacontable'];
				if ($r1_310['chequeadocontabilidad'] =='N')
				{
					$saldocontable=buscar_saldo_f810($lacuenta);
					echo 'saldo contable '.$saldocontable .' de la cuenta '.$lacuenta;
					$sqlpro2="update ".$_SESSION['bdd']."_proyecciones set pagado = 'S' where nroprestamo = '$nropre' and tipoprestamo='$tipo' and cedula='$cedula' and  saldo >= $saldocontable";
					mysql_query($sqlpro2);
					$sqlpro2="update ".$_SESSION['bdd']."_proyecciones set chequeadocontabilidad = 'S' where nroprestamo = '$nropre' and tipoprestamo='$tipo' and cedula='$cedula'";
					mysql_query($sqlpro2);
				}
				$apro=mysql_query($sqlpro);
				while($r_310_=mysql_fetch_assoc($apro)) 
				{
					echo '<tr>';
					echo '<td>';
					echo $r_310_['nroprestamo'].' / '.$r_310_['nombreprestamo'].'</td>';
					$lacuenta=$r_310_['ctacapital']; //  trim($r_310['cuent_pres']).'-'.substr($r_310[codsoc_sdp],1,4);
					echo '<td align="right">'.number_format($r_310_['saldo'],2,".",",").'</td>';
					$registrosp++;
					echo '<td class="centro azul"><input type="checkbox" id="pago_pr'.$registrosp.'" name="pago_pr'.$registrosp.'" value="'.$r_310_["nroprestamo"].'-'.$r_310_['tipoprestamo'] .'" onClick="activar_pago_pr()" ';
					$saldo=$r_310_['saldo'];
					echo '></td>';
					echo '<input type="hidden" id="pagocapital'.$registrosp.'" name="pagocapital'.$registrosp.'" value='.number_format($r_310_['saldo'],2,'.','') .' >';
					echo '<input type="hidden" id="pagoctacapital'.$registrosp.'" name="pagoctacapital'.$registrosp.'" value='.$r_310_['ctacontable'].' >';
					echo '<input type="hidden" id="pagonroreg'.$registrosp.'" name="pagonroreg'.$registrosp.'" value='.$r_310_['registro'].' >';
					echo '<td class="centro azul"><input type="textbox" maxlength="12" size="12" id="pago_prt'.$registrosp.'" name="pago_prt'.$registrosp.'" value=';
					if ($saldo <= 0) echo '0 disabled=true ';
					else echo number_format($saldo,2,',','.').' disabled=true ';
					echo 'onBlur="revisarmonto_prestamos('.$registrosp.')" >';
					echo ' </td>';
					echo '</tr>' ;
				}
				echo "<input type = 'hidden' value ='".$registrosp."' name='registrosp' id='registrosp'>";
//				echo '</table>';
//				echo '</fieldset>';
			}
//				echo '</td></tr>';
				echo '<td align="right" colspan="2"> Monto a Pagar </td><td class="centro azul">';
				echo '<input align="right" name="totalpago" type="text" id="totalprestamos" size="12" maxlength="12" readonly="readonly" value ="0.00"></td>';
				echo '</td></tr>';
				echo '</table>';
				echo '</fieldset>';
				echo '</div>';
		}

// ------------------- fin si tiene prestamos y quiere abonar o pagar
*/
		echo '<div id="pago" style="color:red; background:orange;padding:20px;position:fixed;top:'.$topepago.'%;left:60%; font-size: 14px">';
		// style=\"align="right" background-color="orange" height="70%" left="25%" overflow="hidden" position="absolute" top="20%" width="70%">';
		echo '<fieldset><legend>Detalle forma de Pago </legend>';
		echo '<table>';
		echo '<tr>';
		echo '<td >Depositado</td><td>'; // colspan="1"
		$sqlbanco="select * from ".$_SESSION['bdd']."_sgcaf843 where recibirpago=1";
		$resultado=mysql_query($sqlbanco);
		echo '<select name="elbanco" size="1">';
		while ($fila2 = mysql_fetch_assoc($resultado)) {
			echo '<option value="'.$fila2['cod_banco'].'">'.$fila2['cue_banco'].' - '.$fila2['nombre_ban'].' - '.$fila2['nro_cta_ba'].'</option>'; }
		echo '</select> *'; 
		echo '</td>';
		echo '</tr>';
		echo '<tr>';

		echo '<td>Forma</td><td>';	// colspan="2"
		$sqlbanco="select nombre from ".$_SESSION['bdd']."_sgcaf000 where tipo='FormaPago'";
		$resultado=mysql_query($sqlbanco);
		echo '<select name="laforma" size="1">';
		while ($fila2 = mysql_fetch_assoc($resultado)) {
			echo '<option value="'.$fila2['nombre'].'">'.$fila2['nombre'].'</option>'; }
		echo '</select> *'; 
		echo '</td></tr>';

		echo '<tr>';
		echo '<td>Número de Voucher</td><td>';	// colspan="1"
		echo '<input name="voucher" type="text" id="voucher" size="12" maxlength="12" value =" "></td>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td >Monto a Pagar</td><td>';	// colspan="2"
		echo '<input name="montoprestamo" type="text" id="montoprestamo" size="12" maxlength="12" readonly="readonly" value ="'.number_format($montoprestamo,2,'.','').'"></td>';
		echo '</td></tr>';
		echo '<tr><td align="left" >Fecha Deposito</td><td>';
?>
		<script type="text/javascript">
		setActiveStyleSheet(document.getElementById("defaultTheme"), "green");
		</script>
		<input type="text" name="date3" id="sel3" size="12" readonly><input type="reset" value=" ... " onClick="return showCalendar('sel3', '%d/%m/%Y');">
<?php
		echo '</td>';
		echo '</tr>';
		echo '<tr><td align="center" colspan="2">';
		echo "<input id='continuar' name='continuar' type = 'submit' value = 'Generar recibo' disabled=true>"; 
		echo '</td></tr>';
		echo '</table>';
		echo '</fieldset>';
		echo '</div>';

		pantalla_recibo($result,$cedula);
		$result = mysql_query($sql) or die ('Error 200-1 <br>'.$sql.'<br>'.mysql_error());
/*
	//------------------------- ahorros ----------------------
		echo '<fieldset><legend>Información Sobre Ahorros</legend>';
		$fila = mysql_fetch_assoc($result);
		$elstatus=$_SESSION['elstatus'];
		$ultpag_aho=$fila['ultap_prof'];
		$cedula=$fila['ced_prof'];
		if (!$ultpag_aho)
			$ultpag_aho=$fila['ing_prof'];
		echo "<table class='basica 100 hover' width='550'><tr>";
		echo '<th  width="100" colspan="1">Descripcion</th><th  width="70">Corresponde a</th><th width="50">Pagar</th><th width="80">Monto</th></tr>';
		$pagoahorro=0;
		$registroah=0;
		if (abs($fila['pendiente']) > 0)
		{
			$pagoahorro+=abs($fila['pendiente']);
			echo "<tr><td>Saldo Pendiente </td><td>$ultpag_aho</td>";
			$registroah++;
			echo '<td class="centro azul"><input type="checkbox" id="cancelarah'.$registroah.'" name="cancelarah'.$registroah.'" value='.$pagoahorro.' onClick="activarah()" >'; // disabled=false checked 
	
//			echo '<input type="hidden" id="cancelarahorros'.$registros.'" name="cancelarahorros'.$registroah.'" value='.$pagoahorro.' >';
			echo '<input type="hidden" maxlength="12" size="12" id="cancelaraho'.$registroah.'" name="cancelaraho'.$registroah.'" value='.$pagoahorro.'>';
			echo '<input type="hidden" maxlength="12" size="12" id="cancelaraht'.$registroah.'" name="cancelaraht'.$registroah.'" value='.$pagoahorro.'>';
			echo '<input type="hidden" id="cancelarahtxt'.$registros.'" name="cancelarahtxt'.$registroah.'" value=Pend.'.$ultpag_aho.' >';
			// pidiendo monto ahorro pendiente
			echo '<td class="centro azul"><input type="textbox" maxlength="12" size="12" id="cancelaraht'.$registroah.'" name="cancelaraht'.$registros.'" value=';
			if ($pagoahorro <= 0) echo '0' ; //  disabled=true ';
				else echo number_format($pagoahorro,2,".",',').' disabled=true ';
			echo ' onBlur="revisarmontoahorro('.$registroah.')" >';
			echo '</td></tr>';
		}
		$anio="select now() as ano";
		$rs=mysql_query($anio);
		$reg=mysql_fetch_assoc($rs);
		$hoy=$reg['ano'];
		$anio="select month(now()) as ano";
		$rs=mysql_query($anio);
		$reg=mysql_fetch_assoc($rs);
		$mes=$reg['ano'];
		$sumar=0;
		if ($mes>=11) $sumar++;
		$anio="select year(now()) as ano";
		$rs=mysql_query($anio);
		$reg=mysql_fetch_assoc($rs);
		$actual=($reg['ano']+$sumar).'-12-31';
		$meses="select TIMESTAMPDIFF(MONTH,'".$ultpag_aho."','".$actual."') as meses";
		$rs=mysql_query($meses);
		$reg=mysql_fetch_assoc($rs);
		$meses=($reg['meses']);
		$messig=$ultpag_aho;
//		$registroah=1;
		for ($i=0;$i<$meses;$i++)
		{
			$sql="select date_add('$messig',INTERVAL 31 DAY) as nfecha";
			$rsql=mysql_query($sql);
			$asql=mysql_fetch_assoc($rsql);
			$busqueda=substr($asql['nfecha'],0,8).'01';
			$messig=$busqueda;
			$anio="SELECT * FROM ".$_SESSION['bdd']."_sicafsue WHERE '".$busqueda."'>=ini_sue and '$busqueda' <= fin_sue ";
			$rs=mysql_query($anio);
			$reg=mysql_fetch_assoc($rs);
			$monto=($reg['mon_sue']*($fila['aport_prof']/100));
			echo "<tr><td>Cuota Ordinaria </td><td>$busqueda</td>";
			$registroah++;
//			echo $registroah.'<br>';
			echo '<td class="centro azul"><input type="checkbox" id="cancelarah'.$registroah.'" name="cancelarah'.$registroah.'" value="'.$busqueda.'" ';
			echo 'onClick="activarah()" >';
			echo '<input type="hidden" id="cancelarahtxt'.$registroah.'" name="cancelarahtxt'.$registroah.'" value='.$busqueda.' >';
			// pidiendo monto ahorro ordinario
			echo '<td class="centro azul"><input type="textbox" maxlength="12" size="12" id="cancelaraht'.$registroah.'" name="cancelaraht'.$registroah.'" value=';
			if ($monto <= 0) echo '0 disabled=true ';
				else echo number_format($monto,2,".",',').' disabled=true ';
			echo 'onBlur="revisarmontoahorro('.$registroah.')" >';
			echo '</td></tr>';
			echo '<input type="hidden" maxlength="12" size="12" id="cancelaraho'.$registroah.'" name="cancelaraho'.$registroah.'" value='.$monto.'>';
		}
		$montoahorros=$pagoahorro;
		echo '<td align="right" colspan="2"> Monto por Ahorros</td><td class="centro azul">';
		echo '<input align="right" name="montoahorros" type="text" id="montoahorros" size="12" maxlength="12" readonly="readonly" value ="'.number_format($montoahorros,2,'.','').'"></td>';
		echo '</td></tr>';
		echo '</table>';
		echo '</fieldset>';

		//------------------------- fin ahorros ----------------------
		//------------------------- prestamos ------------------------
		echo '<fieldset><legend>Información Sobre Prestamos Actuales </legend>';
		$sqlprestamos="";
		$estacedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,3);
		$sql = "SELECT * FROM ".$_SESSION['bdd']."_sgcaf310, ".$_SESSION['bdd']."_sgcaf360 WHERE (cedsoc_sdp = '$estacedula' and stapre_sdp='A' and (! renovado)) and codpre_sdp=cod_pres ORDER BY codpre_sdp";
//		echo $sql;
		$rs = mysql_query($sql);
		$i2=0;
			$registros=0;
		while($r_310=mysql_fetch_assoc($rs)) 
		{
			$cedula	= $r_310['cedsoc_sdp'];
			$nropre	= $r_310['nropre_sdp'];
			$tipo	= $r_310['codpre_sdp'];
			$nombreprestamo = $r_310['descr_pres'];
			$ctacapital=trim($r_310['cuent_pres']).'-'.substr($r_310[codsoc_sdp],1,4);
			$ctainteres= $r_310['otro_int'];
		
			$sqlpro= "SELECT * FROM ".$_SESSION['bdd']."_proyecciones where nroprestamo = '$nropre' and tipoprestamo='$tipo' and cedula='$cedula' order by nrocuota";
//			echo $sqlpro;
			$apro=mysql_query($sqlpro);
			if (mysql_num_rows($apro) == 0) // no esta la proyeccion la creo
			{
				crear_proyeccion($nropre, $tipo, $r_310['monpre_sdp'], $r_310['f_1cuo_sdp'] , $r_310['interes_sd'], $r_310['nrocuotas'], $r_310['int_dif'], $cedula, $ctacapital, $ctainteres, $nombreprestamo);
			};
			$apro=mysql_query($sqlpro);
			$r_310=mysql_fetch_assoc($apro);
			$lacuenta=$r_310['ctacontable'];
			if ($r_310['chequeadocontabilidad'] =='N')
			{
				$saldocontable=buscar_saldo_f810($lacuenta);
				echo 'saldo contable '.$saldocontable .' de la cuenta '.$lacuenta;
				$sqlpro2="update ".$_SESSION['bdd']."_proyecciones set pagado = 'S' where nroprestamo = '$nropre' and tipoprestamo='$tipo' and cedula='$cedula' and  saldo >= $saldocontable";
				mysql_query($sqlpro2);
//				echo $sqlpro2;
				$sqlpro2="update ".$_SESSION['bdd']."_proyecciones set chequeadocontabilidad = 'S' where nroprestamo = '$nropre' and tipoprestamo='$tipo' and cedula='$cedula'";
				mysql_query($sqlpro2);
//				echo $sqlpro2;
				
			}

			$primeravez = true;
			$apro=mysql_query($sqlpro);
			while($r_310=mysql_fetch_assoc($apro)) 
			{
				if ($primeravez == true)
				{
					echo '<fieldset><legend>Prestamo '.$r_310['nroprestamo'].' / '.$r_310['nombreprestamo'].'</legend>';
			echo "<table class='basica 100 hover' width='550'><tr>";
			echo '<th colspan="1">Número</th><th>Saldo</th><th>Capital</th><th width="80">Intereses</th><th width="80">Cuota</th><th width="80"># Cuota</th>'; // <th width="100">Habilitar</th><th width="100">Monto a Cancelar</th></tr>';
					$primeravez=false;
				}
				echo "<tr>";
				echo '<td>'.$r_310['nroprestamo'].'</td>';
//				echo '<td>'.$r_310['tipoprestamo'].'</td>';
				$lacuenta=$$r_310['ctacapital']; //  trim($r_310['cuent_pres']).'-'.substr($r_310[codsoc_sdp],1,4);
				echo '<td align="right">'.number_format($r_310['saldo'],2,".",",").'</td>';
				echo '<td align="right">'.number_format($r_310['capital'],2,".",",").'</td>';
				echo '<td align="right">'.number_format($r_310['interes'],2,".",",").'</td>';
				echo '<td align="right">'.number_format($r_310['capital']+$r_310['interes'],2,".",",").'</td>';
				echo '<td align="right">'.$r_310['nrocuota'].'</td>';
//				echo '<td align="right">'.($r_310['ultcan_sdp']).'/'.$r_310['nrocuotas'].'</td>';
				$registros++;
				echo '<td class="centro azul"><input type="checkbox" id="cancelar'.$registros.'" name="cancelar'.$registros.'" value="'.$r_310["nroprestamo"].'-'.$r_310['tipoprestamo'] .'" onClick="activar()" ';
				$saldo=$r_310['capital']+$r_310['interes'];
				if ($r_310['pendiente'] > 0)
					$saldo=$r_310['pendiente'];
				if (($saldo <= 0) or ($r_310['pagado']=='S')) echo ' disabled="true" ';  // checked 
				echo '></td>';

				echo '<input type="hidden" id="cancelarcapital'.$registros.'" name="cancelarcapital'.$registros.'" value='.number_format($r_310['capital'],2,'.','') .' >';
				echo '<input type="hidden" id="cancelarinteres'.$registros.'" name="cancelarinteres'.$registros.'" value='.number_format($r_310['interes'],2,'.','') .' >';
				echo '<input type="hidden" id="ctacapital'.$registros.'" name="ctacapital'.$registros.'" value='.$r_310['ctacontable'].' >';
				echo '<input type="hidden" id="ctainteres'.$registros.'" name="ctainteres'.$registros.'" value='.$r_310['ctainteres'].' >';

				echo '<input type="hidden" id="cancelarnroreg'.$registros.'" name="cancelarnroreg'.$registros.'" value='.$r_310['registro'].' >';
				echo '<input type="hidden" id="cancelarh'.$registros.'" name="cancelarh'.$registros.'" value='.number_format($r_310['capital']+$r_310['interes'],2,'.','') .' >';
				echo '<input type="hidden" id="cancelarho'.$registros.'" name="cancelarho'.$registros.'" value='.number_format($saldo,2,'.','') .' >';
				echo '<td class="centro azul"><input type="textbox" maxlength="12" size="12" id="cancelart'.$registros.'" name="cancelart'.$registros.'" value=';
				if ($saldo <= 0) echo '0 disabled=true ';
				else echo number_format($saldo,2,',','.').' disabled=true ';
//			else echo $saldo .' disabled=true ';
				echo 'onBlur="revisarmonto('.$registros.')" >';
				echo ' </td>';
				echo '</tr>' ;
				}
				echo '</table>';
				echo '</fieldset>';
		}
		echo '<td align="right" colspan="2"> Monto a Pagar </td><td class="centro azul">';
		echo '<input align="right" name="totalprestamos" type="text" id="totalprestamos" size="12" maxlength="12" readonly="readonly" value ="'.number_format($totalprestamos,2,'.','').'"></td>';
		echo '</td></tr>';
		echo '</table>';
		echo '</fieldset>';

		echo "<input type = 'hidden' value ='".$registroah."' name='registroah' id='registroah'>";
		echo "<input type = 'hidden' value ='".$registros."' name='registros' id='registros'>";
		echo "<input type = 'hidden' value ='".$micedula."' name='micedula' id='micedula'>";
		echo "<input type = 'hidden' value ='".$cedula."' name='cedula' id='cedula'>";
		echo "<input type = 'hidden' value ='".$marcados."' name='marcados' id='marcados'>";
		echo '<tr>';
		//------------------------- fin prestamos ------------------------
*/

		echo '<fieldset><legend>Detalle Inscripcion Ahorro</legend>';
		echo "<table class='basica 100 hover' width='550'><tr>";
			echo '<th colspan="1">Serie</th><th>Monto Inscripcion</th><th>Prestamo a Optar</th>'; // <th width="100">Habilitar</th><th width="100">Monto a Cancelar</th></tr>';
//		echo '<td >Seleccione su Opcion</td><td>'; // colspan="1"

		$sqlbanco="select * from ".$_SESSION['bdd']."_sgcafapc where now() <= vhasta ";
		$resultado=mysql_query($sqlbanco);
		$registros=0;
		while ($fila2 = mysql_fetch_assoc($resultado)) {
			echo "<tr>";
			echo '<td align="center">'.$fila2['serie'].'</td>';
			echo '<td align="right">'.number_format($fila2['montoins'],2,',','.').'</td>';
			echo '<td  align="right">'.number_format($fila2['prestamo'],2,',','.').'</td>';
			$registros++;
			echo '<td class="centro azul"><input type="checkbox" id="inscribir'.$registros.'" name="inscribir'.$registros.'" value="'.$fila2["serie"].'" onClick="activar_inscripcion()" ';
			echo '></td>';
			echo '<input type="hidden" id="inscribiri'.$registros.'" name="inscribiri'.$registros.'" value="'.$fila2['montoins'] .'" >';
			echo '<input type="hidden" id="inscribirf'.$registros.'" name="inscribirf'.$registros.'" value="'.convertir_fechadmy($fila2['vdesde']). ' al '.convertir_fechadmy($fila2['vhasta']) . ' para optar a Bs.'.number_format($fila2['prestamo'],2,'.','').'">';
			echo '</tr>';
		}
		// que aparezca / desaparezca
		// calcular nuevo codigo en caso de aceptar
		echo "<input type = 'hidden' value ='".$registros."' name='registros' id='registros'>";
		echo '<tr>';
		echo '<td >Concepto</td><td colspan="3">';	// colspan="1"
		echo '<input name="concepto" type="text" id="concepto" size="120" maxlength="200" value =" " readonly="readonly"></td>';
		echo '</td>';
		
		
//		echo $sqlbanco;
/*
		$resultado=mysql_query($sqlbanco);
		echo '<select name="elbanco" size="1">';
		while ($fila2 = mysql_fetch_assoc($resultado)) {
			echo '<option value="'.$fila2['serie'].'">'.$fila2['serie'].' - '.number_format($fila2['montoins'],2,',','.').' - '.number_format($fila2['prestamo'],2,',','.').'</option>'; }
		echo '</select> *'; 
*/
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td colspan="2" >Codigo (Temporal)';
		echo '<input type="text" id="cod_prog" name="cod_prog" readonly="readonly" value="" ></td>';

		echo '<td align="right" colspan="1"> Monto a Pagar ';
		echo '<input align="right" name="totalprestamos" type="text" id="totalprestamos" size="12" maxlength="12" readonly="readonly" value ="'.number_format($totalprestamos,2,'.','').'"></td>';
		echo '</table>';
		echo '</fieldset>';

		echo '</div>';
		echo '</form>';
	}
}	// fin de ($accion == 'Buscar') 
		
if (!$accion) {
	echo "<form action='insc_ahorropro.php?accion=Buscar' name='form1' method='post'>";
    echo '  C&eacute;dula ';
	echo '<input name="cedula" type="text" id="cedula" value=""  size="10" maxlength="10" />';
	echo "<input type = 'submit' value = 'Buscar'>";
	$_SESSION['numeroarenovar']='';
	$_SESSION['cedulasesion']=''; 
	echo '</form>';
}	// fin de (!$accion) 
if ($accion == 'Ver') {
	echo "<div align='center' id='div1'>";
	$mostrarregresar=1;
	$cedula=$_GET['cedula'];
	$nropre=$_GET['nropre'];
	mostrar_prestamo($cedula,$nropre);
	echo "</div>";
}	// fin de ($accion == 'Ver')

if ($accion == "GenerarRecibos")  {	// selecciono el tipo de prestamo
	$mostrarregresar=0;
	extract($_POST);
	echo '<div id="div1">';
	$sql_200="select cod_prof, ape_prof, nombr_prof from ".$_SESSION['bdd']."_sgcaf200  where ced_prof='$cedula'";
//	echo $sql_200;
	$a_200=mysql_query($sql_200);
	$r_200=mysql_fetch_assoc($a_200);
	$codigo=$r_200['cod_prof'];
	$nombre=$r_200['ape_prof'] . ' '. $r_200['nombr_prof'];
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
//	echo $elnumero;
	$elnumero = $codigo; // nuevo_comprobante();
	$elnumero=substr($elnumero,5*-1);	// simular la funcion rigth
//	echo $elnumero;
	$elnumero=ceroizq($elnumero,5);
//	echo $elnumero;
	$albanco=0;
	$deposito=ceroizq(trim($voucher),20);
	$deposito=substr($deposito,10*-1);
	$letraserie='D';
	$referencia="select nro_rec from ".$_SESSION['bdd']."_sgcaf370 where substr(nro_rec,1,1)='$letraserie' order by nro_rec desc limit 1";
	$a_370=mysql_query($referencia);
	$r_370=mysql_fetch_assoc($a_370);
	$referencia=substr($r_370['nro_rec'],1,5)+1;
//	$referencia = $elnumero; // '00000'; // nuevo_comprobante();
	if (mysql_num_rows($a_370) > 0)
		$referencia=substr($r_370['nro_rec'],0,1).ceroizq($referencia,5);
	else 
		$referencia=$letraserie.ceroizq($referencia,5);
//	$elnumero=substr($referencia,5*-1);	// simular la funcion rigth
//	$elnumero=ceroizq($elnumero,5);
	$elasiento = date("ymd").$elnumero;
	$_SESSION['elasiento']=$elasiento;
	$hoy = date("Y-m-d H:i:s" );
	$b = date("Y-m-d" );

	$sql = "INSERT INTO ".$_SESSION['bdd']."_sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$elasiento', '$proceso', '','',0,0,0,0,0,0,0,'')"; 
		if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);

	echo "Generando Registros contables del asiento <strong>";
	echo "<a target=\"_blank\" href='editasi2.php?asiento=$elasiento'>$elasiento </a>";
	echo "</strong> para el recibo de ingreso numero <strong>".$$referencia."</strong><br>";

	$proceso = convertir_fecha($_POST['date3']);
	$sql_370="insert into ".$_SESSION['bdd']."_sgcaf370 (nro_rec, cod_prof, nombre, monto, fecha, ip, proceso, cod_prog) values ('$referencia', '$codigo', '$nombre',0,'$hoy','$ip', '$proceso', '".$_POST['cod_prog']."')";
	$a_370=mysql_query($sql_370) or die(mysql_error());
	$ip = $_SERVER['HTTP_CLIENT_IP'];
	if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
	$des="Socio $codigo ".$_POST['concepto'] . 'Codigo Asociado '.$_POST['cod_prog']. ' ';
	for ($i=0;$i<$registros;$i++)
	{
		$serie='inscribir'.($i+1);
		$serie=$$serie;
		$variable='inscribiri'.($i+1);
		$var2='inscribiri'.($i+1);
		$txt='cancelarahtxt'.($i+1);
/*
		$pendiente=false;
//		echo 'pendiente '.	substr($$txt,0,5);
		if (substr($$txt,0,5) == "Pend.")
		{
			$pendiente=true;
			$_txt=substr($$txt,5,10);
		}
		else $_txt=$$txt;
//		echo '---txt '.$_txt.'<br>';
*/			
		if (!empty($$variable)) 
		{
			$des.=$$variable.' ';
			$des2=$$var2;
			
			$s310="select * from ".$_SESSION['bdd']."_sgcafapc where serie = '$serie'";
			$a310=mysql_query($s310);
			$r310=mysql_fetch_assoc($a310);
			
			// saldo pendiente del prestamo
			$cuenta1=trim($r310['cinscrip']); // .'-'.substr($codigo,1,4);
//			$debe=buscar_saldo_f810($cuenta1);
			$cargar='-'; 
			$anterior='cancelaraht'.($i+1);
			$anterior=abs($$anterior);
			$debe=$$var2; // 'cancelart'.($i+1);
			$debe=abs($debe);
			$np=$$variable;
			$np2=$$txt;
			if (substr($np2,0,1)=='2')
				$np2=convertir_fechadmy($np2);
			
			agregar_f820($elasiento, $proceso, $cargar, $cuenta1, 'Inscripcion Ahorro Programado'.$np2, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
			$albanco+=$debe;
			$sql_375="insert into ".$_SESSION['bdd']."_sgcaf375 (nro_rec, nro_che, fecha, banco, monto, forma, ip, nro_pre, ahorrovol) VALUES ('$referencia','$deposito','$hoy','$elbanco',$debe, '$laforma','$ip','$np', 'IAP')";
//			echo $sql_375.'<br>';
			$resultado=mysql_query($sql_375);
		}
	}
	$cod_prog=$_POST['cod_prog'];
//	$comando="INSERT INTO ".$_SESSION['bdd']."_sgcafap1 (cod_prof, cod_prog, pres_prog, des_prog, hab_prog, fec_prog, fec_inscr, cuota) VALUES ('$codigo', '$cod_prog', 0, 0, 0, '', '$np2', ";
	$comando="INSERT INTO ".$_SESSION['bdd']."_sgcafap1 (cod_prof, cod_prog, pres_prog, des_prog, hab_prog, fec_prog, fec_inscr) VALUES ('$codigo', '$cod_prog', 0, 0, 0, '', '$np2')";
	$res=mysql_query($comando) or die('Fallo '.$comando);

	$comando="UPDATE ".$_SESSION['bdd']."_sgcafapc set ultimo = ultimo + 1 where serie = '$serie'";
	$res=mysql_query($comando) or die('Fallo '.$comando);

	$des.=' segun '.$laforma.' Nro. '.$deposito;
	$debe = $albanco; //  - ($intereses_diferidos + $d_obligatorias); 
	$sql="update ".$_SESSION['bdd']."_sgcaf370 set descri1 = '$des', monto ='$albanco' where nro_rec='$referencia'";
	$a=mysql_query($sql);
	$sql="update ".$_SESSION['bdd']."_sgcaf830 set enc_desco = '$des', enc_explic='$des' where enc_clave ='$elasiento'";
	$a=mysql_query($sql);
	$cargar='+';
	$sqlbanco="select * from ".$_SESSION['bdd']."_sgcaf843 where recibirpago=1 and cod_banco='$elbanco'";
	$resultado=mysql_query($sqlbanco);
	$fila2 = mysql_fetch_assoc($resultado);
	$cuenta1=$fila2['cue_banco'];
	$concepto=$_POST['concepto']; // "Ahorros / Abono Prest. Socio $codigo";
	agregar_f820($elasiento, $proceso, $cargar, $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$deposito,'','S',0); 

	echo '<input type="submit" name="Submit" value="Imprimir Recibo" onClick="abrirVentana(';
	echo "'";
	echo $referencia;
//	echo "&c1=".$c1."&cu=".$cu."&ia=".$ia."&ac=".$ac."&ta=".$ta."&tc=".$tc."&i1=".$i1;
//	echo "&otro=".$better_token.$better_token;
//	echo "&aa=$capital&bb=$cuotas&ia=$ia[$cuotas]&cc=$cu[1]&dd=$interes&socio=";
//	echo trim($r_200['ape_prof']). ', '.trim($r_200['nombr_prof']);
//	echo "&prestamo=".trim($r_310['descr_pres']);
	echo "&comprobante=";
	echo $elasiento;
	echo "'";
	echo ');">  ';
	echo '</div>';
}	// fin de ($accion == "GenerarRecibos")

if ($mostrarregresar==1) { // ($accion == "Buscar") or ($accion == "Ver") or ($accion="GenerarRecibos")) {
	echo '<form enctype="multipart/form-data" name="formdepie" method="post" action="insc_ahorropro.php?accion=Buscar">';
	echo '<input type = "hidden" value ="'.$_SESSION['cedulasesion'].'" name="cedula" id="cedula">';
// 	echo 'la cedula '.$_SESSION['cedulasesion'];
	echo '<div style="clear:both"></div>';
	echo '<p /><div class="noimpri" style="clear:both;text-align:center">';
	echo '<input type="submit" name="boton" value="regresar" tabindex="3">';
	echo '</div>';
	echo '</form>';
}
else 
	include("pie.php");
?>
</body></html>


<?php

function buscar_saldo_f810($cuenta)
{
	$sql_f810="select cue_saldo from ".$_SESSION['bdd']."_sgcaf810 where cue_codigo='$cuenta'";
//	echo $sql_f810;
	$lacuentas=mysql_query($sql_f810); //  or die ("<p />El usuario $usuario no pudo conseguir el saldo contable<br>".mysql_error()."<br>".$sql);
	$lacuenta=mysql_fetch_assoc($lacuentas);
	$saldoinicial=$lacuenta['cue_saldo'];
	
	$sql_f820="select com_monto1, com_monto2 from ".$_SESSION['bdd']."_sgcaf820 where com_cuenta='$cuenta' order by com_fecha";
//	echo $sql_f820;
	$lacuentas=mysql_query($sql_f820); //  or die ("<p />El usuario $usuario no pudo conseguir los movimientos contables<br>".mysql_error()."<br>".$sql);
	while($lascuenta=mysql_fetch_assoc($lacuentas)) {
		$saldoinicial+=$lascuenta['com_monto1'];
//		echo $saldoinicial.'<br>';
		$saldoinicial-=$lascuenta['com_monto2'];
//		echo $saldoinicial.'<br>';
	}
return round($saldoinicial,2);
}
function pantalla_recibo($result,$cedula)
{
	$deci=$_SESSION['deci'];
	$sep_decimal=$_SESSION['sep_decimal'];
	$sep_miles=$_SESSION['sep_miles'];
	$fila = mysql_fetch_assoc($result);
	echo "<input type = 'hidden' value ='".$fila['ced_prof']."' name='cedula'>";
	if ($accion == 'Editar') { $lectura = 'readonly = "readonly"'; $activada="disabled" ; } else {$lectura=''; $activada='';}
	if ($accion == 'Anadir') {
		$elcodigo=nuevo_codigo(); 
		$ingreso=date("d/m/Y", time());
		}
	else  $elcodigo=$fila['cod_prof'];
	$lectura = 'readonly = "readonly"'; $activada="disabled" ; 
//	<form id="form1" name="form1" method="post" action="">
?>
  <fieldset><legend>Información Personal </legend>
  <table width="639" border="1">
    <tr>
		<td colspan="2" width="100" >C&oacute;digo <?php echo '<strong>'.$elcodigo.'</strong>'; ?></td>
 		<td colspan="1" width="130">Cédula <?php echo '<strong>'.$fila['ced_prof'].'</strong>';?></td>
		<td colspan="3" width="127">Socio <?php echo '<strong>'.$fila['ape_prof'].' '.$fila['nombr_prof'] .'</strong>'?></td>
	</tr>
	<tr>
		<td colspan="2" width="127" scope="col">Fecha de Ingreso 
		<strong><?php echo convertir_fechadmy($fila['f_ing_capu']) ?></strong></td>
		<td>Estatus
		<strong><?php echo $fila['statu_prof'] ?></strong></td>
	    <td align="center" colspan="2" class="<?php echo ($disponible<=0)?'rojo':'azul' ?>" >Disponibilidad Neta
		<?php 
			$ahorros=ahorros($fila['ced_prof']);
			$afectan=afectan($fila['ced_prof']);
//			echo 'afecta '.$noafectan. ' ¿- '.$cedula;
			$noafectan=noafectan($fila['ced_prof']);
//			echo 'afecta '.$noafectan;
			$sql="select * from ".$_SESSION['bdd']."_sgcaf200 where ced_prof='".$fila['ced_prof']."'";
			$result=mysql_query($sql);
//			echo $sql;
			$fila = mysql_fetch_assoc($result);
			$fianzas=fianzas($fila['cod_prof']);
			$disponible=disponibilidad($ahorros,$afectan,$noafectan,$fianzas); ?>
			<strong><?php 
	  		if ($disponible<=0)
				{
					$imagen='24-em-cross.png';
					$cuento_mostrar='Disponibilidad Negativa';
					$cuento_interno='disp_neg';
				}
			else {
					$imagen='24-em-check.png';
					$cuento_mostrar='Disponibilidad Positiva';
					$cuento_interno='disp_pos';
			}
			echo '<img src="imagenes/'.$imagen.'" width="22" height="19" alt="'.$cuento_mostrar.'" longdesc="'.$cuento_interno.'" />';
			echo number_format($disponible,$deci,$sep_decimal,$sep_miles); 
			echo '<img src="imagenes/'.$imagen.'" width="22" height="19" alt="'.$cuento_mostrar.'" longdesc="'.$cuento_interno.'" />';
			$_SESSION['disponibilidadprestamo']=1234; // $disponible; 
//			$_SESSION['disponibilidadprestamo']=$disponible; 
			$_SESSION['elstatus']=strtoupper($fila['statu_prof']);
			$hoy=date("Y-m-d", time());
			$pasados=(dias_pasados($fila['f_ing_capu'],$hoy)/30) ;
		   $_SESSION['tiempoactivo']=intval($pasados);
		 ?></strong></td>
	</tr>
</table>
</fieldset> 

<?php
}

function crear_proyeccion($nropre, $tipo, $monto, $lafecha, $interes, $ncuotas, $diferido, $cedula, $ctacapital, $ctainteres, $nombreprestamo)
{
	$_SESSION['monto']=$_elmonto=$c=$monto;
	$_SESSION['cuotas']=$ncuotas;
	if ($lafecha < '2013-07-01') 
		if ($diferido == 1)
			$concepto.='Descontada';
		else $concepto.='Amortizada';
	else $concepto.='Amortizada';
	
	$i2=0;
	$z=cal_int($interes,$ncuotas,$c,12,0,$i2); // calcular aqui luego la descontada
	if ($concepto == 'Amortizada')
		$z=cal_int($interes,$ncuotas,$c,12,0,$i2); // calcular aqui luego la descontada
	else 
		$z=$monto/$ncuotas;
	$original=$z;
	$z= $original; // $monto/$ncuotas;
	$_lacuota=$z;
	$k = 0;	         // k = contador
	$ia = 0;         // ia = interes acumulado
	$cu = 0;     // cu = cuota
	$ac = 0;     // ac = acumulado
	$tc = $z;     // tc = total cuota
	$ta = 0;     // ta = total acumulado
	$c1 = $c;     //  i1 = interes
	for ($m=0;$m<$ncuotas;$m++) 
	{
		$k = $k + 1;
		$i1 = $c1 * $i2;
		$cu = $z - $i1;
		$c1 = $c1 - $cu;
		$ia = $ia + $i1;
		$ac = $ac + $cu;
		$ta = $ta + $z;
		$sqlpro="INSERT INTO ".$_SESSION['bdd']."_proyecciones (nroprestamo,  tipoprestamo, nrocuota, capital, interes, saldo, ultimopago, pagado, pendiente, ultimomonto, ctacontable, ctainteres,  fecha, cedula, nombreprestamo, interesmora, chequeadocontabilidad) VALUES ('$nropre',  '$tipo', '$k', '$cu', '$i1', '$c1', '', 'N', 0, 0, '$ctacapital', '$ctainteres', '$lafecha', '$cedula','$nombreprestamo', 0, 'N')";
		$rpro=mysql_query($sqlpro);
//		echo $sqlpro.'<br>';

		$sql="select date_add('$lafecha',INTERVAL 30 DAY) as fecha";
		$rsql=mysql_query($sql);
		$asql=mysql_fetch_assoc($rsql);
		$lafecha=($asql['fecha']);
	}
}


/*
V-11784067
SELECT * FROM `cacpcel_sgcaf310` WHERE cedsoc_sdp='V-11.784.067';

INSERT INTO `cacpcel_sgcaf310` (`codsoc_sdp`, `cedsoc_sdp`, `nropre_sdp`, `codpre_sdp`, `f_soli_sdp`, `f_1cuo_sdp`, `ultcan_sdp`, `monpre_sdp`, `monpag_sdp`, `monfia_sdp`, `nrofia_sdp`, `stapre_sdp`, `tipo_fianz`, `cuota`, `nrocuotas`, `monint`, `interes_sd`, `cuota_ucla`, `pag_ucla`, `renovado`, `renova_por`, `paga_hasta`, `ultcan_pro`, `monpag_pro`, `stapre_pro`, `monint_pro`, `aplicado`, `f_pago`, `vige_hasta`, `vige_desde`, `hipo_hasta`, `protocolo`, `seguro`, `c1`, `c2`, `c3`, `c4`, `c5`, `c6`, `c7`, `c8`, `c9`, `c10`, `c11`, `c12`, `c13`, `c14`, `netcheque`, `ctaprestamo`, `ctaodeduc`, `ctaindebidos`, `otroreintegro`, `nro_acta`, `fecha_acta`, `ip`, `inicial`, `intereses`, `quien`, `registro`, `cuota_real`, `pag_real`, `observa`, `sal_pre`, `cotr_ded`, `otr_ded`, `motr_ded`, `ind_des`, `deb_ban`, `cdes_pre`, `des_pre`, `mdes_pre`, `cint_dev`, `mint_dev`, `cotr_rei`, `otr_rei`, `motr_rei`, `nro_che`, `ban_che`, `fec_che`, `atributo`, `fec_acta`, `ult_ret`) VALUES
('01593', 'V-11.784.067', '00001593', '103', '2014-10-15', '2014-11-15', 4, '80000.00', '12042.66', '0.00', '0', 'A', ' ', '3765.88', 24, '0.00', '12.00', '3765.88', '0.00', 0, '        ', '0000-00-00', 4, '15063.52', ' ', '0.000000', '                    ', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', '      ', '0.000000', '0.000000', '0.000000', '0.000000', '0.000000', '', '', '', '', '0000-00-00', '', '', '', '', '0.0000000', '', '', '', '', '0371 1', '2014-10-15', '', '0.00', '0.00', '', 292, '3765.88', '0.00', 'incluye el 12% de los intereses                             ', '0.00', 'canc total prestamo      ', 'especial  ', '9364.57', '0.00', '0.00', '                         ', '          ', '0.00', '                         ', '0.00', '                         ', '          ', '0.00', '13002643', '0008', '2014-10-15', 'N', '2014-10-15', '2015-03-15');

CREATE TABLE `cacpcel_proyecciones` (
  `registro` bigint(20) NOT NULL auto_increment,
  `nroprestamo` varchar(10) NOT NULL,
  `tipoprestamo` varchar(3) NOT NULL,
  `nrocuota` int(11) NOT NULL,
  `capital` decimal(12,2) NOT NULL,
  `interes` decimal(12,2) NOT NULL,
  `saldo` decimal(12,2) NOT NULL,
  `ultimopago` date NOT NULL,
  `pagado` varchar(1) NOT NULL,
  `pendiente` decimal(12,2) NOT NULL,
  `ultimomonto` decimal(12,2) NOT NULL,
  `ctacontable` varchar(30) NOT NULL,
  `ctainteres` varchar(30) NOT NULL,
  `fecha` date NOT NULL,
  PRIMARY KEY  (`registro`),
  KEY `nroprestamo` (`nroprestamo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
ALTER TABLE `cacpcel_proyecciones` ADD `cedula` VARCHAR( 20 ) NOT NULL ;
ALTER TABLE `cacpcel_proyecciones` ADD `nombreprestamo` VARCHAR( 40 ) NOT NULL ,
ADD `interesmora` DECIMAL( 12, 2 ) NOT NULL ;
ALTER TABLE `cacpcel_proyecciones` ADD `chequeadocontabilidad` VARCHAR( 1 ) NOT NULL ;

pendiente para borrar
	for ($i=0;$i<$registros;$i++)
	{
		$variable='cancelar'.($i+1);
//		echo $variable; 
//			echo '<br><br>'.' variable '.$variable.' contenido = '.$$variable;
//			echo $_POST['cancelar1'];
//			echo '<br><br>';
		if (!empty($$variable)) 
		{
			$des.=$$variable.' ';
			
			$s310="select cuent_pres, codsoc_sdp, descr_pres, cuent_int, monpre_sdp, cuota_ucla, registro from ".$_SESSION['bdd']."_sgcaf310, ".$_SESSION['bdd']."_sgcaf360 where (cedsoc_sdp='$micedula') and (nropre_sdp = '".$$variable."') and (stapre_sdp='A' and ! renovado) and (codpre_sdp=cod_pres)";
//				echo $s310;
//             echo $s310; 
			$a310=mysql_query($s310);
			$r310=mysql_fetch_assoc($a310);
			// saldo pendiente del prestamo
			$cuenta1=trim($r310['cuent_pres']).'-'.substr($r310[codsoc_sdp],1,4);
//			$debe=buscar_saldo_f810($cuenta1);
			$cargar='-'; 
			$anterior='cancelarh'.($i+1);
			$anterior=abs($$anterior);
			$debe='cancelart'.($i+1);
			$debe=abs($$debe);
			$np=$$variable;
			agregar_f820($elasiento, $proceso, $cargar, $cuenta1, 'Abono '.$r310['descr_pres'], $debe, $haber, 0,$ip,0,$deposito,'','S',0); 
			$albanco+=$debe;
			$sql_375="insert into ".$_SESSION['bdd']."_sgcaf375 (nro_rec, nro_che, fecha, banco, monto, forma, ip, nro_pre) VALUES ('$referencia','$deposito','$hoy','$elbanco',$debe, '$laforma','$ip','$np')";
//			echo $sql_375.'<br>';
			$resultado=mysql_query($sql_375);
			// actualizar el 310
//			$registro=$r310['registro'];
			$saldo=$r310['monpre_sdp']-($anterior - $debe);
//			echo $r310['monpre_sdp'].' - '.$saldo .' - '.$anterior.' - '. $debe.'<br>';
			$cuotaspagadas=$debe / $r310['cuota_ucla'];
			$sql310="update ".$_SESSION['bdd']."_sgcaf310 set monpag_sdp = monpag_sdp + '$debe' ";
			if ($saldo >= $r310['monpre_sdp'])
				$sql310.=", stapre_sdp = 'C', renovado = 1 ";
			else $sql310.=", ultcan_sdp = ultcan_sdp + '$cuotaspagadas' ";
			$sql310.=" where nropre_sdp = '$registro'";
			echo $sql310;
			$act310=mysql_query($sql310);
			// actualizar el 320
			actualizar_fiador($r310['codsoc_sdp'],$debe,$np);
		}
	}

if ($accion == "GenerarRecibos")  {	// selecciono el tipo de prestamo
	$mostrarregresar=0;
	extract($_POST);
	echo '<div id="div1">';
	$sql_200="select codsoc_sdp as cod_prof, ape_prof, nombr_prof from ".$_SESSION['bdd']."_sgcaf310, ".$_SESSION['bdd']."_sgcaf200  where cedsoc_sdp='$cedula' and cod_prof=codsoc_sdp";
//	echo $sql_200;
	$a_200=mysql_query($sql_200);
	$r_200=mysql_fetch_assoc($a_200);
	$codigo=$r_200['cod_prof'];
	$nombre=$r_200['ape_prof'] . ' '. $r_200['nombr_prof'];
	$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
//	echo $elnumero;
	$elnumero = $codigo; // nuevo_comprobante();
	$elnumero=substr($elnumero,5*-1);	// simular la funcion rigth
//	echo $elnumero;
	$elnumero=ceroizq($elnumero,5);
//	echo $elnumero;
	$albanco=0;
	$deposito=ceroizq(trim($voucher),20);
	$deposito=substr($deposito,10*-1);
	$letraserie='A';
	$referencia="select nro_rec from ".$_SESSION['bdd']."_sgcaf370 where substr(nro_rec,1,1)='$letraserie' order by nro_rec desc limit 1";
	$a_370=mysql_query($referencia);
	$r_370=mysql_fetch_assoc($a_370);
	$referencia=substr($r_370['nro_rec'],1,5)+1;
//	$referencia = $elnumero; // '00000'; // nuevo_comprobante();
	$referencia=substr($r_370['nro_rec'],0,1).ceroizq($referencia,5);
//	$elnumero=substr($referencia,5*-1);	// simular la funcion rigth
//	$elnumero=ceroizq($elnumero,5);
	$elasiento = date("ymd").$elnumero;
	$_SESSION['elasiento']=$elasiento;
	$hoy = date("Y-m-d H:i:s" );
	$b = date("Y-m-d" );
	$proceso = convertir_fecha($_POST['date3']);
	$sql_370="insert into ".$_SESSION['bdd']."_sgcaf370 (nro_rec, cod_prof, nombre, monto, fecha, ip, proceso) values ('$referencia', '$codigo', '$nombre',0,'$hoy','$ip', '$proceso')";
	$a_370=mysql_query($sql_370) or die(mysql_error());
	$ip = $_SERVER['HTTP_CLIENT_IP'];
	if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
	$des="Socio $codigo ";
	if ($_POST['totalprestamos'] > 0)
		$des.=' P/R Abono Prest.';
	
	$sql = "INSERT INTO ".$_SESSION['bdd']."_sgcaf830 (enc_clave, enc_fecha, enc_desco, enc_desc1, enc_debe, enc_haber, enc_item, enc_dif, enc_igual, enc_refer, enc_sw, enc_explic) VALUES ('$elasiento', '$proceso', '','',0,0,0,0,0,0,0,'')"; 
		if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para añadir Asientos.<br>".$sql);

	echo "Generando Registros contables del asiento <strong>";
	echo "<a target=\"_blank\" href='editasi2.php?asiento=$elasiento'>$elasiento </a>";
	echo "</strong> para el recibo de ingreso numero <strong>".$$referencia."</strong><br>";
	$mah=$_POST['montoahorros'];

	//---- ahorro voluntarios ----------------
	if ($cancelarahvol1 > 0)
	{
			$s310="insert into ".$_SESSION['bdd']."_fhis200 (cod_prof, hab_prof, hab_ucla, fecha, total_ahor, descri, pago, ip) values ('$codigo', '".$cancelarahvol1."', 0, '$proceso', 0, 'Ahorros Voluntario ".$$var2." s/d $deposito ', '$proceso', '$ip')";
			
//             echo $s310; 
			$a310=mysql_query($s310);
			
			$s310="update ".$_SESSION['bdd']."_sgcaf200 set hab_f_extr = hab_f_extr + ".$cancelarahvol1.", ultap_extr = '".$b."' ";
			$original='cancelarahvol1'.($i+1);
			$s310.=" where cod_prof = '$codigo'";
//			echo $s310;
			$a310=mysql_query($s310);
			
			$s310="select * from ".$_SESSION['bdd']."_sgcaf000 where tipo = 'RetVol'";
			$a310=mysql_query($s310);
			$r310=mysql_fetch_assoc($a310);
			
			// saldo pendiente del prestamo
			$cuenta1=trim($r310['nombre']).'-'.substr($codigo,1,4);
//			$debe=buscar_saldo_f810($cuenta1);
			$cargar='-'; 
			$anterior=abs($cancelarahvol1);
			$debe=$anterior;
			$debe=abs($debe);
			
			// chequeo f810
			$s810="select cue_codigo from ".$_SESSION['bdd']."_sgcaf810 where cue_codigo ='$cuenta1'";
			$a810=mysql_query($s810);
			if (mysql_num_rows($a810) < 1)
			{
				$s810="insert into ".$_SESSION['bdd']."_sgcaf810 (cue_codigo, cue_nombre, cue_nivel, cue_saldo) values ('$cuenta1', '$nombre', '7', '0.00')";
				$a810=mysql_query($s810);
			}
			
			agregar_f820($elasiento, $proceso, $cargar, $cuenta1, 'Ahorro Voluntario ', $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
			$albanco+=$debe;
			$sql_375="insert into ".$_SESSION['bdd']."_sgcaf375 (nro_rec, nro_che, fecha, banco, monto, forma, ip, nro_pre, ahorrovol) VALUES ('$referencia','$deposito','$hoy','$elbanco',$debe, '$laforma','$ip','', '1')";
//			echo $sql_375.'<br>';
			$resultado=mysql_query($sql_375);
	}
// 	phpinfo();
	//---- prestamos ----------------
	for ($i=0;$i<$registros;$i++)
	{
		$variable='cancelar'.($i+1);
		$var2='cancelar'.($i+1);
		$txt='cancelarh'.($i+1);
		$pendiente=false;
//		echo 'pendiente '.	substr($$txt,0,5);
		if (substr($$txt,0,5) == "Pend.")
		{
			$pendiente=true;
			$_txt=substr($$txt,5,10);
		}
		else $_txt=$$txt;
// 		echo '---txt '.$_txt.'<br>';
			
		if (!empty($$variable)) 
		{
			$des.=$$variable.' ';
			$des2=$$var2; 
//			echo $des.'<br>';
			
			$original='cancelarho'.($i+1);
			$original=abs($$original); 
			$escrito='cancelart'.($i+1);
			$escrito = abs($$escrito);
			$capital='cancelarcapital'.($i+1);
			$interes='cancelarinteres'.($i+1);
			$capital=$$capital;
			$interes=$$interes;
			if (($escrito ==  $original) and ($escrito == ($capital + $interes)))
			{
				// queda igual
			}
			else
			{
				// regla de 3 para ver cuanto va a capital e interes
				$capital=(($escrito * 100) / ($capital + $interes)) ;
				$capital/=100;
				$nuevovalor=$capital*$interes;
				$capital = ($escrito -$nuevovalor);
				$interes= $nuevovalor;
			}

			// saldo pendiente del prestamo
			$cuenta1='ctacapital'.($i+1);
			$cuenta1=trim($$cuenta1); // .'-'.substr($codigo,1,4);
			$cuentacapital=$cuenta1;
//			$debe=buscar_saldo_f810($cuenta1);
			$cargar='-'; 
			$anterior='cancelarh'.($i+1);
			$anterior=abs($$anterior);
			$debe=$anterior; // 'cancelart'.($i+1);
			$debe=abs($debe);
			$np=$$variable;

			$debe=abs($capital);
			$registro='cancelarnroreg'.($i+1);
			$registro=abs($$registro);
			$r310="SELECT * FROM ".$_SESSION['bdd']."_proyecciones where registro = $registro";
//			echo $r310;
			$r310=mysql_query($r310);
			$r310=mysql_fetch_assoc($r310);
			$nombreprestamo=$r310['nombreprestamo'];
			$lacuota = $r310['nrocuota'];
			$nroprestamo=$r310['nroprestamo'];
			$tipoprestamo=$r310['tipoprestamo'];
			$ultimacuotapagada=$lacuota-1;
			$r310="UPDATE ".$_SESSION['bdd']."_proyecciones set ultimopago='$hoy', ultimomonto=ultimomonto+$escrito where registro = $registro";
			$r310=mysql_query($r310);
			$r310="UPDATE ".$_SESSION['bdd']."_proyecciones set pendiente=(capital+interes)-ultimomonto where registro = $registro";
			$r310=mysql_query($r310);
			$r310="SELECT * FROM ".$_SESSION['bdd']."_proyecciones where registro = $registro";
			$r310=mysql_query($r310);
			$r310=mysql_fetch_assoc($r310);
			if ($r310['pendiente'] <= 0)
			{
				$ultimacuotapagada=$lacuota;
				$r310="UPDATE ".$_SESSION['bdd']."_proyecciones set pagado='S' where registro = $registro";
				$r310=mysql_query($r310);		
			}
			
			agregar_f820($elasiento, $proceso, $cargar, $cuenta1, 'Abono '.$nombreprestamo.' Cuota '.$lacuota , $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
			$sql_375="insert into ".$_SESSION['bdd']."_sgcaf375 (nro_rec, nro_che, fecha, banco, monto, forma, ip, nro_pre, ahorrovol) VALUES ('$referencia','$deposito','$hoy','$elbanco',$debe, '$laforma','$ip','$np', 'C')";
			$resultado=mysql_query($sql_375);
			
			$albanco+=$debe;

			$debe=abs($interes);
			$cuenta1='ctainteres'.($i+1);
			$cuenta1=trim($$cuenta1); // .'-'.substr($codigo,1,4);
			agregar_f820($elasiento, $proceso, $cargar, $cuenta1, 'Int. '.$nombreprestamo.' Cuota '.$lacuota , $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
			$albanco+=$debe;
			$sql_375="insert into ".$_SESSION['bdd']."_sgcaf375 (nro_rec, nro_che, fecha, banco, monto, forma, ip, nro_pre, ahorrovol) VALUES ('$referencia','$deposito','$hoy','$elbanco',$debe, '$laforma','$ip','$np', 'I')";
//			echo $sql_375.'<br>';
			$resultado=mysql_query($sql_375);
			// actualizar el 310
//			$registro=$r310['registro'];
//			$saldo=$r310['monpre_sdp']-($anterior - $debe);
			$saldo=buscar_saldo_f810($ctacapital);
//			echo $r310['monpre_sdp'].' - '.$saldo .' - '.$anterior.' - '. $debe.'<br>';
//			$cuotaspagadas=$debe / $r310['cuota_ucla'];
			
			$sql310="update ".$_SESSION['bdd']."_sgcaf310 set monpag_sdp = monpag_sdp + '$debe', ultcan_sdp=$ultimacuotapagada ";
			$sql310.=" where nropre_sdp = '$nroprestamo' and codpre_sdp='$tipoprestamo'";
//			echo $sql310;
			$act310=mysql_query($sql310);
			$sql310="SELECT * FROM ".$_SESSION['bdd']."_sgcaf310 where nropre_sdp = '$nroprestamo'";
			$act310=mysql_query($sql310);
			$r310 =mysql_fetch_assoc($act310);
			if ($r310['ultcan_sdp'] == $r310['nrocuotas'])
			{
				$sql310="update ".$_SESSION['bdd']."_sgcaf310 set stapre_sdp = 'C', renovado = 1, paga_hasta = '$hoy', renova_por='FIN PAGO' ";
				$act310=mysql_query($sql310);
			}
			// actualizar el 320
//			actualizar_fiador($r310['codsoc_sdp'],$debe,$np);

		}
	}

	//---- ahorros ----------------  
	// revisar si hay pendiente y abono a un nuevo pendiente
	// hacer un solo update por ahorro y ver como queda el recibo
	if ($_POST['montoahorros'] > 0)
		$des.=' P/R Ahorros ';
	//---- prestamos ----------------  
	// revisar si hay pendiente y abono a un nuevo pendiente
	// hacer un solo update por ahorro y ver como queda el recibo
	for ($i=0;$i<$registroah;$i++)
	{
		$variable='cancelarah'.($i+1);
		$var2='cancelaraht'.($i+1);
		$txt='cancelarahtxt'.($i+1);
		$pendiente=false;
//		echo 'pendiente '.	substr($$txt,0,5);
		if (substr($$txt,0,5) == "Pend.")
		{
			$pendiente=true;
			$_txt=substr($$txt,5,10);
		}
		else $_txt=$$txt;
//		echo '---txt '.$_txt.'<br>';
			
		if (!empty($$variable)) 
		{
			$des.=$$variable.' ';
			$des2=$$var2;
			
			$s310="insert into ".$_SESSION['bdd']."_fhis200 (cod_prof, hab_prof, hab_ucla, fecha, total_ahor, descri, pago, ip) values ('$codigo', '".$$var2."', 0, '$proceso', 0, 'Ahorros ".$$var2." s/d $deposito ".($pendiente==true?'Pend.':'').convertir_fechadmy($_txt)."', '$proceso', '$ip')";
			
//             echo $s310; 
			$a310=mysql_query($s310);
			
			$s310="update ".$_SESSION['bdd']."_sgcaf200 set hab_f_prof = hab_f_prof + ".$$var2.", ultap_prof = '".$_txt."' ";
//			echo $s310;
			$original='cancelaraho'.($i+1);
			$original=$$original;
			$s310.=" where cod_prof = '$codigo'";
			$a310=mysql_query($s310);

			$s310="select pendiente from ".$_SESSION['bdd']."_sgcaf200 where cod_prof = '$codigo'";
			$a310=mysql_query($s310);
			$r310=mysql_fetch_assoc($a310);
			$pendiente=$r310['pendiente'];
// 			echo 'variable '.$des2. ' origina '.$original;
			if (($pendiente == true) or ($des2 	< $original))
			{
				$s310="update ".$_SESSION['bdd']."_sgcaf200 set pendiente = ".$original."- ".$des2. " where cod_prof = '$codigo'";
//				echo $s310;
				$a310=mysql_query($s310);
			}
			
			$s310="select * from ".$_SESSION['bdd']."_sgcaf000 where tipo = 'RetSoc'";
			$a310=mysql_query($s310);
			$r310=mysql_fetch_assoc($a310);
			
			// saldo pendiente del prestamo
			$cuenta1=trim($r310['nombre']).'-'.substr($codigo,1,4);
//			$debe=buscar_saldo_f810($cuenta1);
			$cargar='-'; 
			$anterior='cancelaraht'.($i+1);
			$anterior=abs($$anterior);
			$debe=$$var2; // 'cancelart'.($i+1);
			$debe=abs($debe);
			$np=$$variable;
			$np2=$$txt;
			if (substr($np2,0,1)=='2')
				$np2=convertir_fechadmy($np2);
			
			agregar_f820($elasiento, $proceso, $cargar, $cuenta1, 'Pago Cuota '.$np2, $debe, $haber, 0,$ip,0,$referencia,'','S',0); 
			$albanco+=$debe;
			$sql_375="insert into ".$_SESSION['bdd']."_sgcaf375 (nro_rec, nro_che, fecha, banco, monto, forma, ip, nro_pre) VALUES ('$referencia','$deposito','$hoy','$elbanco',$debe, '$laforma','$ip','$np')";
//			echo $sql_375.'<br>';
			$resultado=mysql_query($sql_375);
		}
	}

	$des.=' segun '.$laforma.' Nro. '.$deposito;
	$debe = $albanco; //  - ($intereses_diferidos + $d_obligatorias); 
	$sql="update ".$_SESSION['bdd']."_sgcaf370 set descri1 = '$des', monto ='$albanco' where nro_rec='$referencia'";
	$a=mysql_query($sql);
	$sql="update ".$_SESSION['bdd']."_sgcaf830 set enc_desco = '$des', enc_explic='$des' where enc_clave ='$elasiento'";
	$a=mysql_query($sql);
	$cargar='+';
	$sqlbanco="select * from ".$_SESSION['bdd']."_sgcaf843 where recibirpago=1 and cod_banco='$elbanco'";
	$resultado=mysql_query($sqlbanco);
	$fila2 = mysql_fetch_assoc($resultado);
	$cuenta1=$fila2['cue_banco'];
	$concepto="Ahorros / Abono Prest. Socio $codigo";
	agregar_f820($elasiento, $proceso, $cargar, $cuenta1, $concepto, $debe, $haber, 0,$ip,0,$deposito,'','S',0); 

	echo '<input type="submit" name="Submit" value="Imprimir Recibo" onClick="abrirVentana(';
	echo "'";
	echo $referencia;
//	echo "&c1=".$c1."&cu=".$cu."&ia=".$ia."&ac=".$ac."&ta=".$ta."&tc=".$tc."&i1=".$i1;
//	echo "&otro=".$better_token.$better_token;
//	echo "&aa=$capital&bb=$cuotas&ia=$ia[$cuotas]&cc=$cu[1]&dd=$interes&socio=";
//	echo trim($r_200['ape_prof']). ', '.trim($r_200['nombr_prof']);
//	echo "&prestamo=".trim($r_310['descr_pres']);
	echo "&comprobante=";
	echo $elasiento;
	echo "'";
	echo ');">  ';
	echo '</div>';
}	// fin de ($accion == "GenerarRecibos")


CREATE TABLE `cacpcel_sgcafap1` (
`cod_prof` VARCHAR( 5 ) NOT NULL ,
`cod_prog` VARCHAR( 4 ) NOT NULL ,
`pres_prog` INT( 1 ) NOT NULL ,
`des_prog` INT( 1 ) NOT NULL ,
`hab_prog` DECIMAL( 12, 2 ) NOT NULL ,
`fec_prog` DATE NOT NULL ,
`pen_prog` DECIMAL( 12, 2 ) NOT NULL ,
`ult_prog` DATE NOT NULL ,
`mp_prog` DECIMAL( 12, 2 ) NOT NULL ,
`npres_prog` VARCHAR( 10 ) NOT NULL ,
`nro_che` VARCHAR( 10 ) NOT NULL ,
`fec_che` DATE NOT NULL ,
`che_banco` VARCHAR( 4 ) NOT NULL ,
`ini_prog` DECIMAL( 12, 2 ) NOT NULL ,
PRIMARY KEY ( `cod_prog` )
) ENGINE = MYISAM ;
ALTER TABLE `cacpcel_sgcaf370` ADD `cod_prog` VARCHAR( 4 ) NOT NULL ;
// cambiar a 10 ahorrovol en la 375
ALTER TABLE `cacpcel_sgcafap1` ADD `fec_inscr` DATE NOT NULL ;
ALTER TABLE `cacpcel_sgcafap1` ADD `cuota` DECIMAL( 12, 2 ) NOT NULL ;
*/
?>
