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
?>
<script language="Javascript" src="selec_fecha_pasado.js" type='text/javascript'></script>
<?php 

/*
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
*/
?>

<body
<?php
if (!$bloqueo AND $cedula AND $accion AND ($accion == 'agrben' OR $accion == 'editben')) {echo " onload=\"foco('afafi')\"";}
?>
>

<?php
include("arriba.php");
$menu11=3;include("menusizda.php");

if (!$cedula) {

	echo "<form method='post' name='form1'>\n";
	echo "C&eacute;dula: <input type='text' name='cedula' size='10' maxlength='10'>\n";
	echo "<input class='btn btn-info' type='submit' name = 'formu' value='Buscar Beneficiarios'>\n";
	echo "</form>\n";
	echo "</div></body></html>";
	exit;

}
if ($accion == "agrben1" ) { // ($debe != 0 OR $haber != 0)) {
	$agregar=1;
	$herafi=(($herafi==on)?1:0);
	include ("regbeag1.php");
 }

if ($accion == "editben1" ) { // ($debe != 0 OR $haber != 0)) {
// 	include ("editapu1.php");
	$herafi=(($herafi==on)?1:0);
	$agregar=2;
	include ("regbeag1.php");
}

if ($accion == "boapu") {
// 	include ("borrapu1.php");
	$row_id=$_GET['id_familiar'];
	echo '<script language="javascript">alert("No se hace eliminaciones porque no se si tienen consumos aun")</script>';

}

/*
if ($accion == "boasi") {
 	$sql = "DELETE FROM sgcaf220 WHERE com_nrocom = '$cedula'";
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para borrar Asientos.");
	$sql = "DELETE FROM sgcaf220 WHERE enc_clave = '$cedula'";
	if (!mysql_query($sql)) die ("El usuario $usuario no tiene permiso para borrar Asientos.");
//	mysql_query("UPDATE factrec SET asiento = '' WHERE asiento = '$asiento'");
	echo "Asiento<span class='b'> ".$cedula." </span>borrado.\n";
	echo "</div></body></html>";exit;
}
*/

$cedula=ceroizq($cedula,8);
if ($cedula) {
	$sql="SELECT * FROM ".$_SESSION['bdd']."familiar WHERE cedula = '$cedula'";
	$result = $db_con->prepare($sql);
	$result->execute();
	if ($result->rowCount() == 0) {
		echo "<p />Cedula <span class='b'>$cedula</span> no tiene beneficiarios registrados</div></body></html>";
//		exit;
  
	}
	$fila = $result->fetch(PDO::FETCH_ASSOC);
}
// echo "<form enctype='multipart/form-data' name='justificante' action='editasi2.php?cedula=$cedula' method='post'>";
// echo "<label>Soporte</label> <input type='file' name='fich' size='19' maxlength='19'>";
// echo " (Si el asiento ya tiene un justificante será sustituído)";
// echo "<br /><label>Explicación</label> <textarea name='explicacion' rows='6' cols='90'>$fila[1]</textarea>";
// echo " <input type='submit' name='boton' value=\" >> \">";
// echo "</form>";

echo "<table class='basica 100 hover' width='850'>";
// width='100%'
cabben(2);
//totalbene($cedula);
beneficiarios($cedula,"1",$_SESSION['moneda'],$_SESSION['deci'],$_GET['bojust'], $db_con);

echo "</table><p />";

if ($accion == 'editben') {
	include ("regbeed.php");
}

if ($accion == 'agrben') {
	include ("regbeag.php");
}
?>

</div></body></html>

<?php
// ----------------------------------
function cabben($edborr) {
echo "<tr>";
}

//--------------------------------
function beneficiarios($cedula, $edborr, $por, $deci, $bojust, $db_con) {

$sql="SELECT cedula, ape_nom, stat_emp FROM ".$_SESSION['bdd']."obreros WHERE cedula = '$cedula'";
$result = $db_con->prepare($sql);
$result->execute();
if ($result) {$fichero = $result->fetch(PDO::FETCH_ASSOC);}

$cols = 6;
$inactivo=false;
if ($fichero['stat_emp'] == 'Retirado')
{
	echo '<script language="javascript">alert("Titular Inactivo")</script>';
	$inactivo=true;
	$edborr=0;
}
if ($edborr) {$cols = $cols+2;}

// echo "<tr><td class='blanco b' colspan='$cols'>Titular: <a href='editasi2.php?cedula=$cedula'>".$cedula."</a> Nombre: ";
echo "<tr><td class='blanco b' colspan='$cols+3'>Titular: ".$cedula." Nombre: " .trim($fichero['ape_nom']);
// echo $a[2]."/".$a[1]."/".$a[0]; // substr($a[0],2,2);
echo "</b>";

echo "</td></tr>";

$sql="SELECT * FROM ".$_SESSION['bdd']."familiar WHERE cedula = '$cedula' ORDER BY ape_nomb, fecha_nac";
$result = $db_con->prepare($sql);
$result->execute();
$suma=0;
$segvid=0;
if ($edborr) {echo "<th width='200' colspan=2></th>";}
echo '<th width="100">Cedula</th><th width="300">Nombre/Apellido Beneficiario</th><th width="100">Nacimiento</th><th width="80">Edad</th><th width="100">Parentesco</th><th width="100">Sexo</th></tr>';
while ($fila = $result->fetch(PDO::FETCH_ASSOC)) {

	echo "<tr>";
	if ($edborr) {
		echo "<td><a href='regbenef.php?reg_afi=".$fila['id_familiar']."&cedula=$cedula&accion=editben' target='_self'> <img src='imagenes/16-em-pencil.png' width='16' height='16' border='0' title='Editar' alt='Editar' /></a></td>";
		echo "<td><a href='regbenef.php?reg_afi=".$fila['id_familiar']."&cedula=$cedula&accion=boapu' onclick='return borrar_benef()'><img src='imagenes/16-em-cross.png' width='16' height='16' border='0' title='Eliminar' alt='Eliminar' /></a></td>";
	}
//	echo "<td><a href=\"extractoctas3.php?cuenta=".$fila["com_cuenta"]."&datos='no'\">".$fila["com_cuenta"]."</a></td>";
	echo "<td>".$fila["cedulafam"]."</td>";
	echo "<td>".$fila["ape_nomb"]."</td>";
	echo "<td>".convertir_fechadmy($fila["fecha_nac"])."</td>";
	echo "<td>".cedad(convertir_fechadmy($fila["fecha_nac"]))."</td>";
	echo "<td>".$fila["parentesco"]."</td>";
	echo "<td>".$fila["sexo"]."</td>";
/*
	echo "<td><img src='imagenes/".($fila["rec_afi"]==0?'here_down':'here_up').".png'  width='16' height='16' border='0' /></td>";
	echo "</td><td class='dcha'>";
	echo "</td>";
*/
/*
	echo "<td><img src='imagenes/".($fila["sv_afi"]==0?'here_down':'here_up').".png'  width='16' height='16' border='0' /></td>";
	if ($fila["sv_afi"]==1)
		$segvid++;
*/
	echo "</tr>";

}

//	echo "<tr><td class='rojo dcha b' colspan=".($cols);
/*
echo "  Total: </td><td class='blanco dcha b'>".number_format($suma,$deci,',','.')."</td>";
echo "</tr><tr><td colspan='$cols' class='verde'>&nbsp;</td></tr>
<p>";
*/
echo '<table class="table"><tr><td>';
echo "<a class='btn btn-info' href='regbenef.php?cedula=$cedula&accion=agrben'>A&ntilde;adir Beneficiarios</a></td>";
echo "<td><a class='btn btn-danger' href='regbeim.php?cedula=$cedula'>Imprimir Planilla (No Habilitado)</a></td></tr></table>";

}
  
function pantalla_beneficiarios($afiafi,$nomafi, $nacafi, $parafi, $sexo, $db_con)
{
echo "<table class='basica'>";
echo "<tr><th width=\"50\">Cedula</th><th>Apellidos y Nombres </th><th>Nacimiento</th><th >Parentesco</th><th>Sexo</th></tr>";
echo '<tr><td>';
echo "<input type = 'text' maxlength='12' size='12' name='afiafi' value='".$afiafi."' tabindex='3' onChange='conMayusculas(this)'  required>";
echo '</td><td>';
echo "<input type = 'text' size='40' maxlength='40' name='nomafi' tabindex='4' onChange='conMayusculas(this)' value ='".$nomafi."' required>";
echo '</td><td>';
$sqlf="SELECT DATE_FORMAT(now(),'%Y-%m-%d') as hoy, DATE_SUB(NOW(), INTERVAL 100 YEAR) AS inicio, CONCAT(SUBSTR(NOW(),1,8),'01') AS minimo, DATE_FORMAT(DATE_ADD(now(),INTERVAL -1 DAY),'%Y-%m-%d') as ayer, DATE_SUB(NOW(), INTERVAL 7 DAY) AS sietedias, DATE_SUB(NOW(), INTERVAL 1 DAY) AS ayer, DATE_SUB(NOW(), INTERVAL 30 DAY) AS treintadias";

$stmt=$db_con->prepare($sqlf);
$stmt->execute();
$fechas=$stmt->fetch(PDO::FETCH_ASSOC);
// echo '<input type="hidden" name="date3" id="date3" value="'.convertir_fechadmy($nacafi).'"/>';
?>

<!--
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_date3" 
   >
   <php
     echo convertir_fechadmy($nacafi); ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "date3",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_date3",       // ID of the span where the date is to be shown
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
-->

            <div class="form-group">
                <input type="text" name="daterange" id="daterange" value="01/01/2015 - 01/31/2015" />

                <script type="text/javascript">
                $(function() {
                    $('input[name="daterange"]').daterangepicker(
                    {
                        "singleDatePicker": true,
//                        "timePicker": false,
//                        "timePicker24Hour": false,
                        // timePickerIncrement: 10,
                        "applyLabel": "Guardar",
                        "cancelLabel": "Cancelar",
//                        "fromLabel": "Desde",
//                        "toLabel": "Hasta",
                        locale: {
                            format: 'YYYY-MM-DD HH:mm',
                        daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi','Sa'],
                        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                        customRangeLabel: 'Personalizado',
                        applyLabel: 'Aplicar',
                        fromLabel: 'Desde',
                        toLabel: 'Hasta',
                    },
                        "startDate": "<?php echo $fechas['hoy']?>",
                        "endDate": "<?php echo $fechas['hoy']?>", 
                        "minDate": "<?php echo $fechas['inicio']?>",
                        "maxDate": "<?php echo $fechas['hoy']?>" 
                    }
                    );
                });
                </script>

<?php
echo '</td><td>';
$elparentesco=$parafi;
echo '<select name="parafi" size="1" tabindex="6">';
$sql="select cvalor from ".$_SESSION['bdd']."configura where cparametro='Parentesco' order by cvalor";
$resultado = $db_con->prepare($sql);
$resultado->execute();
while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
	echo '<option value="'.$fila2['cvalor'].'" '.(($elparentesco==$fila2['cvalor'])?'selected':'').'>'.$fila2['cvalor'].'</option>';}
echo '</select> '; 
echo '</td><td>';
	$elsexo=$sexo;
	echo '<input type="radio" name="optsexo" value="Masculino" ';
	if ($elsexo == "Masculino") echo " checked";
	echo '/>Masculino';
	echo '<input type="radio" name="optsexo" value="Femenino"';
	if ($elsexo == "Femenino") echo " checked";
	echo '/>Femenino</td>';

//$activar=' ';
// if  (($herafi == 1)) {$activar='checked="checked"'; } else { $activar = ' '; }
// value="<?php echo $herafi;>" 
// echo '<input name="herafi" type="checkbox" id="herafi" tabindex="7" '. $activar.' /> ';
/*
$activar=' ';
if (($svafi == 1)) {$activar='checked="checked"'; $svafi=1; } else { $activar = ' '; $svafi=0; }
echo '<td><input name="svafi" type="checkbox" value="1" id="svafi" tabindex="9" '.$activar.' /> </td>';
*/
echo '</tr>';
}

?>


