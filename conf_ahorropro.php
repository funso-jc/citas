<?php
include("head.php");
include("paginar.php");
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
if ($accion == 'Anadir') 
	$onload="onload=\"foco('eltipo')\"";
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

if (($accion == "Anada")) {	// muestra datos para prestamo
	$vdesde=convertir_fecha($_POST['vdesde']);
	$vhasta=convertir_fecha($_POST['vhasta']);
	$montoins=($_POST['montoins']);
	$cxcobrar=($_POST['cxcobrar']);
	$cinscrip=($_POST['cinscrip']);
	$cpatri=($_POST['cpatri']);
	$cupos=($_POST['cupos']);
	$prestamo=($_POST['prestamo']);
	$cuotas=($_POST['cuotas']);
	$sql="insert into ".$_SESSION['bdd']."_sgcafapc (
	serie, vdesde, vhasta, montoins, cxcobrar, cinscrip, cpatri, cupos, prestamo, cuotas) values (
	'$codigo', '$vdesde', '$vhasta', '$montoins', '$cxcobrar', '$cinscrip', '$cpatri', '$cupos', '$prestamo',
	'$cuotas')";
	echo '<div id="div1">';
//	echo $sql;
	$result = mysql_query($sql) or die ('Error 360-2 <br>'.$sql.'<br>'.mysql_error());
	echo '</div>';
	$accion='';
} 	// fin de ($accion == "Anada")

if (($accion == "Modifica")) {	// muestra datos para prestamo
	$vdesde=convertir_fecha($_POST['vdesde']);
	$vhasta=convertir_fecha($_POST['vhasta']);
	$montoins=($_POST['montoins']);
	$cxcobrar=($_POST['cxcobrar']);
	$cinscrip=($_POST['cinscrip']);
	$cpatri=($_POST['cpatri']);
	$cupos=($_POST['cupos']);
	$prestamo=($_POST['prestamo']);
	$cuotas=($_POST['cuotas']);
/*
	$genera_com=isset($_POST['genera_com']);
	$restar_otros=isset($_POST['restar_otros']);
	$incluir_otros=isset($_POST['incluir_otros']);
	$inicial=isset($_POST['inicial']);
*/
	$sql="update ".$_SESSION['bdd']."_sgcafapc set vdesde='$vdesde', vhasta='$vhasta',
	montoins='$montoins', cxcobrar='$cxcobrar', cinscrip='$cinscrip',
	cpatri='$cpatri', cupos='$cupos', prestamo='$prestamo', 
	cuotas='$cuotas' where (serie ='$codigo')";
	echo '<div id="div1">';
//	echo $sql;
	$result = mysql_query($sql) or die ('Error 360-2 <br>'.$sql.'<br>'.mysql_error());
	echo '</div>';
	$accion='';
} 	// fin de ($accion == "Anada")


//----------------------------
if (!$accion)  {
	$ord = $_GET['ord'];
	if (!$ord) $ord='serie';
	$conta = $_GET['conta'];
	if (!$_GET['conta']) {
		$conta = 1;
	}
	$sql = "SELECT COUNT(serie) AS cuantos FROM ".$_SESSION['bdd']."_sgcafapc";
	$rs = mysql_query($sql);
	$row= mysql_fetch_array($rs);
	$numasi = $row[cuantos]; 

	$sql="SELECT * FROM ".$_SESSION['bdd']."_sgcafapc ORDER BY $ord "." LIMIT ".($conta-1).", 15";
	$rs = mysql_query($sql);
	echo '[ <a href="conf_ahorropro.php?accion=Anadir"> Nueva Configuracion </a> ]';
	echo "<table class='basica 100 hover' width='750'><tr>";
	echo '<th colspan="2"></th><th width="100">Serie<br>';
	echo '</th><th width="60">Vigente Desde</th><th width="40">Vigente Hasta</th><th width="40">Inscripcion</th><th width="100">Nro Cupos</th><th width="80">Cta Inscripcion</th><th width="80">Cta x Cobrar</th><th width="80">Cta Patrimonio</th><th width="80">Prestamo Bs</th><th width="80">Cuotas</th></tr>';

	if (pagina($numasi, $conta, 15, "Configuracion Ahorro Programado", $ord)) {$fin = 1;}
// 		bucle de listado
	while($row=mysql_fetch_assoc($rs)) {
		echo "<tr>";

//		echo "<td class='centro'><a href='extractoctas3.php?cuenta=".trim($row['cuent_pres']).'-'.substr(trim($row['codsoc_sdp']),1,4)."&datos=no&'><img src='imagenes/page_wizard.gif' width='16' height='16' border='0' /></a></td>";
		echo "<td class='centro'><a href='conf_ahorropro.php?accion=Ver&elcodigo=".trim($row['serie'])."'><img src='imagenes/page_user_dark.gif' width='16' height='16' border='0' title='Consultar' alt='Consultar'/></a></td>";
		echo "<td class='centro'><a href='conf_ahorropro.php?accion=Modificar&elcodigo=".trim($row['serie'])."'><img src='imagenes/16-em-pencil.png' width='16' height='16' border='0' title='Editar' alt='Editar'/></a></td>";
		echo "<td class='centro'>";
		echo $row['serie']."</td>";
		echo "<td class='centro'>".convertir_fechadmy($row['vdesde'])."</td>";
		echo "<td class='centro'>".convertir_fechadmy($row['vhasta'])."</td>";
		echo "<td align='right'>".number_format($row['montoins'],2,'.',',')."</td>";
		echo "<td align='right'>".number_format($row['cupos'],0,'.',',')."</td>";
		echo "<td class='centro'>".$row['cxcobrar']."</td>";
		echo "<td class='centro'>".$row['cinscrip']."</td>";
		echo "<td class='centro'>".$row['cpatri']."</td>";
		echo "<td align='right'>".number_format($row['prestamo'],2,'.',',')."</td>";
		echo "<td align='right'>".number_format($row['cuotas'],0,'.',',')."</td>";
/*
		echo "<td class='centro'><input type='checkbox' ".($row['retab_pres']?'checked ':'')." disabled='true' ></td>";
		echo "<td class='centro'><input type='checkbox' ".(($row['renovacion']>3)?'checked ':'')." disabled='true' ></td>";
		echo "<td class='centro'><input type='checkbox' ".(!$row['dcto_sem']?'checked ':'')." disabled='true' ></td>";
*/
			echo "</tr>";
		}

		echo "</table>";
		pagina($numasi, $conta, 15, "Configuracion Ahorro Programado", $ord);
}	// fin de (!$accion ) 

if ($accion == 'Ver') {
	echo "<div align='center' id='div1'>";
	$mostrarregresar=1;
	$codigo=$_GET['elcodigo'];
	mostrar_tipo_prestamo($codigo,$accion);
	echo "</div>";
}	
else $mostrarregresar=0; // fin de ($accion == 'Ver')
if (($accion == "Anadir")) {	// muestra datos para prestamo
	echo '<div id="div1">';
	echo "<form enctype='multipart/form-data' action='conf_ahorropro.php?accion=Anada' name='form1' method='post' onsubmit='return valtipre(form1)'>";
	$codigo=nuevo_codigo();
	mostrar_tipo_prestamo($codigo,$accion);
//	echo "<input type = 'submit' value = 'Grabar Datos'></form>\n"; 
	echo '</div>';
} 	// fin de ($accion == "Anadir")

if (($accion == "Modificar")) {	// muestra datos para prestamo
	echo '<div id="div1">';
	echo "<form enctype='multipart/form-data' action='conf_ahorropro.php?accion=Modifica' name='form1' method='post' onsubmit='return valtipre(form1)'>";
	$codigo=$_GET['elcodigo'];
	mostrar_tipo_prestamo($codigo,$accion);
	echo '</div>';
} 	// fin de ($accion == "Modifcar")


		
if ($mostrarregresar==1) { // ($accion == "Buscar") or ($accion == "Ver") or ($accion="EscogePrestamo")) {
	echo '<form enctype="multipart/form-data" name="formdepie" method="post" action="conf_ahorropro.php?accion=">';
	echo '<div style="clear:both"></div>';
	echo '<p /><div class="noimpri" style="clear:both;text-align:center">';
	echo '<input type="submit" name="boton" value="regresar" tabindex="3">';
	echo '</div>';
	echo '</form>';
}
else 
//	include("pie.php");
?>
</body></html>


<?php


function mostrar_tipo_prestamo($codigo,$accion)
{
	$deci=$_SESSION['deci'];
	$sep_decimal=$_SESSION['sep_decimal'];
	$sep_miles=$_SESSION['sep_miles'];
	$sql_360="select * from ".$_SESSION['bdd']."_sgcafapc where serie='$codigo'";
//	echo $sql_360;
	$a_360=mysql_query($sql_360);
	$r_360=mysql_fetch_assoc($a_360);
	echo "<input type = 'hidden' value ='".$codigo."' name='codigo'>";
	if ($accion == 'Ver') {
		echo '<fieldset><legend>Configuracion Ahorro Programado '. ' / Serie: '.$r_360['serie'].'</legend>';
		echo '<table class="basica 100 hover" width="700" border="1">';
	}
	else { 
		echo '<fieldset><legend>Nueva Configuracion Ahorro Programado / Serie: '.$codigo.'</legend>';
		echo '<table class="basica 100 hover" width="900px" border="1">';
		echo '<tr>';
		echo '<td colspan="6" width="200px">Vigente desde el ';
		// </td><td colspan="1" width="180px" align="left">';
		// <input type="text" name="vdesde" id="vdesde" value="'.$r_360['vdesde'].'" maxlength="12" size="12">*';
	}
	?>
	<input type="hidden" name="vdesde" id="vdesde" value=" <?php  echo convertir_fechadmy($r_360['vdesde']); ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_lafecharetiro" 
   ><?php  echo convertir_fechadmy($r_360['vdesde']); ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "vdesde",     // id of the input field
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

		echo 'hasta el ';
//		echo '<td width="200px">Vigente hasta </td><td colspan="1" width="180px" align="left">';
		// <input type="text" name="vhasta" id="vhasta" value="'.$r_360['vhasta'].'" maxlength="12" size="12">*';
	?>
	<input type="hidden" name="vhasta" id="vhasta" value=" <?php  echo convertir_fechadmy($r_360['vhasta']); ?>"/>
   <span style="background-color: #ff8; cursor: default;"
         onmouseover="this.style.backgroundColor='#ff0';"
         onmouseout="this.style.backgroundColor='#ff8';"
         id="show_lafecharetir2" 
   ><?php  echo convertir_fechadmy($r_360['vhasta']); ?></span> *
<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "vhasta",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_lafecharetir2",       // ID of the span where the date is to be shown
        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 

// desactivacion de 18 años pa' tras


		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000))
							  (date.getTime() > today.getTime()+(1*24*60*60*1000)) 
							  // || date.getTime() > today.getTime()+(10*24*60*60*1000))	date.getDay() == 0 || 
							  ) ? true : false;  }
    });
</script>

<?php
	echo '</tr><tr>';
    echo '<td >Monto Inscripcion Bs </td><td align="right">';
	if ($accion=='Ver')
		echo number_format($r_360['montoins'],$deci,$sep_decimal,$sep_miles).'%</td>';
	else echo "<input type = 'text' size='12' maxlength='12' name='montoins' value ='".number_format($r_360['montoins'],2,'.','')."'>";
   echo '<td >Nro de Cupos</td><td align="left">';
	if ($accion=='Ver')
		echo number_format($r_360['cupos'],0,$sep_decimal,$sep_miles).'</td>';
	else {
		echo '<select id="cupos" name="cupos" size="1">';
		$maximo=52*6;
		$elmaximo=$maximo;
		if ($accion!='Anadir') $elmaximo=$r_360['cupos'];
		for ($laposicion=1;$laposicion <= $maximo;$laposicion++) {
			echo '<option value="'.$laposicion.'" '.($laposicion==$elmaximo?" selected ":"").'>'.$laposicion.' </option>'; }
		// 
		echo '</select>'; 
		}
	echo '</td>';
	echo '<td >Cupos Disponibles</td><td align="left">';
	echo number_format(($r_360['cupos']-$r_360['ultimo']),0,$sep_decimal,$sep_miles).'</td>';
	echo '</tr><tr>';

	echo '<td>Cuenta Contable Inscripcion</td><td>';
	if ($accion=='Ver') 
		echo "<a target=\"_blank\" href=\"extractoctas3.php?cuenta=".$r_360["cinscrip"]."&datos='no'\">".$r_360["cinscrip"]."</a>";
	else {
		echo "<input type='text' size='20' name='cinscrip' id='inputString' onKeyUp='lookup(this.value);' onBlur='fill();' value = '".$r_360["cinscrip"]."'; autocomplete='off'/>";
//	echo '<div class="suggestionsBox" id="suggestions" style="display: none;">';
		echo '<div class="suggestionsBox" id="suggestions" style="display: none; position: absolute; left: 80px; top: 300px; width: 300; height: 272px; z-index: 1; visibility: visible; overflow: visible"> ';
		echo '<img src="upArrow.png" style="position: relative; top: -0px; left: 70px; "  alt="upArrow" />';
		echo '<div class="suggestionList" id="autoSuggestionsList">';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
	echo '</td><td>Cuenta por Cobrar</td><td>';
	if ($accion=='Ver') 
		echo "<a target=\"_blank\" href=\"extractoctas3.php?cuenta=".$r_360["cxcobrar"]."&datos='no'\">".$r_360["cxcobrar"]."</a>";
	else {
		echo "<input type='text' size='20' name='cxcobrar' id='inputString2' onKeyUp='lookup2(this.value);' onBlur='fill2();' value ='".$r_360["cxcobrar"]."'; autocomplete='off'/>";
//	echo '<div class="suggestionsBox2" id="suggestions2" style="display: none;">';
		echo '<div class="suggestionsBox2" id="suggestions2" style="display: none; position: absolute; left: 180px; top: 300px; width: 300; height: 272px; z-index: 1; visibility: visible; overflow: visible"> ';
		echo '<img src="upArrow.png" style="position: relative; top: -12px; left: 70px; "  alt="upArrow" />';
		echo '<div class="suggestionList2" id="autoSuggestionsList2">';
		echo '</div></div></div>';
	}
	echo '</td>';

	echo '<td>Cuenta de Patrimonio</td><td>';
	if ($accion=='Ver') 
		echo "<a target=\"_blank\" href=\"extractoctas3.php?cuenta=".$r_360["cpatri"]."&datos='no'\">".$r_360["cpatri"]."</a>";
	else {
		echo "<input type='text' size='20' name='cpatri' id='inputString6' onKeyUp='lookup6(this.value);' onBlur='fill6();' value = '".$r_360["cpatri"]."'; autocomplete='off'/>";
//	echo '<div class="suggestionsBox6" id="suggestions6" style="display: none;">';
		echo '<div class="suggestionsBox6" id="suggestions6" style="display: none; position: absolute; left: 80px; top: 300px; width: 800; height: 272px; z-index: 1; visibility: visible; overflow: visible"> ';
		echo '<img src="upArrow.png" style="position: relative; top: -0px; left: 70px; "  alt="upArrow" />';
		echo '<div class="suggestionList6" id="autoSuggestionsList6">';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
	echo '</tr>';

    echo '<td colspan="2">Monto en Bs del Prestamo </td><td align="right">';
	if ($accion=='Ver')
		echo number_format($r_360['prestamo'],$deci,$sep_decimal,$sep_miles).'%</td>';
	else echo "<input type = 'text' size='12' maxlength='12' name='prestamo'  value ='".number_format($r_360['prestamo'],2,'.','')."'>";

    echo '<td colspan="2">Nro.Maximo de Cuotas</td><td width="100px" align="left">';
	if ($accion=='Ver')
		echo number_format($r_360['cuotas'],0,$sep_decimal,$sep_miles).'</td>';
	else {
	echo '<select id="cuotas" name="cuotas" size="1">';
	$maximo=52*6;
	$elmaximo=$maximo;
	if ($accion!='Anadir') $elmaximo=$r_360['cuotas'];
	for ($laposicion=1;$laposicion <= $maximo;$laposicion++) {
		echo '<option value="'.$laposicion.'" '.($laposicion==$elmaximo?" selected ":"").'>'.$laposicion.' </option>'; }
		// 
	echo '</select>'; 
//	echo "<input type = 'text' size='12' maxlength='12' name='n_cuo_pres' tabindex='2' value ='".$r_360['n_cuo_pres']."'>";
	}
	echo '</td>';
	echo '</tr>';

	
/*

    echo '<td width="200px">Nro.Maximo de Cuotas</td><td width="100px" align="left">';
	if ($accion=='Ver')
		echo number_format($r_360['n_cuo_pres'],0,$sep_decimal,$sep_miles).'</td>';
	else {
	echo '<select id="n_cuo_pres" name="n_cuo_pres" size="1">';
	$maximo=52*6;
	$elmaximo=$maximo;
	if ($accion!='Anadir') $elmaximo=$r_360['n_cuo_pres'];
	for ($laposicion=1;$laposicion <= $maximo;$laposicion++) {
		echo '<option value="'.$laposicion.'" '.($laposicion==$elmaximo?" selected ":"").'>'.$laposicion.' </option>'; }
		// 
	echo '</select>'; 
//	echo "<input type = 'text' size='12' maxlength='12' name='n_cuo_pres' tabindex='2' value ='".$r_360['n_cuo_pres']."'>";
	}
    echo '<td width="200px">Tasa de Interes </td><td width="200px" align="right">';
	if ($accion=='Ver')
		echo number_format($r_360['i_max_pres'],$deci,$sep_decimal,$sep_miles).'%</td>';
	else echo "<input type = 'text' size='12' maxlength='12' name='i_max_pres' tabindex='3' value ='".number_format($r_360['i_max_pres'],2,'.','')."'>";
	echo '</tr>';

    echo '<tr><td>Nro.Maximo de Fiadores</td><td align="left">';
	if ($accion=='Ver')
		echo number_format($r_360['n_fia_pres'],0,$sep_decimal,$sep_miles).'</td>';
	else {
		echo '<select id="n_fia_pres" name="n_fia_pres" size="1">';
		$maximo=3;
		if ($accion!='Anadir') $elmaximo=$r_360['n_fia_pres'];
		for ($laposicion=0;$laposicion <= $maximo;$laposicion++) {
			echo '<option value="'.$laposicion.'" '.($laposicion==$elmaximo?" selected ":"").'>'.$laposicion.' </option>'; }
			// 
		echo '</select>'; 
//		echo "<input type = 'text' size='12' maxlength='12' name='n_fia_pres' tabindex='4' value ='".$r_360['n_fia_pres']."'>";
		}
    echo '<td>Afecta Disponibilidad</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['retab_pres']?'checked ':'')." disabled='true' ></td>";
	else // echo "<input type = 'checkbox' name='retab_pres' tabindex='5' value ='".($accion=='Anadir'?"1   'checked'":$r_360['retab_pres'])."' ".(($r_360['retab_pres'])?'checked ':'')." >";
	echo "<input type = 'checkbox' name='retab_pres' tabindex='5' value =".$r_360['retab_pres']." ".(($r_360['retab_pres'])?'checked ':'')." >";

    echo '<td width="200px">Nro.de Cuotas p/renovar</td><td align="left">';
	if ($accion=='Ver')
		echo number_format($r_360['renovacion'],0,$sep_decimal,$sep_miles).'</td>';
	else {
		if ($accion!='Anadir') $elmaximo=$r_360['renovacion'];
		echo '<select id="renovacion" name="renovacion" size="1">';
		$maximo=52*6;
		for ($laposicion=0;$laposicion <= $maximo;$laposicion++) {
			echo '<option value="'.$laposicion.'" '.($laposicion==$elmaximo?" selected ":"").'>'.$laposicion.' </option>'; }
		echo '</select>'; 
		}
//	else echo "<input type = 'text' size='12' maxlength='12' name='renovacion' tabindex='6' value ='".$r_360['renovacion']."'>";
	echo '</tr><tr>';
    echo '<td>Deposito al Banco</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['albanco']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='albanco' tabindex='5' value ='".($accion=='Anadir'?"1   'checked'":$r_360['albanco'])."' ".(($r_360['albanco']==1)?'checked ':'')." >";

    echo '<td>Nro.de Meses en la Caja</td><td align="left">';
	if ($accion=='Ver')
		echo number_format($r_360['tiempo'],0,$sep_decimal,$sep_miles).'</td>';
	else {
		if ($accion!='Anadir') $elmaximo=$r_360['tiempo'];
		echo '<select id="tiempo" name="tiempo" size="1">';
		$maximo=36;
		for ($laposicion=1;$laposicion <= $maximo;$laposicion++) {
			echo '<option value="'.$laposicion.'"'.($laposicion==$elmaximo?" selected ":"").' >'.$laposicion.' </option>'; }
		echo '</select>'; 
		}
//	else echo "<input type = 'text' size='12' maxlength='12' name='tiempo' tabindex='8' value ='".$r_360['tiempo']."'>";
    echo '<td >Aprobacion Directa</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($row['aprobar']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='aprobar' tabindex='5' value ='".($accion=='Anadir'?"1   'checked'":$r_360['aprobar'])."' ".(($r_360['aprobar'])?'checked ':'')." >";
	echo '</tr><tr>';

    echo '<td>Descuentos Consecutivos</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['dcto_sem']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='dcto_sem' tabindex='10' value ='".($accion=='Anadir'?"1   'checked'":$r_360['dcto_sem'])."' ".(($r_360['dcto_sem'])?'checked ':'')." >";
    echo '<td >Int.Desc.Anticipado</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['int_dif']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='int_dif' tabindex='11' value ='".($accion=='Anadir'?"1   'checked'":$r_360['int_dif'])."' ".(($r_360['int_dif'])?'checked ':'')." >";

    echo '<td>Varios Vigentes</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['masdeuno']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='masdeuno' tabindex='12' value ='".($accion=='Anadir'?"1   'checked'":$r_360['masdeuno'])."' ".(($r_360['masdeuno'])?'checked ':'')." >";
	echo '</tr><tr>';

    echo '<td >Generar Planillas</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['genera_pl']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='genera_pl' tabindex='13' value ='".($accion=='Anadir'?"1   'checked'":$r_360['genera_pl'])."' ".(($r_360['genera_pl'])?'checked ':'')." >";

    echo '<td>Nro.de Copias</td><td align="left">';
	if ($accion=='Ver')
		echo number_format($r_360['copias'],0,$sep_decimal,$sep_miles).'</td>';
	else {
		echo '<select id="copias" name="copias" size="1">';
		$maximo=5;
		for ($laposicion=1;$laposicion <= $maximo;$laposicion++) {
			echo '<option value="'.$laposicion.($laposicion==$maximo?" selected ":"").'" >'.$laposicion.' </option>'; }
		echo '</select>'; 
		}
//	else echo "<input type = 'text' size='12' maxlength='12' name='copias' tabindex='14' value ='".$r_360['copias']."'>";
    echo '<td >Tope por U.T.</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['tope_ut']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='tope_ut' tabindex='15' value ='".($accion=='Anadir'?"1   'checked'":$r_360['tope_ut'])."' ".(($r_360['tope_ut'])?'checked ':'')." >";
	echo '</tr><tr>';

    echo '<td>Nro.de U.T.</td><td align="left">';
	if ($accion=='Ver')
		echo number_format($r_360['factor_ut'],$deci,$sep_decimal,$sep_miles).'</td>';
	else echo "<input type = 'text' size='12' maxlength='12' name='factor_ut' tabindex='16' value =".number_format($r_360['factor_ut'],$deci,$sepdecimal,'').">";
    echo '<td >Tope en Monto</td><td align="left">';
	if ($accion=='Ver')
		echo number_format($r_360['tope_monto'],$deci,$sep_decimal,$sep_miles).'</td>';
	else echo "<input type = 'text' size='12' maxlength='12' name='tope_monto' tabindex='17' value =".number_format($r_360['tope_monto'],$deci,$sepdecimal,'').">";

    echo '<td>Genera Comprobante</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['genera_com']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='genera_com' tabindex='18' value ='".($accion=='Anadir'?"1   'checked'":$r_360['genera_com'])."' ".(($r_360['genera_com'])?'checked ':'')." >";

	echo '</tr><tr>';
    echo '<td >Restar Otros Pres.Renov.</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['restar_otros']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='restar_otros' tabindex='19' value ='".($accion=='Anadir'?"1   'checked'":$r_360['restar_otros'])."' ".(($r_360['restar_otros'])?'checked ':'')." >";

    echo '<td>Incluir este en renovaciones</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['incluir_otros']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='incluir_otros' tabindex='20' value ='".($accion=='Anadir'?"1   'checked'":$r_360['incluir_otros'])."' ".(($r_360['incluir_otros'])?'checked ':'')." >";
    echo '<td >Permitir Inicial</td><td align="left">';
	if ($accion=='Ver')
		echo "<input type='checkbox' ".($r_360['inicial']?'checked ':'')." disabled='true' ></td>";
	else echo "<input type = 'checkbox' name='inicial' tabindex='21' value ='".($accion=='Anadir'?"1   'checked'":$r_360['inicial'])."' ".(($r_360['inicial'])?'checked ':'')." >";
	echo '</tr><tr>';

    echo '<td >Descripcion Corta</td><td align="left">';
	if ($accion=='Ver')
		echo number_format($r_360['desc_cor'],0,$sep_decimal,$sep_miles).'</td>';
	else echo "<input type = 'text' size='10' maxlength='10' name='desc_cor' tabindex='20' value ='".$r_360['desc_cor']."'>*";
    echo '<td >Tipo de Registro</td><td align="left">';
	if ($accion=='Ver')
		echo $r_360['tipo'].'</td>';
	else {
			$eltipo=$r_360['tipo'];
			echo '<select name="tipo" size="1">';
			$sql="select nombre from ".$_SESSION['bdd']."_sgcaf000 where tipo='TipoReg' order by nombre";
			$resultado=mysql_query($sql);
			while ($fila2 = mysql_fetch_assoc($resultado)) {
				echo '<option value="'.$fila2['nombre'].'" '.(($eltipo==$fila2['nombre'])?'selected':'').'>'.$fila2['nombre'].'</option>';}
	 	echo '</select> '; 
	}

    echo '<td >Tipo de Interes</td><td colspan="1" align="left">';
	if ($accion=='Ver')
		echo $r_360['tipo_interes'].'</td>';
	else {
		$eltipo=$r_360['tipo_interes'];
		echo '<select name="tipo_interes" size="1">';
		$sql="select nombre from ".$_SESSION['bdd']."_sgcaf000 where tipo='TipoInt' order by nombre";
		$resultado=mysql_query($sql);
		while ($fila2 = mysql_fetch_assoc($resultado)) {
			echo '<option value="'.$fila2['nombre'].'" '.(($eltipo==$fila2['nombre'])?'selected':'').'>'.$fila2['nombre'].'</option>';}
 	echo '</select> '; 
	}
	echo '</tr><tr>';

    echo '<td >Garantia</td><td colspan="1" align="left">';
	if ($accion=='Ver')
//		echo ($r_360['garantia']==1?'Disponibilidad':($r_360['garantia']==2?'Fiador(es)':'Hipoteca')).'</td>';
	{
		$sql="select nombre from ".$_SESSION['bdd']."_sgcaf000 where tipo='garantia' and substr(nombre,1,1)='".$r_360['garantia']."'";
		$resultado=mysql_query($sql);
		$fila2 = mysql_fetch_assoc($resultado);
		echo $fila2['nombre'];
	}
	else {
		$eltipo=$r_360['garantia'];
		echo '<select name="garantia" size="1">';
		$sql="select nombre from ".$_SESSION['bdd']."_sgcaf000 where tipo='garantia' order by nombre";
		$resultado=mysql_query($sql);
		while ($fila2 = mysql_fetch_assoc($resultado)) {
			echo '<option value="'.$fila2['nombre'].'" '.(($eltipo==$fila2['nombre'])?'selected':'').'>'.$fila2['nombre'].'</option>';}
 	echo '</select></td> ';
	}
	
	echo '<td>Mayor de Préstamos</td><td>';
	if ($accion=='Ver') 
		echo "<a target=\"_blank\" href=\"extractoctas3.php?cuenta=".$r_360["cuent_pres"]."&datos='no'\">".$r_360["cuent_pres"]."</a>";
	else {
		echo "<input type='text' size='20' tabindex='25' name='cuent_pres' id='inputString' onKeyUp='lookup(this.value);' onBlur='fill();' value = '".$r_360["cuent_pres"]."'; autocomplete='off'/>";
//	echo '<div class="suggestionsBox" id="suggestions" style="display: none;">';
		echo '<div class="suggestionsBox" id="suggestions" style="display: none; position: absolute; left: 80px; top: 300px; width: 300; height: 272px; z-index: 1; visibility: visible; overflow: visible"> ';
		echo '<img src="upArrow.png" style="position: relative; top: -0px; left: 70px; "  alt="upArrow" />';
		echo '<div class="suggestionList" id="autoSuggestionsList">';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
	echo '</td><td>Intereses Diferidos</td><td>';
	if ($accion=='Ver') 
		echo "<a target=\"_blank\" href=\"extractoctas3.php?cuenta=".$r_360["cuent_int"]."&datos='no'\">".$r_360["cuent_int"]."</a>";
	else {
		echo "<input type='text' size='20' tabindex='26' name='cuent_int' id='inputString2' onKeyUp='lookup2(this.value);' onBlur='fill2();' value ='".$r_360["cuent_int"]."'; autocomplete='off'/>";
//	echo '<div class="suggestionsBox2" id="suggestions2" style="display: none;">';
		echo '<div class="suggestionsBox2" id="suggestions2" style="display: none; position: absolute; left: 180px; top: 300px; width: 300; height: 272px; z-index: 1; visibility: visible; overflow: visible"> ';
		echo '<img src="upArrow.png" style="position: relative; top: -12px; left: 70px; "  alt="upArrow" />';
		echo '<div class="suggestionList2" id="autoSuggestionsList2">';
		echo '</div></div></div>';
	}
	echo '</td><tr>';

	echo '<td>Intereses Ganados</td><td>';
	if ($accion=='Ver') 
		echo "<a target=\"_blank\" href=\"extractoctas3.php?cuenta=".$r_360["otro_int"]."&datos='no'\">".$r_360["otro_int"]."</a>";
	else {
		echo "<input type='text' size='20' tabindex='27' name='otro_int' id='inputString6' onKeyUp='lookup6(this.value);' onBlur='fill6();' value = '".$r_360["otro_int"]."'; autocomplete='off'/>";
//	echo '<div class="suggestionsBox6" id="suggestions6" style="display: none;">';
		echo '<div class="suggestionsBox6" id="suggestions6" style="display: none; position: absolute; left: 80px; top: 300px; width: 800; height: 272px; z-index: 1; visibility: visible; overflow: visible"> ';
		echo '<img src="upArrow.png" style="position: relative; top: -0px; left: 70px; "  alt="upArrow" />';
		echo '<div class="suggestionList6" id="autoSuggestionsList6">';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
	echo '</tr>';
*/
	echo '</table>';
	if ($accion != 'Ver') echo "<input type = 'submit' value = 'Grabar Datos'></form>\n"; 
//	echo '<br><br><br><br><br>';
	echo '</fieldset>';
}	

function nuevo_codigo()
{
	$sql="SELECT serie FROM ".$_SESSION['bdd']."_sgcafapc ORDER BY serie DESC limit 1";
	$resulta2=mysql_query($sql);
	$fila2 = mysql_fetch_assoc($resulta2);
	$ultimo=$fila2['serie'];
	$digitos=3;
	$ultimo++;
//	$ultimo=ceroizq($ultimo,$digitos);
	return $ultimo;
}

/*

CREATE TABLE IF NOT EXISTS `cacpcel_sgcafapc` (
  `serie` varchar(1) NOT NULL,
  `vdesde` date NOT NULL,
  `vhasta` date NOT NULL,
  `montoins` decimal(12,2) NOT NULL,
  `cxcobrar` varchar(30) NOT NULL,
  `cinscrip` varchar(30) NOT NULL,
  `cpatri` varchar(30) NOT NULL,
  `cupos` int(3) NOT NULL,
  `ultimo` int(3) NOT NULL,
  `prestamo` decimal(12,2) NOT NULL,
  `cuotas` int(3) NOT NULL,
  UNIQUE KEY `serie` (`serie`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
INSERT INTO `cacpcelo_sica`.`cacpcel_sgcafapc` (`serie`, `vdesde`, `vhasta`, `montoins`, `cxcobrar`, `cinscrip`, `cpatri`, `cupos`, `ultimo`, `prestamo`, `cuotas`) VALUES ('C', '2009-01-07', '2010-06-30', '0', '1-02-01-01-03-02', '2-01-01-99-99-01-0006', '3-01-04-02-01-01', '100', '1', '53700', '72');
https://goodlybit.com

  */
?>
