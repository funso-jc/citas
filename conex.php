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

include_once('dbconfig.php');
/*
//***********************************************************

if ($_POST['crearbd']) {include ("crearemp.php");}

//***********************************************************

if ($_GET['emp'] == 1) {
	$_POST['usuario1'] = 'administrador';
	$_POST['empresa1'] = 'nuevocat';
	$_POST['password1'] = "admin";
}

*/
if ($_POST['usuario1']) {
//	echo 'la bdd= '.$bdd.''.$_POST['empresa1'];
	if (1==1) { 


/*
	echo $_POST['usuario1'];
	echo $_POST['empresa1'];
	echo $_POST['password1'];
*/

// 	echo "entre 1";
		$sql="SELECT * FROM smobrero_smpass WHERE alias = '".$_POST['usuario1']."' AND password = PASSWORD('".$_POST['password1']."')";
//		die ($sql);
		$fila = $db_con->prepare($sql);
		$fila->execute();
		$fila=$fila->fetch(PDO::FETCH_ASSOC);
		if (!$fila) {
			echo "no fila";
			session_unset();session_destroy();return;
		} else {
			$_SESSION['empresa']= $_POST['empresa1'];
			$_SESSION['usuario'] = $_POST['usuario1'];
			$_SESSION['auto'] = $fila['perm'];
			$_SESSION['bdd']=''; // 'smobrero_';
			echo 'coloco la bdd';
		}
	} else {
		session_unset();session_destroy();return;
	}
}

if ($_POST['accion'] == 'desc') {

	session_unset();
	return;

}

if (substr(strrchr($_SERVER['SCRIPT_NAME'], "/"), 1) == "empresa.php"){
	
	session_unset();
	return;
	
}


if (!$_SESSION['empresa']) {return;}
?>
