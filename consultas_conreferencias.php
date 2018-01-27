<?php
include("head.php");
include("paginar.php");

if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
?>

<!--
<style type="text/css">
 * {
  margin: 0;
  padding: 0;
 }
 
 body {
 font-family: Georgia, "Times New Roman", Times, serif; 
 font-size: 2em;
  background: #9fffff;
  color: #0000ff;
 }
 
 a {
  color: #F00;
 }
 
 /* base semi-transparente */
    .overlay{
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #000;
        z-index:1001;
  opacity:.75;
        -moz-opacity: 0.75;
        filter: alpha(opacity=75);
    }
 
    /* estilo para la ventana modal */
    .modal {
        display: none;
        position: absolute;
        top: 25%;
        left: 25%;
        width: 50%;
        height: 50%;
        padding: 16px;
        background: #fff;
  color: #333;
        z-index:1002;
        overflow: auto;
    }
    </style>
  -->
	
	

<script language="JavaScript" type="text/javascript" src="llamarpcteconsultorio.js"></script>
<script language="JavaScript" type="text/javascript" src="examenes.js"></script>
<script type="text/javascript" type="text/javascript" src="select_dependientes_3_niveles.js"></script>
<script type="text/javascript" type="text/javascript" src="ajx_examenes.js"></script>

<style type="text/css">
       .scrollWrapper   {
            width:250px;height:500px;
            overflow:hidden;
            border:2px solid #00f;
            font-family:Arial;font-size:0.8em;
			left: 900px;
			top: 150px;

			position:absolute;
			padding-right:10px;
			margin-right:10px;			
			
        }

        .scrollTitle {
            background-color:#00f;
            color:#fff;
            padding:5px;
            font-weight:bold;
            text-align:center;
        }
        #scroll    {
            position:relative;
            width:auto;
            margin:1px;
            z-index: -1;
            padding:5px;
        }
        #scroll .title  {font-weight:bold;margin-top:20px;}
</style>
<style type="text/css">

#divexamenes{background-color:#CCC;
width:500px;
margin:20px auto;
padding:10px 10px 10px 10px;
cursor:pointer;
}
</style>
<style type="text/css">
#divdatos{background-color:#CCC;
top: 0px;
width:830px;
height:300px;
<!-- 
margin:20px auto;
padding:10px 10px 10px 10px;
-->
cursor:pointer;
}
</style>

<script type="text/javascript">
        // determina el numero de pixeles que se moveran las noticias para
        // cada iteracion en milisegundos de "speedjump"
        var scrollspeed=1;
        // determina la velocidad en milisgundos
        var speedjump=30;
        // segundos antes de empezar el movimiento
        var startdelay= 3;
        // posicion inicial superior en pixeles para cuando inicia
        var topspace=-10;
        // altura del marco donde se mostraran las noticias
        // Si se modifica la altura del contenedor de las noticas hay que
        // modificar tambien este valor
        var frameheight=370;

        // variable temporal que variara su valor en función de si estan las
        // noticias en movimiento o paradas
        current=scrollspeed;
        /**
         * Inicio del scroll
         * Esta función es llamada en el body de la pagina.
         * Tiene que recibir el id del scroll
         */

        function scrollStart()
        {
            dataobj = document.getElementById("scroll");
            // cogemos la altura maxima de la capa de las noticias
            alturaNoticias = dataobj.offsetHeight;
            // posicionamos la capa del scroll en su posicion inicial
            dataobj.style.top = topspace + 'px';
            setTimeout("scrolling()", (startdelay * 1000));
        }


        /**
         * Funcion que realiza el movimiento
         */

        function scrolling() {
            // Restamos a la propiedad top de la capa el valor en pixeles
            // establecido en la variable "scrollspeed", para hacer el
            // movimiento hacia arriba.
            dataobj.style.top = parseInt(dataobj.style.top) - scrollspeed + 'px';
            // Si la capa ha sobrepasado la altura del area por donde se muestran
            // las noticias ("alturaNoticias")
            if (parseInt(dataobj.style.top) < alturaNoticias * (-1))
            {
                // Posicionamos la capa en la parte inferior del recuadro, para
                // que simule que vienen las noticias de la parte inferior
                dataobj.style.top = frameheight + 'px';
                setTimeout("scrolling()", 0);
            }else{
                setTimeout("scrolling()", speedjump);
            }
        }
</script>


<body > 


<?php
 // onLoad="scrollStart();">
//$_SESSION['numeroregistro']='';
$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;
include("menusizda.php");
$cedula = $_GET['cedula'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
$ahora=ahora();
if (!$_POST['medico'])
{
	echo "<form action='consultas.php?accion=seleccion' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
	echo '<td>M&eacute;dico </td><td><select id="medico" name="medico" size="1">';
	$sql="select nombre, codigomedico from ".$_SESSION['bdd']."_internos where status = 1 order by nombre "; // and atendiendo = 0 
	$resultado=mysql_query($sql);
	while ($fila2 = mysql_fetch_assoc($resultado)) 
	{
		echo '<option value="'.$fila2['codigomedico'].'" '.(($elstatus==$fila2['nombre'])?'selected':'').'>'.$fila2['nombre'].'</option>';}
	echo '</select></td>'; 
	echo '<tr><td colspan="2" align="center">';
	echo "<input type = 'submit' id='seleccionar' name='seleccionar' value = 'Seleccionar' >"; // disabled
	echo '</td></tr>';
	echo "</form>";
}
echo 'medico '.$_POST['medico'];
if (($_POST['medico']) and (!$_SESSION['numeroregistro']))
{
	// boton para liberar el medico
	
	echo 'falta boton para descanso del medico <br>';
	echo 'falta boton para dejar en suspenso al paciente ';
	$sql="UPDATE ".$_SESSION['bdd']."_internos set atendiendo = 1 where codigomedico = '".$_POST['medico']."'";
	$resultado=mysql_query($sql);
	$_SESSION['codigomedico']=$_POST['medico'];
	$sql="select * from ".$_SESSION['bdd']."_internos where codigomedico = '".$_POST['medico']."'";
	$resultadodr=mysql_query($sql);
	$filadr=mysql_fetch_assoc($resultadodr);
/*
	echo "<form action='consultas.php?accion=llamar' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
	echo "<input type = 'submit' id='llamar' name='llamar' value = 'Llamar a siguiente paciente' >"; // disabled
	echo '</td></tr>';
	echo "</form>";
*/
	echo '<div id="imagenbusqueda"></div>';
	echo '<form id="llamar" name="llamar" action="" onsubmit="LlamarPacientealConsultorio(); return false">';
	echo '<div id="resultado">';
	$_SESSION['nombredr']=$filadr['nombre'];
	echo "<table class='basica 100 hover' width='60%'><tr>";
	echo '<td>Buen d&iacutea Dr(a) '.$filadr['nombre'].'</td>';
	echo '<input type="hidden" name="codigomedico" id="codigomedico" value="'.$_POST['medico'].'">';
	echo '<input type="hidden" name="numeroregistro" id="numeroregistro" value="">';
	echo '<td><input type="submit" value="Llamar al Siguiente Paciente" /></td></tr>';
	echo '<tr><td>Titular<input type"text" id="cedulatitular" name="cedulatitular" value="" readonly></td>';
	echo '<td>Nombre Titular<input type"text" id="nombretitular" name="nombretitular" size="50" value="" readonly></td></tr>';
	echo '<tr><td>Beneficiario <input type"text" id="cedulabeneficiario" name="cedulabeneficiario" value="" readonly></td>';
	echo '<td>Nombre Beneficiario <input type"text" id="nombrebeneficiario" name="nombrebeneficiario" size="50" value="" readonly></td></tr>';
	echo '<input type="hidden" name="existe" id="existe" value="0">';
	echo '<input type="hidden" name="medico" id="medico" value="'.$_POST['medico'].'">';
	echo '<tr><td>Motivo de la Cita </td><td><input type"text" id="motivo" name="motivo" value="" readonly></td></tr>';
	echo '<tr><td>Observaciones</td><td> <textarea id="observaciones" name="observaciones" row="4" cols="50" value="." readonly></textarea></td></tr>';
	echo '</table>';
	echo '</div>';
//	echo '<div id="divexamenes" style="text-align:center; margin: 0 auto;">';
	echo '<table>';
	echo '</form>';
	botones(false);
/*	
	echo '<div id="divdatos" top=400px; style="text-align:center; margin: 0 auto;">'; // visibility: hidden
	echo ';;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;';
	echo '</div>';
*/
}

///////////////////// guardar
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['Guardard']))
{
	$primeravez=isset($_POST['primeravez']);
	$sqli="INSERT INTO ".$_SESSION['bdd']."_diagnosticos (nrocon_diagnostico, descripcion_diag, observac_diagnos, ip, realizado, notifica, primeravez) VALUES 
	('".$_SESSION['numeroregistro']."', '$ddiagnostico', '$dobservacion', '$ip', '$ahora', '$notificacion', '$primeravez')";
	// echo $sqli;
	$resp=mysql_query($sqli) or die('Error en inclusion '.mysql_error().'<br>'.$sqli);
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['Guardars']))
{
	$sqli="INSERT INTO ".$_SESSION['bdd']."_signos (numcon_signos, peso, estatura, temperatura, presionart, pulso, tiposangre, observacion, ip, realizado) VALUES 
	('".$_SESSION['numeroregistro']."', '$peso', '$estatura', '$temperatura', '$presionart', '$pulso', '$tiposangre', '$dobservacion', '$ip', '$ahora')";
	// echo $sqli;
	$resp=mysql_query($sqli) or die('Error en inclusion '.mysql_error().'<br>'.$sqli);
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['Guardara']))
{
	$sqli="INSERT INTO ".$_SESSION['bdd']."_alergias (numcon_alergias, alergicoa, observacion, ip, realizado) VALUES 
	('".$_SESSION['numeroregistro']."', '$alergicoa', '$dobservacion', '$ip', '$ahora')";
	// echo $sqli;
	$resp=mysql_query($sqli) or die('Error en inclusion '.mysql_error().'<br>'.$sqli);
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['Guardarrec']))
{
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['Guardarref']))
{
//	echo '<div style="text-align: center; position: absolute; top: 350px; " id="divdatos">';
	for ($i=0;$i<$registros;$i++)		// no es necesarios revisar el check si aparece es porq estan seleccionados para hacer el asiento 
	{
		$variable='cancelar'.($i+1);
//		echo $$variable. 'variable '.$variable. '<br>';
		if (!empty($$variable)) 
		{
			$elid=$$variable;
			// ver el costo del examen 
			if ($tipo == 'Laboratorio')
			{
				$sql="select *, descripcion as examen from ".$_SESSION['bdd']."_costoslaboratorio where nroregistro = '".$elid."'";
				$result_costos=mysql_query($sql) or die(mysql_error().' '.$sql);
				$fila=mysql_fetch_assoc($result_costos);
				$monto = $fila['costo'];
			}	
			else 
			{
				$sql="select *, 'Su Especialidad' as examen  from ".$_SESSION['bdd']."_costos where idregistro = '".$elid."'";
				$result_costos=mysql_query($sql) or die(mysql_error().' '.$sql);
				$fila=mysql_fetch_assoc($result_costos);
				$monto = $fila['costo'];				
			}
//			echo $sql.'\n';
			$sql="INSERT INTO ".$_SESSION['bdd']."_referencias (numcon_referencia, codmed, observacion, realizarce, montoconsumo, cedulatitular, cedulabeneficiario, ip, realizado, tipo, registrooriginal, examende) VALUES 
			('".$_SESSION['numeroregistro']."', '$medico', '$dobservacion', '$realizarse', '$monto', '".$_SESSION['cedulatitular']."', '".$_SESSION['cedulabeneficiario']."', '$ip', '$ahora', '$tipo', '".$$variable."', '".$fila['examen']."')";
			$result_costos=mysql_query($sql) or die(mysql_error().' '.$sql);
		}
	}
//	echo '</div>';
}

if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['Guardarrep']))
{
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['Guardarscons']))
{
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['Guardarinf']))
{
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['Guardarord']))
{
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['Guardarh']))
{
}

///////////////////// fin guardar

if (($_POST['medico']) and ($_SESSION['numeroregistro']))
{
	include("mostrarpaciente.php");
//	include('diagnostico.php');
	botones(true);

/*
	echo '<tr align="center">';
//	echo '<form id="llamar2" name="llamar2" action="" onsubmit="examenes(\'diagnostico.php\'); return false">'; // 
	echo "<form action='consultas.php' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
//		echo '<div style="text-align: center; position: absolute; top: 400px; " id="losexamenes">';
		echo '<input type="hidden" name="medico" id="medico" value="'.$_POST['medico'].'">';
		echo '<td><input id="diagnostico" name="diagnostico" type="submit" value="Diagn&oacute;stico" /></td>';  // disabled
//		echo '</div>';
	echo '<td><input id="signos" name="signos" type="submit" value="Signos Vitales" /></td>';
	echo '<td><input id="recipe" name="recipe" type="submit" value="R&eacute;cipe"  /></td>';
	echo '<td><input id="alergias" name="alergias" type="submit" value="Alergias"  /></td>';
	echo '<td><input id="referencia" name="referencia" type="submit" value="Referencia"  /></td>';
	echo '<td><input id="reposo" name="reposo" type="submit" value="Reposo"  /></td>';
	echo '<td><input id="constancia" name="constancia" type="submit" value="Constancia"  /></td>';
	echo '<td><input id="informe" name="informe" type="submit" value="Informe"  /></td>';
	echo '<td><input id="orden" name="orden" type="submit" value="Orden Hospit."  disabled/></td>';
	echo '<td><input id="historia" name="historia" type="submit" value="Historia"  disabled/></td>';
	echo '</tr>';
	echo '</table>';
	echo '</form>';
*/


	// scroll
	echo '<div class="scrollWrapper" onMouseover="scrollspeed=0" onMouseout="scrollspeed=current">';
    echo '<div class="scrollTitle">Sobre este paciente</div>';
    echo '<div id="scroll" >';
	
	// diagnosticos
	$sqlnot="select realizado as fecha, descripcion_diag as parte1, observac_diagnos as parte2 from ".$_SESSION['bdd']."_diagnosticos where nrocon_diagnostico = '".$_SESSION['numeroregistro']."' order by realizado";
	$resultado=mysql_query($sqlnot);
	$contador=0;
	echo '<script language="javascript">scrollStart();</script>';
	echo '<div class="title"><strong>----DIAGNOSTICOS----</strong></div>';
	while ($fila2 = mysql_fetch_assoc($resultado)) {
		$contador++;
		echo '<div class="title">'.$contador.') '.($fila2['fecha']).'</div>';
		echo '<div class="content">'.$fila2['parte1'].'/'.$fila2['parte2'].'</div>';
	}
		
	// signos
	$sqlnot="select realizado as fecha, Concat('Peso= ',peso,' / Estatura = ',estatura, ' / Temperatura = ',temperatura,' / Presion Arterial = ',presionart,' / Sangre Tipo = ',tiposangre) as parte1, observacion as parte2 from ".$_SESSION['bdd']."_signos where numcon_signos = '".$_SESSION['numeroregistro']."' order by realizado";
	$resultado=mysql_query($sqlnot);
	$contador=0;
//	echo $sqlnot;
	echo '<script language="javascript">scrollStart();</script>';
	echo '<div class="title"><strong>----SIGNOS VITALES----</strong></div>';
	while ($fila2 = mysql_fetch_assoc($resultado)) {
		$contador++;
		echo '<div class="title">'.$contador.') '.($fila2['fecha']).'</div>';
		echo '<div class="content">'.$fila2['parte1'].'/'.$fila2['parte2'].'</div>';
	}

	// alergias
	$sqlnot="select realizado as fecha, alergicoa as parte1, observacion as parte2 from ".$_SESSION['bdd']."_alergias where numcon_alergias = '".$_SESSION['numeroregistro']."' order by realizado";
	$resultado=mysql_query($sqlnot);
	$contador=0;
	echo '<script language="javascript">scrollStart();</script>';
	echo '<div class="title"><strong>----ALERGIAS----</strong></div>';
	while ($fila2 = mysql_fetch_assoc($resultado)) {
		$contador++;
		echo '<div class="title">'.$contador.') '.($fila2['fecha']).'</div>';
		echo '<div class="content">'.$fila2['parte1'].'/'.$fila2['parte2'].'</div>';
	}
		
    echo '</div>';
	echo '</div>';

	// referencias
	$sqlnot="select realizado as fecha, examende as parte1, observacion as parte2 from ".$_SESSION['bdd']."_referencias where numcon_referencia = '".$_SESSION['numeroregistro']."' and tipo = 'Especialista' order by realizado ";
	$resultado=mysql_query($sqlnot);
	$contador=0;
	echo '<script language="javascript">scrollStart();</script>';
	echo '<div class="title"><strong>----REFERENCIAS----</strong></div>';
	while ($fila2 = mysql_fetch_assoc($resultado)) {
		$contador++;
		echo '<div class="title">'.$contador.') '.($fila2['fecha']).'</div>';
		echo '<div class="content">'.$fila2['parte1'].'/'.$fila2['parte2'].'</div>';
	}
		
    echo '</div>';
	echo '</div>';

	//
}

if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['diagnostico']))
{
// 	echo '<script language="javascript">alert("diagnostico")</script>';
//	echo '<div id="divdatos" top=400px; style="text-align:center; margin: 0 auto;">'; // visibility: hidden
//	echo ';;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;';
//	echo '</div>';
	echo '<div style="text-align: center; position: absolute; top: 350px; " id="divdatos">';
	echo "<table class='basica 100 hover' width='100%'><tr>";
	echo "<form action='consultas.php' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
	echo '<input type="hidden" name="medico" id="medico" value="'.$_POST['medico'].'">';
	echo '<tr><td>Diagn&oacute;tico</td><td> <textarea id="ddiagnostico" name="ddiagnostico" row="4" cols="50" value="." ></textarea></td></tr>';
	echo '<tr><td>Observaci&oacute;n</td><td> <textarea id="dobservacion" name="dobservacion" row="4" cols="50" value="." ></textarea></td></tr>';
	echo '<tr><td colspan="2">Presenta s&iacute;ntomas con Notificaci&oacuten Obligatoria';
	echo '<select name="notificacion" size="1">';
	$sql="select cvalor from ".$_SESSION['bdd']."_configura where cparametro='Notificacion' order by cvalor	";
	$resultado=mysql_query($sql);
	while ($fila2 = mysql_fetch_assoc($resultado)) {
		echo '<option value="'.$fila2['cvalor'].'" '.(($elcivil==$fila2['cvalor'])?'selected':'').'>'.$fila2['cvalor'].'</option>';}
 	echo '</select> '; 
	echo '</td></tr><tr>';
	echo '<td colspan="2"><input type="checkbox" name="primeravez">Primera Vez</td></tr>';
	echo '<tr>';
	echo '<td colspan="2" align="center"><input id="Guardard" name="Guardard" type="submit" value="Guardar" />';
	echo '<input id="Cancelar" name="Cancelar" type="submit" value="Descartar" /></td>';
	echo '</tr>';
	echo '</form>';
	echo '</table>';
	echo '</div>';

}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['signos']))
{
//	echo '<script language="javascript">alert("receipe")</script>';
	echo '<div style="text-align: center; position: absolute; top: 350px; " id="divdatos">';
	echo "<table class='basica 100 hover' width='100%'><tr>";
	echo "<form action='consultas.php' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
	echo '<input type="hidden" name="medico" id="medico" value="'.$_POST['medico'].'">';
	echo '<tr><td>Peso</td><td> <input type="text" id="peso" name="peso" value="" /></td>';
	echo '<td>Estatura</td><td> <input type="text" id="estatura" name="estatura" value="" /></td></tr>';
	echo '<tr><td>Temperatura</td><td> <input type="text" id="temperatura" name="temperatura" value="" /></td>';
	echo '<td>Presi&oacute;n Arterial</td><td> <input type="text" id="presion" name="presion" value="" /></td></tr>';
	echo '<tr><td>Tipo de Sangre</td><td> <input type="text" id="tipo" name="tipo" value="" /></td>';
	 echo '<td></td><td> </td></tr>';
	echo '<tr><td>Observaci&oacute;n</td><td colspan="3"> <textarea id="dobservacion" name="dobservacion" row="4" cols="50" value="." ></textarea></td></tr>';
	echo '<tr>';
	echo '<td colspan="4" align="center"><input id="Guardard" name="Guardars" type="submit" value="Guardar" />';
	echo '<input id="Cancelar" name="Cancelar" type="submit" value="Descartar" /></td>';
	echo '</tr>';
	echo '</form>';
	echo '</table>';
	echo '</div>';
}

if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['alergias']))
{
//	echo '<script language="javascript">alert("receipe")</script>';
	echo '<div style="text-align: center; position: absolute; top: 350px; " id="divdatos">';
	echo "<table class='basica 100 hover' width='100%'><tr>";
	echo "<form action='consultas.php' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
	echo '<input type="hidden" name="medico" id="medico" value="'.$_POST['medico'].'">';
	echo '<tr><td>Alergico a</td><td> <textarea id="alergicoa" name="alergicoa" row="4" cols="50" value="." ></textarea></td></tr>';
	echo '<tr><td>Observaci&oacute;n</td><td> <textarea id="dobservacion" name="dobservacion" row="4" cols="50" value="." ></textarea></td></tr>';
	echo '<tr>';
	echo '<td colspan="2" align="center"><input id="Guardard" name="Guardara" type="submit" value="Guardar" />';
	echo '<input id="Cancelar" name="Cancelar" type="submit" value="Descartar" /></td>';
	echo '</tr>';
	echo '</form>';
	echo '</table>';
	echo '</div>';
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['recipe']))
{
//	echo '<script language="javascript">alert("receipe")</script>';
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['reposo']))
{
//	echo '<script language="javascript">alert("receipe")</script>';
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['constancia']))
{
//	echo '<script language="javascript">alert("receipe")</script>';
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['informe']))
{
//	echo '<script language="javascript">alert("receipe")</script>';
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['orden']))
{
//	echo '<script language="javascript">alert("receipe")</script>';
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['historia']))
{
//	echo '<script language="javascript">alert("receipe")</script>';
}
echo 'ref '.$_POST['Continuarref'];
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and (($_POST['referencia']) and (!$_POST['Continuarref'])))
{
//	echo '<script language="javascript">alert("receipe")</script>';

	echo '<div style="text-align: center; position: absolute; top: 350px; " id="divdatos">';
	echo "<form action='consultas.php' id='formref' name='formref' enctype='multipart/form-data' method='post'>";
	echo "<table class='basica 100 hover' width='100%'><tr>";
	echo '<input type="hidden" name="medico" id="medico" value="'.$_POST['medico'].'">';

	// echo '<input type="hidden"  name="ciudad" id="ciudad" value="" size="3"></td>';
	echo '<div id="demo" ><td>Especialidad </td><td><div id="demoMed">'; // style="width:100px;"
		$sql="select * from  ".$_SESSION['bdd']."_especialidad order by nombre";

		echo '<select name="especialidad" id="especialidad" size="1" onChange="cargaContenido(this.id)">';
			echo '<option value="x">Seleccione Especialidad</option>'; 		
			$resultado=mysql_query($sql);
			while ($fila2 = mysql_fetch_assoc($resultado)) {
				echo '<option value="'.$fila2['codigo'].'">'.$fila2['nombre'].'</option>'; 		
			}
		echo '</select>'; 

	echo '</td></tr><tr>';

	$sql="select * from  ".$_SESSION['bdd']."_instituto where codesp = '$especialidad' order by instituto";
//	echo '<div id="demo" ><td>Especialista</td><td> '; // style="width:100px;"
	echo '<td>Especialista</td><td> '; // style="width:100px;"
	echo '<select name="especialistas" id="especialistas" size="1">';
		echo '<option value="x">Seleccione Especialista</option>'; 		
			$resultado=mysql_query($sql);
			while ($fila2 = mysql_fetch_assoc($resultado)) {
				// echo '<option value="'.$fila2['codmed'].'">'.$fila2['instituto'].'</option>'; 
				}
		echo '</select> '; 
	echo '</td>';
	echo '<input id="referencia" name="referencia" type="hidden" value="Referencia" >';
	echo '</tr>';
	echo '<tr>';
	echo '<td colspan="2" align="center"><input id="Continuarref" name="Continuarref" type="submit" value="Continuar Referencia" />';
	echo '</td>';
	echo '</tr>';
	echo '</table>';
	echo '</form>';
	echo '</div>';

/*
	<div id="light" class="modal">
	<p> 
	 nombre <input type = 'text' id='nombre'>
	<td colspan="2"><input type="checkbox" name="primeravez">Primera Vez</td></tr>
	<script language="JavaScript" type="text/javascript"> document.getElementById('especialidad').value; </script> 
	especialidad <input type='text' value=<script language="JavaScript" type="text/javascript"> print document.getElementById('especialidad').value; </script> >
	seleccion <input type='text' value=document.getElementById('especialistas').value>
	<a href = "javascript:void(0)" onclick = "document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'">cerrar</a>
	</div>
	<a href = "javascript:void(0)" onclick = "document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block';">simple click</a>
	<?
*/

}

// continuacion de la referencia
// revisar el monto por ano
// revisar el monto que lleva actualmente
// mas lo que se va a consumir en los examenes

if (($_POST['medico']) and ($_SESSION['numeroregistro']) and (($_POST['referencia']) and ($_POST['Continuarref'])))
{
	echo '<div style="text-align: center; position: absolute; top: 350px; " id="divdatos">';
	echo "<table class='basica 100 hover' width='100%'><tr>";
	echo "<form action='consultas.php' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
	echo '<input type="hidden" name="medico" id="medico" value="'.$_POST['medico'].'">';
	echo '<input type="hidden" name="especialistas" id="especialistas" value="'.$_POST['especialistas'].'">';
	echo '<input type="hidden" name="especialidad" id="especialidad" value="'.$_POST['especialidad'].'">';
	$ano=substr(ahora(),0,4);
	$sql="select * from ".$_SESSION['bdd']."_cupo where fecha = '$ano'";
	$result_cupo=mysql_query($sql);
	
	if (mysql_num_rows($result_cupo) < 1)
		die('<h1>Lo siento... no hay cupo asignado para este a&nacute;o<br>Participe a administraci&oacute;n para subsanar el inconveniente</h1>');
	
	$filatope=mysql_fetch_assoc($result_cupo);
	$topetitular = $filatope['titular'];
	
	// reviso los consumos
	$sql="select sum(montoconsumo) as consumido from ".$_SESSION['bdd']."_referencias where (substr(realizado,1,4) = '$ano') and (cedulatitular = '".$_SESSION['cedulatitular']."')";
	$result_consumo=mysql_query($sql);
	$filaconsumo=mysql_fetch_assoc($result_consumo);
	$consumo = $filaconsumo['consumido'];
	
	if ($consumo > $topetitular)
		die('<h1>Lo siento... Ha agotado el cupo asignado para este a&nacute;o<br>Puede realizar los ex&aacute;menes por su cuenta</h1>');

	$sql="select * from ".$_SESSION['bdd']."_instituto where codmed = '".$_POST['especialistas']."'";
//	echo $sql;
	$result_especialista=mysql_query($sql);
	$fila=mysql_fetch_assoc($result_especialista);
	$hoy = substr(ahora(),0,10);

	// consulto en costos
	$sql="select *, 'Su Especialidad' as descripcion, idregistro as nroregistro from ".$_SESSION['bdd']."_costos where codigo = '".$_POST['especialistas']."' and ('$hoy' >= fechadesde and '$hoy' <= fechahasta)";
	$result_costos=mysql_query($sql) or die(mysql_error().' '.$sql);
//	echo $sql;
	if (mysql_num_rows($result_costos) < 1)
		die('<h1>Lo siento... no hay montos asignados para este per&iacute;odo<br>Participe a administraci&oacute;n para subsanar el inconveniente</h1>');
	
	$Tipo=$fila['tipo'];
	if ($fila['tipo'] == 'Laboratorio')
	{
		// consulto en costos laboratorio
		$result_costos=mysql_query($sql) or die(mysql_error().' '.$sql);
		$fila=mysql_fetch_assoc($result_costos);
		$elid=$fila['idregistro'];
		$sql="select * from ".$_SESSION['bdd']."_costoslaboratorio where registrocosto = '".$elid."'";
		$result_costos=mysql_query($sql) or die(mysql_error().' '.$sql);
	}
//	echo $sql;
	
	$cancelar=array();
	$registros=0;
	$columnas=0;
	echo '<tr><td>Cupo Anual</td><td align="right"><input type="text" id="cupo" name="cupo" value="'.($topetitular).'" readonly></td>';
	echo '<td>Consumido</td><td align="right"><input type="text" id="consumido" name="consumido" value="'.($consumo).'" readonly"></td>';
	echo '<td>Ahora</td><td align="right"><input type="text" id="aqui" name="aqui" value="0" readonly"></td>';
	echo '<td>Por Consumir </td><td align="right"><input class="fondo_rojo" type="text" id="xconsumir" name="xconsumir" value="'.number_format((($topetitular-$consumo)-$aqui),2,',','.').'" readonly"></td>';
/*
	echo '<tr><td class="hoy">Cupo Anual</td><td align="right"><input style="background-color: #87CEEB;" "type="text" id="cupo" name="cupo" value="'.number_format($topetitular,2,',','.').'" readonly></td>';
	echo '<td>Consumido</td><td align="right"><input type="text" id="consumido" name="consumido" value="'.number_format($consumo,2,',','.').'" readonly"></td>';
	echo '<td>En esta operacion</td><td align="right"><input type="text" id="aqui" name="aqui" value="0" readonly"></td>';
	echo '<td>Por Consumir </td><td align="right"><input type="text" id="xconsumir" name="xconsumir" value="'.number_format((($topetitular-$consumo)-$aqui),2,',','.').'" readonly"></td>';
*/
	echo '</tr>';
	while($fila=mysql_fetch_assoc($result_costos)) 
	{
		$columnas++;
		$registros++;
		if ($columnas == 1)
			echo '<tr>';
		echo '<td align="left">'.$fila['descripcion'].'</td>';
		echo '<td align="right">'.number_format($fila['costo'],2,".",",").'</td>';
		echo '<td class="centro azul"><input type="checkbox" id="cancelar'.$registros.'" name="cancelar'.$registros.'" value="'.$fila["nroregistro"] .'" onClick="calccanc()" ';
		// if ($fila)) echo ' checked ';

		// disabled="true" ';
		echo '></td>';
		if ($columnas == 3)
		{
			$columnas=0;
			echo '<tr>';
		}
	}
	
	echo '<td><input type="hidden" id="registros" name="registros" value="'.$registros.'" "></td>';
	echo '<td><input type="hidden" id="tipo" name="tipo" value="'.$Tipo.'" "></td>';
	
	echo '<tr>';
	echo '<td colspan="3" align="center"><input type="button" name="calculo" value="Marcar Todos" onClick="marcar()"></td>	';
	echo '<td colspan="3" align="center"><input type="button" name="calculo" value="Desmarcar Todos" onClick="desmarcar()"></td>	';
	echo '<td colspan="3" align="center"><input id="Guardarref" name="Guardarref" type="submit" value="Guardar Referencia" />';
	echo '</td>';
	echo '</tr>';
	echo '</table>';
	echo '</form>';
	echo '</div>';

}



function botones($habilitar)
{
	if ($habilitar == false)
		$readonly=" disabled";
	else $readonly=" enabled";
		
	echo '<tr align="center">';
//	echo '<form id="llamar2" name="llamar2" action="" onsubmit="examenes(\'diagnostico.php\'); return false">'; // 
	echo "<form action='consultas.php' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
//		echo '<div style="text-align: center; position: absolute; top: 400px; " id="losexamenes">';
		echo '<input type="hidden" name="medico" id="medico" value="'.$_POST['medico'].'">';
		echo '<td><input id="diagnostico" name="diagnostico" type="submit" value="Diagn&oacute;stico" '.$readonly.'/></td>';  // disabled
//		echo '</div>';
	echo '<td><input id="signos" name="signos" type="submit" value="Signos Vitales" '. $readonly.'/></td>';
	echo '<td><input id="alergias" name="alergias" type="submit" value="Alergias"  '. $readonly.'/></td>';
	echo '<td><input id="referencia" name="referencia" type="submit" value="Referencia"  '. $readonly.'/></td>';
	echo '<td><input id="reposo" name="reposo" type="submit" value="Reposo"  '. $readonly.'/></td>';
	echo '<td><input id="constancia" name="constancia" type="submit" value="Constancia"  '. $readonly.'/></td>';
	echo '<td><input id="informe" name="informe" type="submit" value="Informe"  '. $readonly.'/></td>';
	echo '<td><input id="recipe" name="recipe" type="submit" value="R&eacute;cipe"  '. $readonly.'/></td>';
	echo '<td><input id="orden" name="orden" type="submit" value="Orden Hospit." '.  $readonly.'/></td>';
	echo '<td><input id="historia" name="historia" type="submit" value="Historia"  '. $readonly.'/></td>';
	echo '</tr>';
	echo '</table>';
	echo '</form>';

}
/*

 <div id="light" class="modal">
     <p>
	 nombre <input type = 'text' id='nombre'>
	<td colspan="2"><input type="checkbox" name="primeravez">Primera Vez</td></tr>
	 
	 Contenido de la ventana modal. Para
<a href = "javascript:void(0)" onclick = "document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'">cerrar</a>
 basta un simple JavaScript.</p>
    </div>

    <p>Con un
<a href = "javascript:void(0)" onclick = "document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block';">simple click</a>
 podrás desplegar la ventana modal.</p>
 
*/
?>

</body></html>

