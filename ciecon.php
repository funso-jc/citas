<?php

//Copyright (C) 2000-2006  Antonio Grandío Botella http://www.antoniograndio.com
//Copyright (C) 2000-2006  Inmaculada Echarri San Adrián http://www.inmaecharri.com

//This file is part of Catwin.

//CatWin is free software; you can redistribute it and/or modify
//it under the terms of the GNU General Public License as published by
//the Free Software Foundation; either version 2 of the License, or
//(at your option) any later version.

//CatWin is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details:
//http://www.gnu.org/copyleft/gpl.html

//You should have received a copy of the GNU General Public License
//along with Catwin Net; if not, write to the Free Software
//Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

include("head.php");
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
if ($_GET['emp'] == 1) {$_GET['n'] = 1;}

extract($_GET);
extract($_POST);

// 		$sql="update definiti set final='1', cierre='$b' where idempresa='".$_SESSION['idempresa']."'";
//		$resultado=mysql_query($sql);

/*
if ($fila['final'] == 1) {		// listo para hacer cierre
	$onload="onload=\"foco('asiento')\"";
	//$result = mysql_query("SELECT max(asiento) FROM asientos");
	//$row = mysql_fetch_row($result);
	//$asiento = $row[0] + 1;
	$fila = mysql_fetch_array(mysql_query("SELECT con_compr FROM sgcaf8co where idempresa='".$_SESSION['idempresa']."'"));
	$asiento = $fila[0] + 1;
	mysql_query("UPDATE sgcaf8co SET con_compr = '$asiento' WHERE idempresa='".$_SESSION['idempresa']."'");
	// Cojo el valor de la fecha en que se hizo el último Asiento
	$result = mysql_query("SELECT date_format(con_ultfec,'%d/%m/%y') AS ultfechax FROM sgcaf8co where idempresa='".$_SESSION['idempresa']."'");
	$row = mysql_fetch_array($result);
	$fecha = $row[0];
} else {
	$onload="onload=\"foco('cuenta11')\"";
	$readonly=" readonly='readonly'";
	$asiento = $_POST['asiento'];
	$fecha = $_POST['fecha'];
	$fecha = $_POST['fecha'];
	$tipo =$_POST['tipo'];
	$debcre= $_POST['debcre'];
	$cuenta1= $_POST['cuenta1'];
	$referencia =$_POST['referencia'];
	$elmonto=$_POST['elmonto'];
}

?>
*/
?>
<body <?php if (!$bloqueo) {echo $onload;}?>>

<?php

include("arriba.php");
$menu24=1;include("menusizda.php");

/*
if ($elmonto) {
	include ("altaasim2.php");
//	$cuadre = totalapu($asiento);
}
*/

if (!$_POST['accion']) {
	echo '<fieldset><legend>Confirmar</legend>';
	echo "<form enctype='multipart/form-data' name='form1' action='ciecon.php' method='post'>";
	echo "<input type='hidden' name='accion' value='ListoCierre' />";
	echo "<input type='submit' name='boton' value=\"Cerrar Ejercicio\" tabindex='10'>"; 