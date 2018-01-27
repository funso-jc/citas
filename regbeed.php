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
/*
if (!$link OR !$_SESSION['empresa']) {
	return;
}
*/
$sql="SELECT * FROM ".$_SESSION['bdd']."familiar WHERE id_familiar = $reg_afi";
// die( $sql);
$result = $db_con->prepare($sql);
$result->execute();
$fila = $result->fetch(PDO::FETCH_ASSOC);
// $a=explode("-",$fila["com_fecha"]); 
?>

<form action='regbenef.php?cedula=<?php echo $cedula; ?>&amp;row_id=<?php echo $fila['id_familiar']; ?>&amp;accion=editben1' name='form1' onsubmit="return compruebabeneficiario(form1)" method='post'>

<?php
// $fecha=$a[2]."/".$a[1]."/".substr($a[0],2,2);
// if (($fila['com_debcre']== '+')) $elmonto=$fila['com_monto1']; else $elmonto=$fila['com_monto2'];
pantalla_beneficiarios($fila['cedulafam'],$fila['ape_nomb'], $fila['fecha_nac'], $fila['parentesco'], $fila['sexo'], $db_con);
?>

<tr><td colspan='7' class='dcha'>

<input class='btn btn-warning' type = 'submit' name = 'formu' value = "Confirmar cambios" tabindex='10' onclick='return compruebabeneficiario(form1)'>

</td></tr>

</table>

</form>


