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
echo "<form action='regbenef.php?cedula=".$cedula."&accion=agrben1' name='form1' onsubmit='return validar_beneficiario(form1)' method=post>\n";


pantalla_beneficiarios('','', '', '', '',$db_con);
?>

<tr>

<td colspan='7' class='dcha'>

<input class='btn btn-success' type = 'submit'name = 'formu' value = 'A&ntilde;adir' tabindex='10' onclick='return compruebabeneficiario(form1)'>

</td>

</tr>

</table>

</form>

