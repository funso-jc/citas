<?php
include("head.php");
//include("popcalendario/escribe_formulario.php");
?>
<script language="javascript">
function abrirVentana(fechadescuento)
{
// window.open("06_Inventario_actuallist.asp","prueba1", "width=385,height=180,top=0,left=0',status,toolbar =1,scrollbars,location");
// window.open("leftmenu.htm","prueba2","width=385,he ight=180,top=0,left=395,status,toolbar=1,scrollbar s,location");
window.open("cuoceropdf.php?fechadescuento="+fechadescuento,"parte1","top=0,left=395,status=no,toolbar=no,scrollbar=yes,location=no,type=fullWindow,fullscreen");	// los primeros 500 socios	width=385,height=180,
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
	echo "<form action='cuocero.php?accion=Listado' name='form1' method='post'>";
	echo '<fieldset><legend>Informaci�n Para Prestamos Cuota 0</legend>';
	echo 'Reporte de Pr�stamos en 0 al: ';
	$fechadelabono=date("d")."/".date('m')."/".date("Y"); 
	echo $fechadelabono;
	$hoy = date("d")."/".date('m')."/".date("Y"); 
	echo '<input type="hidden" name="fechadelpago" value="'.$hoy.'"/><br>';
	echo '<input type="submit" name="Submit" value="Obtener Reporte a la Fecha" />';
	echo '</legend>';
	echo '</form>';
	echo '</div>';
}	// !$accion
if ($accion=='Listado') {
//	$fechadescuento=convertir_fecha($_POST['fechadelpago']);		// revisar que no hayan nominas con esa fecha
	echo "<div id='div1'>";
	echo "<form action='cuocero.php?accion=Listo' name='form1' method='post'>"; //  onsubmit='return realizo_abono(form1)'>";
	$fechadescuento=$_POST['fechadelpago'];
	echo '<fieldset><legend>Recopilando informaci�n Para Prestamos Cuota 0 al '.$fechadescuento.'</legend>';
	echo '<h2>Preparando informaci�n...</h2>';
	echo '<input type="submit" name="Submit" value="Impresi�n de Listados" onClick="abrirVentana(';
	echo "'";
	echo $fechadescuento;
	echo "'";
	echo ');">  ';
	echo '</legend>';
	echo '</form>';
	echo '</div>';	
}	// ($accion=='Listado')
if (($accion=='Listo')) { // and ($nominasnormales == 'on')) {
// if ($nominasnormales == 'on') {
	$fechadescuento=$_POST['fechadescuento'];
	$nombre_archivo=$_POST['nombre_archivo'];
//	echo '<input type="hidden" name="nombre_archivo" value = "'.$nombre_archivo.'"/>';
	echo "<div id='div1'>";
	
	echo '<h2>Listado Generado...</h2>';
	echo '</div>';
}	// ($accion=='Listo') 

?>

<?php include("pie.php");?>

</body></html>

