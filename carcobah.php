<?php
include("head.php");
//include("popcalendario/escribe_formulario.php");
?>
<script language="Javascript" src="selec_fecha.js" type='text/javascript'></script>
<script language="javascript">
function abrirVentana(fechadescuento, minimo, maximo, inicio, fin)
{
// window.open("06_Inventario_actuallist.asp","prueba1", "width=385,height=180,top=0,left=0',status,toolbar =1,scrollbars,location");
// window.open("leftmenu.htm","prueba2","width=385,he ight=180,top=0,left=395,status,toolbar=1,scrollbar s,location");
window.open("carcobahpdf.php");
//?fechadescuento="+fechadescuento+"&minimo="+minimo+"&maximo="+maximo+"&inicio="+inicio+"&fin="+fin,"parte1","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen")
//

//);	// los primeros 500 socios	width=385,height=180,
/*
window.open("cuobanpdf2.php?fechadescuento="+fechadescuento,"parte2","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// los demas
window.open("cuobanpdf3.php?fechadescuento="+fechadescuento,"resumen","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
//,"width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// resumen de los montos
window.open("cuobanpdf4.php?fechadescuento="+fechadescuento,"banco","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// el listado a banco
window.open("cuobanpdf5.php?fechadescuento="+fechadescuento,"amortiza","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// amortizacion / capital
//window.open("cuobanpdf6.php?fechadescuento="+fechadescuento,"descargar","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");
// "width=385,height=180,top=0,left=395,status,toolbar=1,scrollbar s,location");	// amortizacion / capital
*/
}
</script>
<?php
// include("paginar.php");

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
	echo "<form action='carcobah.php?accion=Cartas' name='form1' method='post'>";
	echo '<fieldset><legend>Información Para Listado de Solvencias</legend>';
/*
	$sqlpre="SELECT * FROM ".$_SESSION['bdd']."_sgcaf360 where (retab_pres = 0) ORDER BY cod_pres";
//	echo $sqlpre;
	echo '<table align="center" class="basica 100 hover" width="500" border="1">';
	echo '<tr><td>';
	echo 'Iniciar en ';
   	echo '<td class="rojo">';
	echo '<select name="elinicio" size="1">';
	$resultado=mysql_query($sqlpre);
	while ($fila2 = mysql_fetch_assoc($resultado)) {
		echo '<option value="'.$fila2['cod_pres'].'">'.$fila2['cod_pres'].' - '.$fila2['descr_pres'].'</option>'; }
	echo '</select> *'; 
	echo '</td>';
	echo '</tr>';

	echo '<tr><td>';
	echo 'Terminar en ';
   	echo '<td class="rojo">';
	echo '<select name="elfin" size="1">';
	$resultado=mysql_query($sqlpre);
	while ($fila2 = mysql_fetch_assoc($resultado)) {
		echo '<option value="'.$fila2['cod_pres'].'">'.$fila2['cod_pres'].' - '.$fila2['descr_pres'].'</option>'; }
	echo '</select> *'; 
	echo '</td>';
	echo '</tr>';
	
	echo '<tr><td>';
	echo 'Fecha de Vencimiento ';
	echo '</td>';
   	echo '<td class="rojo">';
?>
	<script type="text/javascript">
	setActiveStyleSheet(document.getElementById("defaultTheme"), "green");
	</script>
	<input type="text" name="date3" id="sel3" size="12" readonly><input type="reset" value=" ... " onClick="return showCalendar('sel3', '%d/%m/%Y');">
<?php
	echo '</td>';
	echo '</tr>';
	echo '<tr><td>';
	echo 'Minimo Dias de Pago';
	echo '</td>';
   	echo '<td class="rojo">';
	echo '<select id="elminimo" name="elminimo" size="1">';
	for ($laposicion=120;$laposicion >= 30;$laposicion--) {
		echo '<option value="'.$laposicion.'" >'.$laposicion.' </option>'; }
		// 
	echo '</select>'; 
	echo '</td>';
	echo '</tr>';
	
	echo '<tr><td>';
	echo 'Maximo Dias de Pago';
	echo '</td>';
   	echo '<td class="rojo">';
	echo '<select id="elminimo" name="elmaximo" size="1">';
	for ($laposicion=(365*3);$laposicion >= 30;$laposicion--) {
		echo '<option value="'.$laposicion.'" >'.$laposicion.' </option>'; }
		// 
	echo '</select>'; 
	echo '</td>';
	echo '</tr>';
	
	echo '<tr><td align="center" colspan="2">';
	echo '<input type="submit" name="Submit" value="Obtener Reporte" />';
	echo '</td>';
	echo '</tr>';
	
	echo '</legend>';
	echo '</form>';
	echo '</div>';
}	// !$accion
if ($accion=='Listado') {
//	$fechadescuento=convertir_fecha($_POST['fechadelpago']);		// revisar que no hayan nominas con esa fecha
	echo "<div id='div1'>";
	echo "<form action='carcobah.php?accion=Cartas' name='form1' method='post'>"; //  onsubmit='return realizo_abono(form1)'>";
	$fechadescuento=convertir_fecha($_POST['date3']);
	echo '<fieldset><legend>Recopilando información Para Saldos de Prestamos al '.$fechadescuento.'</legend>';
*/
	echo '<h2>Preparando información...</h2>';
	echo '<input type="submit" name="Submit" value="Generar Listado" onClick="abrirVentana()">';
/*
	echo "'";
	echo $date3;
	echo "',";
	echo "'";
	echo $elminimo;
	echo "',";
	echo "'";
	echo $elmaximo;
	echo "',";
	echo "'";
	echo $elinicio;
	echo "',";
	echo "'";
	echo $elfin;
	echo '\');">  ';
	echo '</legend>';
	echo '<input type="hidden" name="fechadescuento" id="fechadescuento" value="'.$fechadescuento.'">';
	echo '<input type="hidden" name="elminimo" id="elminimo" value="'.$elminimo.'">';
	echo '<input type="hidden" name="elmaximo" id="elmaximo" value="'.$elmaximo.'">';
	echo '<input type="hidden" name="elinicio" id="elinicio" value="'.$elinicio.'">';
	echo '<input type="hidden" name="elfin" id="elfin" value="'.$elfin.'">';
	echo '</form>';
	echo '</div>';	
*/
}	// ($accion=='Listado')
if (($accion=='Cartas')) { // and ($nominasnormales == 'on')) {
// if ($nominasnormales == 'on') {
	$fechadescuento=$_POST['fechadescuento'];
	echo "<form action='carcobah.php?accion=Listo' name='form1' method='post' onsubmit='return realiza_emails_cobranza(form1)'>"; 
	echo "<div id='div1'>";
	echo '<input type="hidden" name="fechadescuento" id="fechadescuento" value="'.$fechadescuento.'">';
	echo '<input type="hidden" name="elminimo" id="elminimo" value="'.$elminimo.'">';
	echo '<input type="hidden" name="elmaximo" id="elmaximo" value="'.$elmaximo.'">';
	echo '<input type="hidden" name="elinicio" id="elinicio" value="'.$elinicio.'">';
	echo '<input type="hidden" name="elfin" id="elfin" value="'.$elfin.'">';
	echo '<input type="submit" name="Submit" value="Enviar emails con Cobranza Ahorro" />';
/*
	$_SESSION['fechadescuento']=$fechadescuento;
	$_SESSION['elminimo']=$elminimo;
	$_SESSION['elmaximo']=$elmaximo;
	$_SESSION['elinicio']=$elinicio;
	$_SESSION['elfin']=$elfin;
*/
	echo '</form>';
	echo '</div>';
}	// ($accion=='Listo') 
if (($accion=='Listo')) { // and ($nominasnormales == 'on')) {
// if ($nominasnormales == 'on') {
	$sqldis = "SELECT DATE_ADD(NOW(),INTERVAL -30 DAY) AS siguiente";
	$arcdis=mysql_query($sqldis);
	$regdis=mysql_fetch_assoc($arcdis);
	$fechasiguiente=substr($regdis['siguiente'],0,10);

	$fechadescuento=$fechasiguiente; // $_POST['fechadescuento'];
	$nombre_archivo=$_POST['nombre_archivo'];
	echo '<fieldset><legend>Generando mail de vencimiento '.convertir_fechadmy($fechadescuento).'</legend>';
	include('mailcobranzaah.php');
//	echo '<input type="hidden" name="nombre_archivo" value = "'.$nombre_archivo.'"/>';
	echo "<div id='div1'>";
	
	echo '<h2>Listado Generado...</h2>';
	echo '</fieldset>';
	echo '</div>';
}	// ($accion=='Listo') 

?>

<?php include("pie.php");?>

</body></html>

