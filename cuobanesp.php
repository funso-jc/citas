<?php
include("head.php");
//include("popcalendario/escribe_formulario.php");
?>
<script language="javascript">
function abrir2Ventanas(fechadescuento)
{
// window.open("06_Inventario_actuallist.asp","prueba1", "width=385,height=180,top=0,left=0',status,toolbar =1,scrollbars,location");
// window.open("leftmenu.htm","prueba2","width=385,he ight=180,top=0,left=395,status,toolbar=1,scrollbar s,location");
window.open("cuobanpdf1.php?fechadescuento="+fechadescuento+"& dcto=0","parte1","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");	// los primeros 500 socios	width=385,height=180,
window.open("cuobanpdf2.php?fechadescuento="+fechadescuento+"& dcto=0","parte2","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// los demas
window.open("cuobanpdf3.php?fechadescuento="+fechadescuento+"& dcto=0","resumen","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
//,"width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// resumen de los montos
window.open("cuobanpdf4.php?fechadescuento="+fechadescuento+"& dcto=0","banco","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// el listado a banco
window.open("cuobanpdf5.php?fechadescuento="+fechadescuento+"& dcto=0","amortiza","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// amortizacion / capital
//window.open("cuobanpdf6.php?fechadescuento="+fechadescuento,"descargar","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// amortizacion / capital
}
</script>
<?php
include("paginar.php");

extract($_GET);
extract($_POST);
extract($_SESSION);


if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
?>
<body <?php if (!$bloqueo) {echo $onload;}?>>


<?php

$readonly=" readonly='readonly'";
include("arriba.php");
// $menu61=1;
include("menusizda.php");
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
// echo '<h1>Desarrollando.... pronto estara lista</h1>';
if (!$accion) {
	echo "<div id='div1'>";
	echo "<form action='cuobanesp.php?accion=ListadoDeCuotas' name='form1' method='post'>";
	echo '<fieldset><legend>Informaci�n Para Descuentos de Prestamos Especiales</legend>';
	echo '<table border="0">';
	echo '<tr><td>Fecha en que se realiza el descuento: </td><td>';
	$fechadelabono=date("d")."/".date('m')."/".date("Y"); 
//	escribe_formulario_fecha_vacio("fechadelpago","form1",$fechadelabono,5,''); 
	/*
		fecha del abono = fecha de la forma
		form1.fechadelabono	= ?
		'd/m/yyyy' =	formato de la fecha
		$fechadelabono 	= fecha por defecto
		$mesant			= rango anterior
		$hoy			= rango maximo
		'1'				= no habilita sabados ni domingos '0' muestra todo
		'3'				= cantidad de anos que se pueden visualizar
	*/
	$hoy1 = mktime(0,0,0,date("m"),date("d"),date("Y")); 
	$h = date("d/m/Y",$hoy1);
	$futuro = $hoy1+((30*24*3600)*24); // 30 dias
	$pasado = $hoy1-((30*24*3600)*24); // 3 dias
	$futuro = date("d/m/Y",$futuro);
	$pasado = date("d/m/Y",$pasado);
	escribe_formulario(fechadelpago, form1.fechadelpago, 'd/m/yyyy',$fechadelabono, $pasado, $futuro, '0', '1');
/*
	echo '<br>Numero de Cuotas a Descontar: ';
	echo '<select id="lascuotas" name="lascuotas" size="1">';
	for ($laposicion=1;$laposicion < 5;$laposicion++) {
		echo '<option value="'.$laposicion.($posicion==1?" selected ":"").'" >'.$laposicion.' </option>'; }
	echo '</select><br>'; 
*/
//	echo '<br>Nominas Normales <input type="checkbox" name="nominasnormales" value = "off" checked align="right"/><br />';
	echo '</td></tr><tr><td>';
	echo "Porcentaje (%) a Descontar: </td><td><input type='text' name='porcentaje' value='100' size='10' maxlength='10'></td></tr>";
/*
	echo '<tr><td>Seleccione Tipo</td>';
   	echo '<td class="rojo">';
	echo '<select name="elprestamo" size="1">';
	$sqlprestamos='select * from sgcaf360 where dcto_sem=0 order by cod_pres';
//	echo $sqlprestamos;
	$resultado=mysql_query($sqlprestamos);
	while ($fila2 = mysql_fetch_assoc($resultado)) {
		echo '<option value="'.$fila2['cod_pres'].'">'.$fila2['cod_pres'].' - '.$fila2['descr_pres'].'</option>'; }
	echo '</select> *'; 
	echo '</td></tr>';
*/
	echo '<tr><td colspan="2">';
	echo '<input type="submit" name="Submit" value="Enviar" />';
	echo '</td></tr>';
	echo '</legend>';
	echo '</form>';
	echo '</table>';
	echo '</div>';
}	// !$accion
// echo 'accion = '.$accion;
// echo 'nominas '.$_POST['nominasnormales'];
// recordar bloquear la base de datos durante este proceso y luego liberarla
if (($accion=='ListadoDeCuotas')) { //  and ($nominasnormales == 'on')) {
	$fechadescuento=convertir_fecha($_POST['fechadelpago']);		// revisar que no hayan nominas con esa fecha
	$sql="select count(fecha) as cuantos, ip from sgcanopr where fecha = '$fechadescuento' group by fecha";
//	echo $sql;
	$resultado=mysql_query($sql);
	if (mysql_num_rows($resultado)>0) {
		$registro=mysql_fetch_assoc($resultado);
		echo '<h2>No se puede procesar esta nomina existe una ya realizada con '.$registro['cuantos'].' registro generada desde la IP '.$registro['ip'].'</h2>';
		exit;
	}

	$fechaarchivo=explode('-',$fechadescuento);
	$fechaarchivo=$fechaarchivo[0].$fechaarchivo[1].$fechaarchivo[2];
	$nombre_archivo = 'nompre/'.$fechaarchivo.'domiciliacion.txt';
	$contenido = $nombre;
	fopen($nombre_archivo, 'w');

	// Asegurarse primero de que el archivo existe y puede escribirse sobre el.
	if (is_writable($nombre_archivo)) {

		// En nuestro ejemplo estamos abriendo $nombre_archivo en modo de adicion.
		// El apuntador de archivo se encuentra al final del archivo, asi que
		// alli es donde ira $contenido cuando llamemos fwrite().
		if (!$gestor = fopen($nombre_archivo, 'a')) {
			echo "<h2>No se puede abrir el archivo ($nombre_archivo) revise permisologia</h2>";
			exit;
		}
		else {

			echo "<div id='div1'>";
			echo "<form action='cuobanesp.php?accion=Abonar' name='form1' method='post' onsubmit='return realizo_abono(form1)'>";
			echo '<input type="hidden" name="nombre_archivo" value = "'.$nombre_archivo.'"/>';
			echo '<input type="hidden" name="nominasnormales" value = "on"/>';
			$porcentaje = 
			$fechadescuento=$_POST['fechadelpago'];
			echo '<fieldset><legend>Recopilando informaci�n Para Descuentos de Prestamos al '.$fechadescuento.'</legend>';
			echo '<h2>Preparando informaci�n...</h2>';
			$fechadescuento=convertir_fecha($fechadescuento);
			$sql_360="select * from sgcaf360 where (dcto_sem = 0) order by cod_pres";
			$a_360=mysql_query($sql_360);
			$sql_200="select cod_prof, ced_prof, concat(ape_prof, ' ', nombr_prof) as nombre, ctan_prof from sgcaf200 where (ucase(statu_prof) != 'RETIRA') and (tipo_socio='P') order by ced_prof";
			$a_200=mysql_query($sql_200);
			while ($r200 = mysql_fetch_assoc($a_200))
			{
				$cedula=$r200['ced_prof'];
				$micedula=substr($cedula,0,4).'.'.substr($cedula,4,3).'.'.substr($cedula,7,4);
				$sql_310="select * from sgcaf310 where (stapre_sdp='A') and (cedsoc_sdp='$micedula') and (f_1cuo_sdp = '$fechadescuento') order by codpre_sdp";
				$a_310=mysql_query($sql_310);
				revisar_prestamo($r200,$a_310,$a_360,$fechadescuento,$micedula,$ip,$gestor, $porcentaje);
			} // ($r200 = mysql_fetch_assoc($a_200))
			echo '<input type="hidden" name="fechadescuento" value="'.$fechadescuento.'">';
			echo '<h2>Informaci�n Lista...</h2><br>';
			echo '<h2>Se ha generado el archivo '.$nombre_archivo.'<br> para su procesamiento a banco</h2>';
			echo '<input type="submit" name="Submit" value="Impresi�n de Listados" onClick="abrir2Ventanas(';
			echo "'";
			echo $fechadescuento;
			echo "'";
//			echo "'".'&downloadfile='.$nombre_archivo.'&';
			echo ');">  ';
//			echo '<input type="submit" name="Submit" value="Realizar Abono " />';
			echo '</legend>';
			echo '</form>';
			echo '</div>';	
		}
		fclose($gestor);
/*
		$downloadfile=nombre_archivo;
		echo 'header ("Content-Disposition: attachment; filename=\".$downloadfile.\"" )';
//		header ("Content-Disposition: attachment; filename=\"exportar.txt\"" );
		echo 'header("Content-Type: application/force-download")';
		echo 'header("Content-Transfer-Encoding: binary")';
		echo 'header("Content-Length: ".strlen($downloadfile))';
// 		header("Content-Length: ".strlen($filecontent));
		echo 'header("Pragma: no-cache")';
		echo 'header("Expires: 0")' ;
		echo $downloadfile;
*/
	}
	else {
		echo "<h2>No se puede crear el archivo ($nombre_archivo) revise permisologia</h2>";
		exit;
	}
}	// ($accion=='ListadoDeCuotas')
if (($accion=='Abonar')) { // and ($nominasnormales == 'on')) {
// if ($nominasnormales == 'on') {
	$fechadescuento=$_POST['fechadescuento'];
	$nombre_archivo=$_POST['nombre_archivo'];
//	echo '<input type="hidden" name="nombre_archivo" value = "'.$nombre_archivo.'"/>';
	echo "<div id='div1'>";
	
	echo '<h2>Puede proceder luego de la impresion de los listados a <br>realizar el abono a prestamos y el asiento contable y';
	echo '<br>recuerde obtener descargar el archivo </h2><h1>'.$nombre_archivo.'</h1><h2> para enviar al banco</h2>';
//	echo "<form action='bajpre.php' name='form1' method='post'>";
//	echo '<input type="submit" name="Submit" value="Descargar Archivo TXT"';
//	echo '</form>';
	/// hacer el asiento ver asiento original con intereses y demas
/*	echo '<script language="JavaScript" src="cuobanpdf1.php?fechadescuento=$fechadescuento"></script>';  */
//	echo '<a href="" onClick="abrir2Ventanas();">KK</a>';
//	echo "<a target=\"_blank\" href=\"cuobanpdf1.php?fechadescuento=$fechadescuento\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Listados de Descuentos</a>"; 	


	echo '</div>';
}	// ($accion=='ImpresionListados') 


function revisar_prestamo($r200,$a_310,$a_360,$fechadescuento,$micedula,$ip,$gestor, $porcentaje)
{
	$primeravez=0;
	$totalxsocio=0;
	while ($r310 = mysql_fetch_assoc($a_310))
	{
		if (! $r310['renovado'])
			if ($r310['stapre_sdp'] == 'A')
				acumular($r200,$r310,$a_360,$fechadescuento,$micedula,$primeravez,$ip,$totalxsocio, $porcentaje) ;
			else ;
		else 
			if ($r310['stapre_sdp'] == 'A')
				if ($r310['paga_hasta'] >= $fechadescuento)
					acumular($r200,$r310,$a_360,$fechadescuento,$micedula,$primeravez,$ip,$totalxsocio, $porcentaje);
	}
	if ($totalxsocio > 0){	// meterlo en el listado a banco
		listadotxt($r200,$totalxsocio,$gestor);
	}
}

function listadotxt($r200,$totalxsocio,$gestor)
{
//0201082457570200015888V07333526        00000000000008937ABARCA DE G.TERESA G.                                                 00CAPPOUCL              *
//0201082457510200129328V16770549        00000000000000010Xx  CARRASCO R. TONDIS MIGUEL                                         00CAPPOUCL              *
	$cadena='02'.$r200['ctan_prof'];
	$cadena.=substr($r200['ced_prof'],0,1).substr($r200['ced_prof'],2,8).replicate(' ',8);
	$monto=trim($totalxsocio);
	// quito el punto
	$sinpunto='';
	for ($i=0;$i<strlen($monto);$i++)
		if (substr($monto,$i,1)!= '.')
			$sinpunto.=substr($monto,$i,1);
	$monto=ceroizq($sinpunto,17);
	$cadena.=$monto;
	$nombre=$r200['nombre'];
	$nombre=substr(trim($nombre),1,40);
	$rellenar=replicate(' ',40-strlen($nombre));
	$cadena.=$nombre.$rellenar;
	$cadena.=replicate(' ',30).'00'.'CAPPOUCL'.replicate(' ',14).'*'.chr(13).chr(10);
	if (fwrite($gestor, $cadena) === FALSE) {
		echo "No se puede escribir al archivo ($nombre_archivo)";
		exit;
	}

}

function replicate($caracterarepetir,$cantidaddeveces)
{
	$resultado='';
	for ($i=0;$i<$cantidaddeveces;$i++)
		$resultado.=$caracterarepetir;
	return $resultado;
}


function acumular($r200,$r310,$a_360,$fechadescuento,$micedula,&$primeravez,$ip,&$totalxsocio, $porcentaje)
{
	if ($r310['cuota_ucla'] == 0) {
		$actualiza="update sgcaf310 set cuota_ucla=".$r310['cuota']." where registro =".$r310['registro'];
		$ractualiza=mysql_query($actualiza);
		$lacuota = $r310['cuota'];
	}
	else $lacuota = $r310['cuota_ucla'];
	$lacuota = $lacuota; // * ($porcentaje / 100);
	mysql_data_seek ($a_360, 0);		// volver al principio de la busqueda
	$nombre=$r200['nombre'];
	$codigo=$r310['codsoc_sdp'];
	$posicion=0;
	while ($r360 = mysql_fetch_assoc($a_360))
	{
		$posicion++;
		if ($r310['codpre_sdp']==$r360['cod_pres']) {
//			echo $r310['nropre_sdp'].' - ' .$r310['codpre_sdp'].' '; // . ' - '.$r360['cod_pres'];
			$lacolumnapres='colpre'.$posicion;
			$lacolumnanro='colnro'.$posicion;
			$elnumero=$r310['nropre_sdp'];
			if ($primeravez == 0) {
				$nrocuenta=$r200['ctan_prof'];
				$sql_pre="insert into sgcanopr (fecha, cedula, codigo, nombre, ".$lacolumnapres.", ".$lacolumnanro.", proceso, ip, nrocta) values ('$fechadescuento','$micedula','$codigo','$nombre','$lacuota','$elnumero',1, '$ip', '$nrocuenta')";
					$primeravez = 1;
			}
			else $sql_pre="update sgcanopr set ".$lacolumnapres." = '$lacuota', ".lacolumnanro." = '$elnumero' where (cedula=$micedula)" ;
			if (mysql_query($sql_pre)) {
				$saldo=$r310['monpre_sdp']-$r310['monpag_sdp'];
				$tipo=$r310['codpre_sdp'];
				$capital=$lacuota;
				$totalxsocio+=$capital;
				$i2=0;
//				echo $r360['i_max_pres']. ' - ' .$r310['nrocuotas'].' - '.$saldo.' - '.'<br>';
				$interes=cal_int($r360['i_max_pres'],$r310['nrocuotas'],$saldo,$factor_divisible = 52,$z=0,$i2);
//				echo $i2;
				$interes=round(($saldo*$i2),2);
				$cu=round($lacuota,2) - $interes;
				$la_cuota=$interes+$cu;
				if ($saldo <= 0) {
					$interes=0;
					$capital=$cuota;
				}
				$cuent_p=trim($r360['cuent_pres']).'-'.substr($codigo,1,4);
				$cuent_i=trim($r360['cuent_int']).'-'.substr($codigo,1,4);
				$cuent_d=trim($r360['otro_int']);
				$nrocuota=$r310['ultcan_sdp']+1;
				$tipoprestamo=$r360['tipo'];
				$pos310=$r310['registro'];
				$sql_amor="insert into sgcaamor (fecha, codsoc, nropre, cedula, nombre, saldo, capital, interes, cuota, codpre, cuent_p, cuent_i, cuent_d, ip, nrocuota, proceso, tipo, pos310) values ('$fechadescuento', '$codigo', '$elnumero', '$micedula', '$nombre', '$saldo', '$capital', '$interes', '$la_cuota', '$tipo', '$cuent_p', '$cuent_i', '$cuent_d', '$ip','$nrocuota', 1, '$tipoprestamo', '$pos310')";
				$result2=mysql_query($sql_amor);
//				echo $sql_amor.'<br>';
			}
//			echo $sql_pre.'----'.$posicion.'<br>';
		}	// ($r360 = mysql_fetch_assoc($a_360))
	} // ($r310 = mysql_fetch_assoc($a_310))
}


?>

<?php include("pie.php");?>

</body></html>

