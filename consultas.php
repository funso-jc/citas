<?php
include("head.php");
include("paginar.php");

/*
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
// phpinfo();
*/
?>
<script language="JavaScript" type="text/javascript" src="llamarpcteconsultorio.js"></script>
<script language="JavaScript" type="text/javascript" src="examenes.js"></script>
<script type="text/javascript" type="text/javascript" src="select_dependientes_3_niveles.js"></script>
<script type="text/javascript" type="text/javascript" src="ajx_examenes.js"></script>
<script language="Javascript" src="selec_fecha_pasado.js" type='text/javascript'></script>

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


<body>


<?php
// onload="escrolea()"> 
// V1ct0r/-
 // onLoad="scrollStart();">
// $_SESSION['numeroregistro']='';
$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;
include("menusizda.php");
$cedula = $_GET['cedula'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
$ahora=ahora($db_con);
echo 'medico '.$_POST['medico'];
////////////// finalizar session del paciente
if ($_POST['Finalizar'])
{
	// mandar a imprimir
	// colocar la hora de termino en la consulta y en el justificativo si lo tiene
	// blanquear numero de registro y colocar 2 en pasocons para saber que termino
	// habilitar medico para otro paciente
	echo "<a class='btn btn-success' target=\"_blank\" href=\"impresion.php\" onClick=\"info.html\', \'\',\'width=250, height=190\')\">Imprimir Soportes</a>";
	$sqli="UPDATE ".$_SESSION['bdd']."justificativo set hasta = '".ahora($db_con)."' where numcon_justificativo = '".$_SESSION['numeroregistro']."'";
	$resultado=$db_con->prepare($sqli);
	$resp=$resultado->execute();
	 // echo $sqli;
	if (!$resp) die('Error en actualizacion  <br>'.$sqli);	
	$sqli="update ".$_SESSION['bdd']."consulta set pasocons = 2, fechatermino = '".ahora($db_con)."' where numeroconsulta = '".$_SESSION['numeroregistro']."'";
	$resultado=$db_con->prepare($sqli);
	$resp=$resultado->execute();
	if (!$resp)  die('Error en inclusion <br>'.$sqli);	
	$sqli="UPDATE ".$_SESSION['bdd']."internos set disponible = 0, hfdisponibilidad = '".ahora($db_con)."' where (codigomedico = '$medico')";
	$resultado=$db_con->prepare($sqli);
	$resp=$resultado->execute();
	// unset($_SESSION['numeroregistro']);
//	include('imprimir.php');
}
////////////// finalizar session del paciente
if ($_POST['StandBy'])
{
	// no imprimir
	// colocar la hora de termino en la consulta (por si acaso) y en el justificativo si lo tiene
	// blanquear numero de registro y colocar 9 en pasocons para saber que esta pendiente
	// habilitar medico para otro paciente
	$sqli="UPDATE ".$_SESSION['bdd']."justificativo set hasta = '".ahora($db_con)."' where numcon_justificativo = '".$_SESSION['numeroregistro']."'";
	 // echo $sqli;
	$resultado=$db_con->prepare($sqli);
	$resp=$resultado->execute();
	if (!$resp) die('Error en actualizacion <br>'.$sqli);	
	$sqli="update ".$_SESSION['bdd']."consulta set pasocons = 9, fechatermino = '".ahora($db_con)."' where numeroconsulta = '".$_SESSION['numeroregistro']."'";
	$resultado=$db_con->prepare($sqli);
	$resp=$resultado->execute();
	if (!$resp) die('Error en inclusion <br>'.$sqli);	
	$sqli="UPDATE ".$_SESSION['bdd']."internos set disponible = 0, hfdisponibilidad = '".ahora($db_con)."' where (codigomedico = '$medico')";
	$resultado=$db_con->prepare($sqli);
	$resp=$resultado->execute();
	unset($_SESSION['numeroregistro']);
	// echo '<span>numero '.$_SESSION['numeroregistro'].'</span>';
	// die('pase');
	}
if ($_POST['Descanso'])
{
	$sqli="UPDATE ".$_SESSION['bdd']."internos set atendiendo = 0, hfdisponibilidad = '".ahora($db_con)."' where (codigomedico = '$medico')";
	$resultado=$db_con->prepare($sqli);
	$resp=$resultado->execute();
	unset($_SESSION['numeroregistro']);
}
//////////////////
if (!$_POST['medico'])
{
	echo "<form action='consultas.php?accion=seleccion' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
	echo '<td>M&eacute;dico </td><td><select id="medico" name="medico" size="1">';
	$sql="select nombre, codigomedico from ".$_SESSION['bdd']."internos where status = 1 order by nombre "; // and atendiendo = 0 
	$resultado=$db_con->prepare($sql);
	$resp=$resultado->execute();
	while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) 
	{
		echo '<option value="'.$fila2['codigomedico'].'" '.(($elstatus==$fila2['nombre'])?'selected':'').'>'.$fila2['nombre'].'</option>';}
	echo '</select></td>'; 
	echo '<tr><td colspan="2" align="center">';
	echo "<input  class='btn btn-info' type = 'submit' id='seleccionar' name='seleccionar' value = 'Seleccionar' >"; // disabled
	echo '</td></tr>';
	echo "</form>";
}

if (($_POST['medico']) and (!$_SESSION['numeroregistro']))
{
	// boton para liberar el medico
//	echo 'falta boton para descanso del medico <br>';
//	echo 'falta boton para dejar en suspenso al paciente ';
	$sql="UPDATE ".$_SESSION['bdd']."internos set atendiendo = 1 where codigomedico = '".$_POST['medico']."'";
	$resultado=$db_con->prepare($sql);
	$resp=$resultado->execute();
	$_SESSION['codigomedico']=$_POST['medico'];
	$sql="select * from ".$_SESSION['bdd']."internos where codigomedico = '".$_POST['medico']."'";
	$resultador=$db_con->prepare($sql);
	$resp=$resultador->execute();
	$filadr=$resultador->fetch(PDO::FETCH_ASSOC);
	echo '<div id="imagenbusqueda"></div>';
	echo '<form id="llamar" name="llamar" action="" onsubmit="LlamarPacientealConsultorio(); return false">';
	echo '<div id="resultado">';
	$_SESSION['nombredr']=$filadr['nombre'];

	echo "<table class='table' width='60%'><tr>";
	echo '<td>Buen d&iacutea Dr(a) '.$filadr['nombre'].'</td>';
	echo '<input type="hidden" name="codigomedico" id="codigomedico" value="'.$_POST['medico'].'">';
	echo '<input type="hidden" name="numeroregistro" id="numeroregistro" value="">';
	echo '<td><input class="btn btn-warning" type="submit" value="Llamar al Siguiente Paciente" /></td></tr>';
	echo '<tr><td>Titular<input type"text" id="cedulatitular" name="cedulatitular" value="" readonly></td>';
	echo '<td>Nombre Titular<input type"text" id="nombretitular" name="nombretitular" size="50" value="" readonly></td></tr>';
	echo '<tr><td>Beneficiario <input type"text" id="cedulabeneficiario" name="cedulabeneficiario" value="" readonly></td>';
	echo '<td>Nombre Beneficiario <input type"text" id="nombrebeneficiario" name="nombrebeneficiario" size="50" value="" readonly></td></tr>';
	echo '<input type="hidden" name="existe" id="existe" value="0">';
	echo '<input type="hidden" name="medico" id="medico" value="'.$_POST['medico'].'">';
	echo '<tr><td>Motivo de la Cita </td><td><input type"text" id="motivo" name="motivo" value="" readonly></td></tr>';
	echo '<tr><td>Observaciones</td><td> <textarea id="observaciones" name="observaciones" row="4" cols="60" value="." readonly></textarea></td></tr>';
	echo '</table>';
	echo '</div>';
//	echo '<div id="divexamenes" style="text-align:center; margin: 0 auto;">';
	echo '<table>';

//	include("mostrarpaciente.php");
	echo '</form>';
	botones(false);
}

	
///////////////////// guardar
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['Guardard']))
{
	$primeravez=(isset($_POST['primeravez'])?1:0);
	$sqli="INSERT INTO ".$_SESSION['bdd']."diagnosticos (nrocon_diagnostico, descripcion_diag, observac_diagnos, ip, realizado, notifica, primeravez, pc) VALUES 
	('".$_SESSION['numeroregistro']."', '$ddiagnostico', '$dobservacion', '$ip', '$ahora', '$notificacion', '$primeravez', 'x')";
	// echo $sqli;
	try
	{
		$resultado=$db_con->prepare($sqli);
		$resp=$resultado->execute();
	}
	catch(PDOException $e){
		echo $e->getMessage();
		 // echo 'Fallo la conexion';
	}

	if (!$resp) die('Error en inclusion <br>'.$sqli);
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['Guardars']))
{
	$sqli="INSERT INTO ".$_SESSION['bdd']."signos (numcon_signos, peso, estatura, temperatura, presionart, pulso, tiposangre, observacion, ip, realizado) VALUES 
	('".$_SESSION['numeroregistro']."', '$peso', '$estatura', '$temperatura', '$presionart', '$pulso', '$tiposangre', '$dobservacion', '$ip', '$ahora')";
	// echo $sqli;
	try
	{
		$resultado=$db_con->prepare($sqli);
		$resp=$resultado->execute();
	}
	catch(PDOException $e){
		echo $e->getMessage();
		 // echo 'Fallo la conexion';
	}
	if (!$resp) die('Error en inclusion <br>'.$sqli);
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['Guardara']))
{
	$sqli="INSERT INTO ".$_SESSION['bdd']."alergias (numcon_alergias, alergicoa, observacion, ip, realizado) VALUES 
	('".$_SESSION['numeroregistro']."', '$alergicoa', '$dobservacion', '$ip', '$ahora')";
	// echo $sqli;
	try
	{
		$resultado=$db_con->prepare($sqli);
		$resp=$resultado->execute();
	}
	catch(PDOException $e){
		echo $e->getMessage();
		 // echo 'Fallo la conexion';
	}
	if (!$resp) die('Error en inclusion <br>'.$sqli);
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['Guardarrec']))
{
	$sqli="INSERT INTO ".$_SESSION['bdd']."tratamiento (nrocon_recipe, rp, ind, codigomedico, ip, realizado, pc) VALUES 
	('".$_SESSION['numeroregistro']."', '$rp', '$ind', '$medico', '$ip', '$ahora', 'x')";
	try
	{
	// echo $sqli;
		$resultado=$db_con->prepare($sqli);
		$resp=$resultado->execute();
	}
	catch(PDOException $e){
		echo $e->getMessage();
		 // echo 'Fallo la conexion';
	}
	if (!$resp) die('Error en inclusion <br>'.$sqli);
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
				$sql="select *, descripcion as examen from ".$_SESSION['bdd']."costoslaboratorio where nroregistro = '".$elid."'";
				$result_costos=$db_con->prepare($sql);
				$resp=$result_costos->execute();
				if (!$resp)  die(' '.$sql);
				$fila=$result_costos->fetch(PDO::FETCH_ASSOC);
				$monto = $fila['costo'];
			}	
			else 
			{
				$sql="select *, 'Su Especialidad' as examen  from ".$_SESSION['bdd']."costos where idregistro = '".$elid."'";
				$resultado=$db_con->prepare($sql);
				$resp=$resultado->execute();
				if (!$resp) die(' '.$sql);
				$fila=$resultado->fetch(PDO::FETCH_ASSOC);
				$monto = $fila['costo'];				
			}
//			echo $sql.'\n';
			$sql="INSERT INTO ".$_SESSION['bdd']."referencias (numcon_referencia, codmed, observacion, realizarse, montoconsumo, cedulatitular, cedulabeneficiario, ip, realizado, tipo, registrooriginal, examende, especialista) VALUES 
			('".$_SESSION['numeroregistro']."', '$medico', '$dobservacion', '$realizarse', '$monto', '".$_SESSION['cedulatitular']."', '".$_SESSION['cedulabeneficiario']."', '$ip', '$ahora', '$tipo', '".$$variable."', '".$fila['examen']."', '".$_POST['especialistas']."')";
			try
			{
				$resultado=$db_con->prepare($sql);
				$resp=$resultado->execute();
			}
			catch(PDOException $e){
				echo $e->getMessage(). $sql;
				 // echo 'Fallo la conexion';
			}
			if (!$resp) die(' '.$sql);
		}
	}
//	echo '</div>';
}

if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['Guardarrep']))
{
	$fechadesde=convertir_fecha($fechadesde);
	$fechahasta=convertir_fecha($fechahasta);
	$fechaincorpora=convertir_fecha($fechaincorpora);
	$volveraconsulta=(isset($_POST['volver'])?1:0);
	$sqli="INSERT INTO ".$_SESSION['bdd']."reposos (nrocon_reposo, fechareposo, cedulatitular, cedulabeneficiario, codigomedico, desde, hasta, observacion, volveraconsulta, fechaincorporacion, ip, realizado) VALUES 
	('".$_SESSION['numeroregistro']."', '$ahora', '".$_SESSION['cedulatitular']."', '".$_SESSION['cedulabeneficiario']."', '$medico', '$fechadesde', '$fechahasta', '$dobservacion', '$volveraconsulta', '$fechaincorpora', '$ip', '$ahora')";
	 // echo $sqli;
	try
	{
		$resultado=$db_con->prepare($sqli);
		$resp=$resultado->execute();
	}
	catch(PDOException $e){
		echo $e->getMessage();
		 // echo 'Fallo la conexion';
	}

	if (!$resp) die('Error en inclusion <br>'.$sqli);
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['Guardarcons']))
{
	$fechahasta='1001-01-01';
	$sqli="INSERT INTO ".$_SESSION['bdd']."justificativo (numcon_justificativo, fechajustificativo, cedulatitular, cedulabeneficiario, codigomedico, motivojustificativo, desde, hasta, observacion, ip, realizado) VALUES 
	('".$_SESSION['numeroregistro']."', '$ahora', '".$_SESSION['cedulatitular']."', '".$_SESSION['cedulabeneficiario']."', '$medico', '$notificacion', '$fechadesde', '$fechahasta', '$dobservacion', '$ip', '$ahora')";
	 // echo $sqli;
	try
	{
		$resultado=$db_con->prepare($sqli);
		$resp=$resultado->execute();
	}
	catch(PDOException $e){
		echo $e->getMessage().$sqli;
		 // echo 'Fallo la conexion';
	}
	if (!$resp) die('Error en inclusion <br>'.$sqli);	
	$sqli="update ".$_SESSION['bdd']."consulta set justificativo = 1 where numeroconsulta = '".$_SESSION['numeroregistro']."'";
	try
	{
		$resultado=$db_con->prepare($sqli);
		$resp=$resultado->execute();
	}
	catch(PDOException $e){
		echo $e->getMessage().$sqli;
		 // echo 'Fallo la conexion';
	}
	if (!$resp) die('Error en inclusion<br>'.$sqli);	
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['Guardarinf']))
{
	$sqli="INSERT INTO ".$_SESSION['bdd']."informe (nrocon_informe, fechainforme, cedulatitular, cedulabeneficiario, codigomedico, dx, observacion, ip, realizado) VALUES 
	('".$_SESSION['numeroregistro']."', '$ahora', '".$_SESSION['cedulatitular']."', '".$_SESSION['cedulabeneficiario']."', '$medico', '$dx', '$dobservacion', '$ip', '$ahora')";
	 // echo $sqli;
	try
	{
		$resultado=$db_con->prepare($sqli);
		$resp=$resultado->execute();
	}
	catch(PDOException $e){
		echo $e->getMessage();
		 // echo 'Fallo la conexion';
	}
	if (!$resp) die('Error en inclusion <br>'.$sqli);	
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
	$sql="select * from ".$_SESSION['bdd']."internos where codigomedico = '".$_POST['medico']."'";
	$resultado=$db_con->prepare($sql);
	$resp=$resultado->execute();
	$filadr=$resultado->FETCH(PDO::FETCH_ASSOC);
	$_SESSION['nombredr']=$filadr['nombre'];
	include("mostrarpaciente.php");
//	include('diagnostico.php');
	botones(true);


	// scroll

	echo '<div class="scrollWrapper" onMouseover="scrollspeed=0" onMouseout="scrollspeed=current">';
	echo '<div class="scrollTitle">Sobre este paciente</div>';
	echo '<div id="scroll" >';
	
	// diagnosticos
	$sqlnot="select realizado as fecha, descripcion_diag as parte1, observac_diagnos as parte2 from ".$_SESSION['bdd']."diagnosticos where nrocon_diagnostico = '".$_SESSION['numeroregistro']."' order by realizado";
	$resultado=$db_con->prepare($sqlnot);
	$resp=$resultado->execute();
	$noticias = $contador=0;
	echo '<script language="javascript">scrollStart();</script>';
	echo '<div class="title"><strong>----DIAGNOSTICOS----</strong></div>';
// noticias[0]= new noticia("Titulo","Descrip","fecha","#","1.gif")
// noticias[1]= new noticia("Titulo","Descrip","fecha","#","_blank","2.gif")

	while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
		$contador++;
		echo '<div class="title">'.$contador.') '.($fila2['fecha']).'</div>';
		echo '<div class="content">'.$fila2['parte1'].'/'.$fila2['parte2'].'</div>';
//		echo '<script language="javascript">noticias[".($contador+2)."]= new noticia("Titulo","Descrip","fecha","#","1.gif")</script>';
//		echo '<script language="javascript">noticias['.($noticias).']= new noticia("Diagnostico", "'.$fila2['parte1'].'/'.$fila2['parte2'].'", "'.$fila2['fecha'].'", "","")</script>';
		$noticias++;
//noticias[1]= new noticia("Titulo","Descrip","fecha","#","_blank","2.gif")
	}
		
	// signos
	$sqlnot="select realizado as fecha, Concat('Peso= ',peso,' / Estatura = ',estatura, ' / Temperatura = ',temperatura,' / Presion Arterial = ',presionart,' / Sangre Tipo = ',tiposangre) as parte1, observacion as parte2 from ".$_SESSION['bdd']."signos where numcon_signos = '".$_SESSION['numeroregistro']."' order by realizado";
	$resultado=$db_con->prepare($sqlnot);
	$resp=$resultado->execute();
	
	$contador=0;
//	echo $sqlnot;
	echo '<script language="javascript">scrollStart();</script>';
	echo '<div class="title"><strong>----SIGNOS VITALES----</strong></div>';
	while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
		$contador++;
		echo '<div class="title">'.$contador.') '.($fila2['fecha']).'</div>';
		echo '<div class="content">'.$fila2['parte1'].'/'.$fila2['parte2'].'</div>';
//		echo '<script language="javascript">noticias['.($noticias).']= new noticia("Signos Vitales", "'.$fila2['parte1'].'/'.$fila2['parte2'].'", "'.$fila2['fecha'].'", "","")</script>';
		$noticias++;
	}

	// alergias
	$sqlnot="select realizado as fecha, alergicoa as parte1, observacion as parte2 from ".$_SESSION['bdd']."alergias where numcon_alergias = '".$_SESSION['numeroregistro']."' order by realizado";
	$resultado=$db_con->prepare($sqlnot);
	$resp=$resultado->execute();
	$contador=0;
	echo '<script language="javascript">scrollStart();</script>';
	echo '<div class="title"><strong>----ALERGIAS----</strong></div>';
	while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
		$contador++;
		echo '<div class="title">'.$contador.') '.($fila2['fecha']).'</div>';
		echo '<div class="content">'.$fila2['parte1'].'/'.$fila2['parte2'].'</div>';
//		echo '<script language="javascript">noticias['.($noticias).']= new noticia("Alergias", "'.$fila2['parte1'].'/'.$fila2['parte2'].'", "'.$fila2['fecha'].'", "","")</script>';
		$noticias++;
	}
		
	// referencias especialistas
//	$sqlnot="select ".$_SESSION['bdd']."referencias.realizado as fecha, examende as parte1, observacion, instituto as parte2 from ".$_SESSION['bdd']."referencias, ".$_SESSION['bdd']."ninstituto where (numcon_referencia = '".$_SESSION['numeroregistro']."') and ".$_SESSION['bdd']."referencias.tipo = 'Especialista' and ".$_SESSION['bdd']."referencias.especialista=".$_SESSION['bdd']."ninstituto.codmed order by ".$_SESSION['bdd']."referencias.realizado ";
	$sqlnot="select ".$_SESSION['bdd']."referencias.realizado as fecha, examende as parte1, observacion, instituto as parte2 from ".$_SESSION['bdd']."referencias, ".$_SESSION['bdd']."ninstituto where (numcon_referencia = '".$_SESSION['numeroregistro']."') and ".$_SESSION['bdd']."referencias.especialista=".$_SESSION['bdd']."ninstituto.codmed order by ".$_SESSION['bdd']."referencias.realizado ";
	$resultado=$db_con->prepare($sqlnot);
	$resp=$resultado->execute();
//	echo $sqlnot;
	//	select smobrero_referencias.realizado as fecha, examende as parte1, observacion, instituto as parte2 from smobrero_referencias, smobrero_instituto where (numcon_referencia = '17') and smobrero_referencias.tipo = 'Especialista' and smobrero_referencias.especialista=smobrero_instituto.codmed order by smobrero_referencias.realizado 
	
	$contador=0;
	echo '<script language="javascript">scrollStart();</script>';
	echo '<div class="title"><strong>----REFERENCIAS----</strong></div>';
	while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
		$contador++;
		echo '<div class="title">'.$contador.') '.($fila2['fecha']).'</div>';
		echo '<div class="content">'.$fila2['parte1'].'/'.$fila2['parte2'].'</div>';
//		echo '<script language="javascript">noticias['.($noticias).']= new noticia("Referencias", "'.$fila2['parte1'].'/'.$fila2['parte2'].'", "'.$fila2['fecha'].'", "","")</script>';
		$noticias++;
	}
		
	// referencias examenes
//	$sqlnot="select especialista from ".$_SESSION['bdd']."referencias where (numcon_referencia = '".$_SESSION['numeroregistro']."') and tipo = 'Laboratorio' group by especialista";
	$sqlnot="select ".$_SESSION['bdd']."referencias.realizado as fecha, examende as parte1, observacion, especialista, instituto as parte2 from ".$_SESSION['bdd']."referencias, ".$_SESSION['bdd']."ninstituto where (numcon_referencia = '".$_SESSION['numeroregistro']."') and ".$_SESSION['bdd']."referencias.tipo = 'Laboratorio' and ".$_SESSION['bdd']."referencias.especialista=".$_SESSION['bdd']."ninstituto.codmed group by especialista, referencias.realizado, parte1, observacion,  parte2 order by ".$_SESSION['bdd']."referencias.realizado ";
	$resultadoe=$db_con->prepare($sqlnot);
	$resp=$resultadoe->execute();
	while ($fila1 = $resultadoe->fetch(PDO::FETCH_ASSOC)) {
		$sqlnot="select realizado as fecha, examende as parte1, observacion as parte2 from ".$_SESSION['bdd']."referencias where (numcon_referencia = '".$_SESSION['numeroregistro']."') and tipo = 'Laboratorio' and especialista = '".$fila1['especialista']."' order by realizado ";
		$resultado=$db_con->prepare($sqlnot);
		$resp=$resultado->execute();
	
// 	echo $sqlnot; 
		$contador=0;
		echo '<script language="javascript">scrollStart();</script>';
		echo '<div class="title"><strong>----REFERENCIAS Laboratorio----</strong></div>';
		$cuento = '';
		while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
			$contador++;
			if ($cuento == '')
				echo '<div class="title">'.$contador.') '.($fila2['fecha']).'</div>';
//			echo '<script language="javascript">noticias['.($noticias).']= new noticia("Referencias", "'.$fila2['parte1'].'/'.$fila2['parte2'].'", "'.$fila2['fecha'].'", "","")</script>';
			$parte2=$fila2['parte2'];
			$cuento.=$fila2['parte1'] . ' ';
			$noticias++;
		}
		echo '<div class="content">'.$cuento.'/'.$parte2.'</div>';
	}

	
	// reposos
	$sqlnot="select realizado as fecha, concat('Desde ',desde, ' Hasta ',hasta) as parte1, observacion as parte2 from ".$_SESSION['bdd']."reposos where nrocon_reposo = '".$_SESSION['numeroregistro']."' order by realizado";
	$resultado=$db_con->prepare($sqlnot);
	$resp=$resultado->execute();
	if (!$resp)	$contador=0;
	echo '<script language="javascript">scrollStart();</script>';
	echo '<div class="title"><strong>----REPOSOS----</strong></div>';
	while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
		$contador++;
		echo '<div class="title">'.$contador.') '.($fila2['fecha']).'</div>';
		echo '<div class="content">'.$fila2['parte1'].'/'.$fila2['parte2'].'</div>';
//		echo '<script language="javascript">noticias['.($noticias).']= new noticia("Alergias", "'.$fila2['parte1'].'/'.$fila2['parte2'].'", "'.$fila2['fecha'].'", "","")</script>';
		$noticias++;
	}
		
	// justificativo
	$sqlnot="select realizado as fecha, concat('Desde ',desde, ' Hasta ',hasta) as parte1, concat(motivojustificativo, ' ',observacion) as parte2 from ".$_SESSION['bdd']."justificativo where numcon_justificativo = '".$_SESSION['numeroregistro']."' order by realizado";
	$resultado=$db_con->prepare($sqlnot);
	$resp=$resultado->execute();
	$contador=0;
	echo '<script language="javascript">scrollStart();</script>';
	echo '<div class="title"><strong>----JUSTIFICATIVO----</strong></div>';
	while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
		$contador++;
		echo '<div class="title">'.$contador.') '.($fila2['fecha']).'</div>';
		echo '<div class="content">'.$fila2['parte1'].'/'.$fila2['parte2'].'</div>';
//		echo '<script language="javascript">noticias['.($noticias).']= new noticia("Alergias", "'.$fila2['parte1'].'/'.$fila2['parte2'].'", "'.$fila2['fecha'].'", "","")</script>';
		$noticias++;
	}
		
	// informe
	$sqlnot="select realizado as fecha, dx as parte1, observacion as parte2 from ".$_SESSION['bdd']."informe where nrocon_informe = '".$_SESSION['numeroregistro']."' order by realizado";
	$resultado=$db_con->prepare($sqlnot);
	$resp=$resultado->execute();
	$contador=0;
	echo '<script language="javascript">scrollStart();</script>';
	echo '<div class="title"><strong>----INFORME----</strong></div>';
	while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
		$contador++;
		echo '<div class="title">'.$contador.') '.($fila2['fecha']).'</div>';
		echo '<div class="content">'.$fila2['parte1'].'/'.$fila2['parte2'].'</div>';
//		echo '<script language="javascript">noticias['.($noticias).']= new noticia("Alergias", "'.$fila2['parte1'].'/'.$fila2['parte2'].'", "'.$fila2['fecha'].'", "","")</script>';
		$noticias++;
	}
		
	// tratamiento
	$sqlnot="select realizado as fecha, rp as parte1, ind as parte2 from ".$_SESSION['bdd']."tratamiento where nrocon_recipe = '".$_SESSION['numeroregistro']."' order by realizado";
	$resultado=$db_con->prepare($sqlnot);
	$resp=$resultado->execute();
	$contador=0;
	echo '<script language="javascript">scrollStart();</script>';
	echo '<div class="title"><strong>----TRATAMIENTO----</strong></div>';
	while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
		$contador++;
		echo '<div class="title">'.$contador.') '.($fila2['fecha']).'</div>';
		echo '<div class="content">'.$fila2['parte1'].'/'.$fila2['parte2'].'</div>';
//		echo '<script language="javascript">noticias['.($noticias).']= new noticia("Alergias", "'.$fila2['parte1'].'/'.$fila2['parte2'].'", "'.$fila2['fecha'].'", "","")</script>';
		$noticias++;
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
	echo "<table class='table' width='100%'><tr>";
	echo "<form action='consultas.php' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
	echo '<input type="hidden" name="medico" id="medico" value="'.$_POST['medico'].'">';
	echo '<tr><td>Diagn&oacute;tico</td><td> <textarea id="ddiagnostico" name="ddiagnostico" row="4" cols="60" value="." ></textarea></td></tr>';
	echo '<tr><td>Observaci&oacute;n</td><td> <textarea id="dobservacion" name="dobservacion" row="4" cols="60" value="." ></textarea></td></tr>';
	echo '<tr><td colspan="2">Presenta s&iacute;ntomas con Notificaci&oacuten Obligatoria';
	echo '<select name="notificacion" size="1">';
	$sql="select cvalor from ".$_SESSION['bdd']."configura where cparametro='Notificacion' order by cvalor	";
	$resultado=$db_con->prepare($sql);
	$resp=$resultado->execute();
	while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
		echo '<option value="'.$fila2['cvalor'].'" '.(($elcivil==$fila2['cvalor'])?'selected':'').'>'.$fila2['cvalor'].'</option>';}
 	echo '</select> '; 
	echo '</td></tr><tr>';
	echo '<td colspan="2"><input type="checkbox" name="primeravez">Primera Vez</td></tr>';
	echo '<tr>';
	echo '<td colspan="2" align="center"><input id="Guardard" name="Guardard"  class="btn btn-success" type="submit" value="Guardar" />';
	echo '<input id="Cancelar" name="Cancelar"  class="btn btn-danger" type="submit" value="Descartar" /></td>';
	echo '</tr>';
	echo '</form>';
	echo '</table>';
	echo '</div>';
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['signos']))
{
//	echo '<script language="javascript">alert("receipe")</script>';
	echo '<div style="text-align: center; position: absolute; top: 350px; " id="divdatos">';
	echo "<table class='table' width='100%'><tr>";
	echo "<form action='consultas.php' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
	echo '<input type="hidden" name="medico" id="medico" value="'.$_POST['medico'].'">';
	echo '<tr><td>Peso</td><td> <input type="text" id="peso" name="peso" value="" /></td>';
	echo '<td>Estatura</td><td> <input type="text" id="estatura" name="estatura" value="" /></td></tr>';
	echo '<tr><td>Temperatura</td><td> <input type="text" id="temperatura" name="temperatura" value="" /></td>';
	echo '<td>Presi&oacute;n Arterial</td><td> <input type="text" id="presion" name="presion" value="" /></td></tr>';
	echo '<tr><td>Tipo de Sangre</td><td> <input type="text" id="tipo" name="tipo" value="" /></td>';
	 echo '<td></td><td> </td></tr>';
	echo '<tr><td>Observaci&oacute;n</td><td colspan="3"> <textarea id="dobservacion" name="dobservacion" row="4" cols="60" value="." ></textarea></td></tr>';
	echo '<tr>';
	echo '<td colspan="4" align="center"><input id="Guardard" name="Guardars"  class="btn btn-success" type="submit" value="Guardar" />';
	echo '<input id="Cancelar" name="Cancelar" type="submit" class="btn btn-danger"  value="Descartar" /></td>';
	echo '</tr>';
	echo '</form>';
	echo '</table>';
	echo '</div>';
}

if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['alergias']))
{
//	echo '<script language="javascript">alert("receipe")</script>';
	echo '<div style="text-align: center; position: absolute; top: 350px; " id="divdatos">';
	echo "<table class='table' width='100%'><tr>";
	echo "<form action='consultas.php' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
	echo '<input type="hidden" name="medico" id="medico" value="'.$_POST['medico'].'">';
	echo '<tr><td>Alergico a</td><td> <textarea id="alergicoa" name="alergicoa" row="4" cols="60" value="." ></textarea></td></tr>';
	echo '<tr><td>Observaci&oacute;n</td><td> <textarea id="dobservacion" name="dobservacion" row="4" cols="60" value="." ></textarea></td></tr>';
	echo '<tr>';
	echo '<td colspan="2" align="center"><input id="Guardara" name="Guardara"  class="btn btn-success" type="submit" value="Guardar" />';
	echo '<input id="Cancelar" name="Cancelar" type="submit"  class="btn btn-danger" value="Descartar" /></td>';
	echo '</tr>';
	echo '</form>';
	echo '</table>';
	echo '</div>';
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['recipe']))
{
	echo '<div style="text-align: center; position: absolute; top: 350px; " id="divdatos">';
	echo "<table class='table' width='100%'><tr>";
	echo "<form action='consultas.php' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
	echo '<input type="hidden" name="medico" id="medico" value="'.$_POST['medico'].'">';
	echo '<tr><td>Rp.</td><td> <textarea id="rp" name="rp" row="8" cols="60" value="." ></textarea></td></tr>';
	echo '<tr><td>Ind.</td><td> <textarea id="ind" name="ind" row="8" cols="60" value="." ></textarea></td></tr>';
	echo '<tr>';
	echo '<td colspan="2" align="center"><input id="Guardarrec" name="Guardarrec"  class="btn btn-success" type="submit" value="Guardar" />';
	echo '<input id="Cancelar" name="Cancelar" type="submit"  class="btn btn-danger" value="Descartar" /></td>';
	echo '</tr>';
	echo '</form>';
	echo '</table>';
	echo '</div>';
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['reposo']))
{
	echo '<div style="text-align: center; position: absolute; top: 350px; " id="divdatos">';
	echo "<table class='table' width='100%'><tr>";
	echo "<form action='consultas.php' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
	echo '<input type="hidden" name="medico" id="medico" value="'.$_POST['medico'].'">';
	echo '<tr><td>Desde el </td>';
	echo '<input type="hidden" name="fechadesde" id="fechadesde" value="'.convertir_fechadmy($fila['fechadesde']).'"/>';
	echo '<td align="left">';
	echo '<span style="background-color: #ff8; cursor: default; "onmouseover="this.style.backgroundColor=\'#ff0\';" onmouseout="this.style.backgroundColor=\'#ff8\';" id="show_d2">'.convertir_fechadmy($fila['fechadesde']).'</span> </td>';
	echo '<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechadesde",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_d2",       // ID of the span where the date is to be shown
        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 

// desactivacion de 18 años pa tras

		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000) ||
							  (date.getTime() > today.getTime()+((365*18)*24*60*60*1000))							  
							  ) ? true : false;  }
    });
</script>
';

//	echo '</tr>';
//	echo '<tr>';
	echo '<td>Hasta el </td>';
	echo '<input type="hidden" name="fechahasta" id="fechahasta" value="'.convertir_fechadmy($fila['fechahasta']).'"/>';
	echo '<td align="left">';
	echo '<span style="background-color: #ff8; cursor: default; "onmouseover="this.style.backgroundColor=\'#ff0\';" onmouseout="this.style.backgroundColor=\'#ff8\';" id="show_d3">'.convertir_fechadmy($fila['fechahasta']).'</span> </td>';
	echo '<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechahasta",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_d3",       // ID of the span where the date is to be shown
        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 

// desactivacion de 18 años pa tras

		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000) ||
							  (date.getTime() > today.getTime()+((365*18)*24*60*60*1000))							  
							  ) ? true : false;  }
    });
</script>
';

	 echo '</td></tr>';
	echo '<tr><td>Por presentar</td><td> <textarea id="dobservacion" name="dobservacion" row="4" cols="60" value="." ></textarea></td>';
//	echo '</tr>';
//	echo '<tr>';

	echo '<td>Se debe incorporar el </td>';
	echo '<input type="hidden" name="fechaincorpora" id="fechaincorpora" value="'.convertir_fechadmy($fila['fechaincorpora']).'"/>';
	echo '<td align="left">';
	echo '<span style="background-color: #ff8; cursor: default; "onmouseover="this.style.backgroundColor=\'#ff0\';" onmouseout="this.style.backgroundColor=\'#ff8\';" id="show_d4">'.convertir_fechadmy($fila['fechaincorpora']).'</span> </td>';
	echo '<script type="text/javascript">
    Calendar.setup({
//		showAt(220, 250)
//		position       : 	{100,300},
        inputField     :    "fechaincorpora",     // id of the input field
        ifFormat       :    "%d/%m/%Y",     // format of the input field (even if hidden, this format will be honored)
        displayArea    :    "show_d4",       // ID of the span where the date is to be shown
        daFormat       :    "%A, %B %d, %Y",// format of the displayed date
        align          :    "Tl",           // alignment (defaults to "Bl")
        singleClick    :    true,
		weekNumbers    :    false, 

// desactivacion de 18 años pa tras

		dateStatusFunc :    function (date) { // disable weekend days (Saturdays == 6 and Subdays == 0)
						var today = new Date();
						return (
//							  (date.getTime() < today.getTime()-((365*18)*24*60*60*1000) ||
							  (date.getTime() > today.getTime()+((365*18)*24*60*60*1000))							  
							  ) ? true : false;  }
    });
</script>
';

	echo '</td></tr>';
	echo '<tr>';
	echo '<td colspan="2"><input type="checkbox" id="volver" name="volver">Debe volver a consulta? </td>' ; //</tr>';
	// echo '<tr>';

	echo '<td colspan="2" align="center"><input id="Guardarrep" name="Guardarrep"  class="btn btn-success" type="submit" value="Guardar" />';
	echo '<input id="Cancelar"  class="btn btn-danger" name="Cancelar" type="submit" value="Descartar" /></td>';
	echo '</tr>';
	echo '</form>';
	echo '</table>';
	echo '</div>';
}
//echo 'la cita '.$_SESSION['numeroregistro'];
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['constancia']))
{
	echo '<div style="text-align: center; position: absolute; top: 350px; " id="divdatos">';
	echo "<table class='table' width='100%'><tr>";
	echo "<form action='consultas.php' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
	echo '<input type="hidden" name="medico" id="medico" value="'.$_POST['medico'].'">';
	echo '<tr><td>Acudio a este centro asistencial para ';
	$sql="select cvalor from ".$_SESSION['bdd']."configura where cparametro='Constancia' order by cvalor	";
//	echo $sql;
	echo '<select name="notificacion" size="1">';
	$resultado=$db_con->prepare($sql);
	$resp=$resultado->execute();
	while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
		echo '<option class="form-control" value="'.$fila2['cvalor'].'" '.(($elcivil==$fila2['cvalor'])?'selected':'').'>'.$fila2['cvalor'].'</option>';}
 	echo '</select> '; 
	echo '</td>';
	echo '<td rowspan="3">Observaci&oacute;n</td><td rowspan="3"> <textarea id="dobservacion" name="dobservacion" row="4" cols="60" value="." ></textarea></td></tr>';
	$sql1="select fechapaso from ".$_SESSION['bdd']."consulta where numeroconsulta = '".$_SESSION['numeroregistro']."'";
	$resultado=$db_con->prepare($sql1);
	$resp=$resultado->execute();
	$reg1=$resultado->fetch(PDO::FETCH_ASSOC);
	$entrada=$reg1['fechapaso'];
	echo '<input type="hidden" name="fechadesde" id="fechadesde" value="'.$entrada.'">';
	echo '<tr><td>Desde '.$entrada.'</td></tr>';
	echo '<tr><td>Hasta la hora (por especificar)</td></tr>';
	echo '<tr>';
	echo '<td colspan="3" align="center"><input id="Guardarcons" name="Guardarcons"  class="btn btn-success" type="submit" value="Guardar" />';
	echo '<input  class="btn btn-danger" id="Cancelar" name="Cancelar" type="submit" value="Descartar" /></td>';
	echo '</tr>';
	echo '</form>';
	echo '</table>';
	echo '</div>';
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['informe']))
{
	echo '<div style="text-align: center; position: absolute; top: 350px; " id="divdatos">';
	echo "<table class='table' width='100%'><tr>";
	echo "<form action='consultas.php' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
	echo '<input type="hidden" name="medico" id="medico" value="'.$_POST['medico'].'">';
	echo '<tr>';
	echo '<td >Dx</td><td > <textarea id="dx" name="dx" row="4" cols="60" value="." ></textarea></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td >Observaci&oacute;n</td><td > <textarea id="dobservacion" name="dobservacion" row="4" cols="60" value="." ></textarea></td></tr>';
	echo '<tr>';
	echo '<td colspan="3" align="center"><input id="Guardarinf" name="Guardarinf"  class="btn btn-success" type="submit" value="Guardar" />';
	echo '<input  class="btn btn-danger" id="Cancelar" name="Cancelar" type="submit" value="Descartar" /></td>';
	echo '</tr>';
	echo '</form>';
	echo '</table>';
	echo '</div>';
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['orden']))
{
//	echo '<script language="javascript">alert("receipe")</script>';
}
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and ($_POST['historia']))
{
//	echo '<script language="javascript">alert("receipe")</script>';
}
//echo 'ref '.$_POST['Continuarref'];
if (($_POST['medico']) and ($_SESSION['numeroregistro']) and (($_POST['referencia']) and (!$_POST['Continuarref'])))
{
//	echo '<script language="javascript">alert("receipe")</script>';

	echo '<div style="text-align: center; position: absolute; top: 350px; " id="divdatos">';
	echo "<form action='consultas.php' id='formref' name='formref' enctype='multipart/form-data' method='post'>";
	echo "<table class='table' width='100%'><tr>";
	echo '<input type="hidden" name="medico" id="medico" value="'.$_POST['medico'].'">';

	// echo '<input type="hidden"  name="ciudad" id="ciudad" value="" size="3"></td>';
	echo '<div id="demo" ><td>Especialidad </td><td><div id="demoMed">'; // style="width:100px;"
		$sql="select * from  ".$_SESSION['bdd']."especialidad order by nombre";

		echo '<select name="especialidad" id="especialidad" size="1" onChange="cargaContenido(this.id)">';
			echo '<option value="x">Seleccione Especialidad</option>'; 		
			$resultado=$db_con->prepare($sql);
			$resp=$resultado->execute();
			while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
				echo '<option value="'.$fila2['codigo'].'">'.$fila2['nombre'].'</option>'; 		
			}
		echo '</select>'; 

	echo '</td></tr><tr>';

	$sql="select * from  ".$_SESSION['bdd']."ninstituto where codesp = '$especialidad' order by instituto";
//	echo '<div id="demo" ><td>Especialista</td><td> '; // style="width:100px;"
	echo '<td>Especialista</td><td> '; // style="width:100px;"
	echo '<select name="especialistas" id="especialistas" size="1">';
		echo '<option value="x">Seleccione Especialista</option>'; 		
		$resultado=$db_con->prepare($sql);
		$resp=$resultado->execute();
		while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
				// echo '<option value="'.$fila2['codmed'].'">'.$fila2['instituto'].'</option>'; 
				}
		echo '</select> '; 
	echo '</td>';
	echo '<input id="referencia" name="referencia" type="hidden" value="Referencia" >';
	echo '</tr>';
	echo '<tr>';
	echo '<td colspan="2" align="center"><input id="Continuarref" name="Continuarref"  class="btn btn-success" type="submit" value="Continuar Referencia" />';
	echo '</td>';
	echo '</tr>';
	echo '</table>';
	echo '</form>';
	echo '</div>';

}

// continuacion de la referencia
// revisar el monto por ano
// revisar el monto que lleva actualmente
// mas lo que se va a consumir en los examenes

if (($_POST['medico']) and ($_SESSION['numeroregistro']) and (($_POST['referencia']) and ($_POST['Continuarref'])))
{
	echo '<div style="text-align: center; position: absolute; top: 350px; " id="divdatos">';
	echo "<table class='table' width='100%'><tr>";
	echo "<form action='consultas.php' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
	echo '<input type="hidden" name="medico" id="medico" value="'.$_POST['medico'].'">';
	echo '<input type="hidden" name="especialistas" id="especialistas" value="'.$_POST['especialistas'].'">';
	echo '<input type="hidden" name="especialidad" id="especialidad" value="'.$_POST['especialidad'].'">';
	$ano=substr(ahora($db_con),0,4);
	$sql="select * from ".$_SESSION['bdd']."cupo where fecha = '$ano'";
	$resultado=$db_con->prepare($sql);
	$resp=$resultado->execute();
		
	if ($resultado->rowCount() < 1)
		die('<h1>Lo siento... no hay cupo asignado para este a&nacute;o<br>Participe a administraci&oacute;n para subsanar el inconveniente</h1>');
	
	$filatope=$resultado->fetch(PDO::FETCH_ASSOC);
	$topetitular = $filatope['titular'];
	
	// reviso los consumos
	$sql="select sum(montoconsumo) as consumido from ".$_SESSION['bdd']."referencias where (substr(realizado,1,4) = '$ano') and (cedulatitular = '".$_SESSION['cedulatitular']."')";
	$resultado=$db_con->prepare($sql);
	$resp=$resultado->execute();
	$filaconsumo=$resultado->fetch(PDO::FETCH_ASSOC);
	$consumo = $filaconsumo['consumido'];
	
	if ($consumo > $topetitular)
		die('<h1>Lo siento... Ha agotado el cupo asignado para este a&nacute;o<br>Puede realizar los ex&aacute;menes por su cuenta</h1>');

	$sql="select * from ".$_SESSION['bdd']."ninstituto where codmed = '".$_POST['especialistas']."'";
//	echo $sql;
	$resultado=$db_con->prepare($sql);
	$resp=$resultado->execute();
	$fila=$resultado->fetch(PDO::FETCH_ASSOC);
	$Tipo=$fila['tipo'];
	$hoy = substr(ahora($db_con),0,10);

	// consulto en costos
	$sql="select *, 'Su Especialidad' as descripcion, idregistro as nroregistro from ".$_SESSION['bdd']."costos where codigo = '".$_POST['especialistas']."' and ('$hoy' >= fechadesde and '$hoy' <= fechahasta)";
	$result_costos=$db_con->prepare($sql);
	$resp=$result_costos->execute();
	if (!$resp) die($sql);
	// echo $sql;
	if ($result_costos->rowCount() < 1)
		die('<h1>Lo siento... no hay montos asignados para este per&iacute;odo<br>Participe a administraci&oacute;n para subsanar el inconveniente</h1>');
	
	if ($fila['tipo'] == 'Laboratorio')
	{
		// consulto en costos laboratorio
		$resultado=$db_con->prepare($sql);
		$resp=$resultado->execute();
		if (!$resp) die($sql);
		$fila=$resultado->fetch(PDO::FETCH_ASSOC);
		$elid=$fila['idregistro'];
		$sql="select * from ".$_SESSION['bdd']."costoslaboratorio where registrocosto = '".$elid."'";
		$result_costos=$db_con->prepare($sql);
		$resp=$result_costos->execute();
		if (!$resp)
			die($sql);
	}
//	echo $sql;
	
	$cancelar=array();
	$registros=0;
	$columnas=0;
	echo '<tr><td>Cupo Anual</td><td align="right"><input type="text" id="cupo" size="8" name="cupo" value="'.($topetitular).'"  readonly="readonly"></td>';
	echo '<td>Consumido</td><td align="right"><input type="text" id="consumido" size="8" name="consumido" value="'.($consumo).'" readonly="readonly"></td>';
	echo '<td>Ahora</td><td align="right"><input type="text" id="aqui" name="aqui" size="8" value="0"  readonly="readonly""></td>';
	echo '<td>Por Consumir </td><td align="right"><input class="fondo_rojo" type="text" size="8" id="xconsumir" name="xconsumir" value="'.number_format((($topetitular-$consumo)-$aqui),2,',','.').'" readonly"></td>';

	echo '</tr>';
	while($fila=$result_costos->fetch(PDO::FETCH_ASSOC)) 
	{
		$columnas++;
		$registros++;
		if ($columnas == 1)
			echo '<tr>';
		echo '<td align="left">'.$fila['descripcion'].'</td>';
		echo '<td align="right">'.number_format($fila['costo'],2,".",",");
		// echo '</td>';
		// echo '<td class="centro azul">';
		echo '<input type="checkbox" id="cancelar'.$registros.'" name="cancelar'.$registros.'" value="'.$fila["nroregistro"] .'" onClick="calccanc()" ';
		if ($fila['costo'] == 0) echo ' disabled="true"';

		// disabled="true" ';
		echo '></td>';
		if ($columnas == 3)
		{
			$columnas=0;
			echo '<tr>';
		}
	}
	
//	echo '<tr><td>Alergico a</td><td> <textarea id="realizarse" name="realizarse" row="4" cols="60" value="." ></textarea></td></tr>';
	echo '<td>Realizar </td><td colspan="3"><select id="realizarse" name="realizarse" size="1">';
	$sql="select cvalor from ".$_SESSION['bdd']."configura where cparametro='Referencia' and activado = 1 order by cvalor";
	$resultado=$db_con->prepare($sql);
	$resp=$resultado->execute();
	while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) 
	{
		echo '<option class="form-control" value="'.$fila2['cvalor'].'" '.(($elstatus==$fila2['cvalor'])?'selected':'').'>'.$fila2['cvalor'].'</option>';}
	echo '</select></td>'; 
	echo '<td>Observaci&oacute;n</td><td class="form-control" colspan="2"> <textarea id="dobservacion" name="dobservacion" row="4" cols="30" value="." ></textarea></td></tr>';

	echo '<td><input type="hidden" id="registros" name="registros" value="'.$registros.'" "></td>';
	echo '<td><input type="hidden" id="tipo" name="tipo" value="'.$Tipo.'" "></td>';
	
	echo '<tr>';
	echo '<td colspan="3" align="center"><input type="button" name="calculo" value="Marcar Todos" onClick="marcar()"></td>	';
	echo '<td colspan="3" align="center"><input type="button" name="calculo" value="Desmarcar Todos" onClick="desmarcar()"></td>	';
	echo '<td colspan="3" align="center"><input id="Guardarref" name="Guardarref"  class="btn btn-success" type="submit" value="Guardar Referencia" />';
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
		
//	echo '<form id="llamar2" name="llamar2" action="" onsubmit="examenes(\'diagnostico.php\'); return false">'; // 
	echo "<form action='consultas.php' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
	echo '<table><tr>';
//		echo '<div style="text-align: center; position: absolute; top: 400px; " id="losexamenes">';
		echo '<input type="hidden" name="medico" id="medico" value="'.$_POST['medico'].'">';
		echo '<td><input id="diagnostico" name="diagnostico"  class="btn btn-default" type="submit" value="Diagn&oacute;stico" '.$readonly.'/>';  // disabled
//		echo '</div>';
	echo '<input id="signos" name="signos"  class="btn btn-info" type="submit" value="Signos Vitales" '. $readonly.'/>';
	echo '<input id="alergias" name="alergias" type="submit"  class="btn btn-success" value="Alergias"  '. $readonly.'/>';
	echo '<input id="referencia" name="referencia" type="submit"  class="btn btn-warning" value="Referencia"  '. $readonly.'/></td>';
	echo '<td><input id="reposo" name="reposo" type="submit"  class="btn btn-danger" value="Reposo"  '. $readonly.'/></td>';
	echo '<td><input id="constancia" name="constancia" type="submit"  class="btn btn-default" value="Justificativo"  '. $readonly.'/></td>';
	echo '<td><input id="informe" name="informe" type="submit"  class="btn btn-info" value="Informe"  '. $readonly.'/></td>';
	echo '<td><input id="recipe" name="recipe" type="submit"  class="btn btn-danger" value="R&eacute;cipe"  '. $readonly.'/></td>';
//	echo '<td><input id="orden" name="orden" type="submit" value="Orden Hospit." '.  $readonly.'/></td>';
	echo '<td><input id="historia" name="historia" type="submit"  class="btn btn-success" value="Historia"  '. $readonly.'/></td>';
	echo '</tr><tr><td><input id="Finalizar" name="Finalizar" type="submit"  class="btn btn-warning" value="Finalizar Paciente" />';
	echo '<input id="StandBy" name="StandBy" type="submit"  class="btn btn-danger" value="StandBy Paciente" > ';
	echo '<input id="Descanso" name="Descanso" type="submit"  class="btn btn-info" value="Descanso Medico">  </td></tr>';
	echo '</table>';
	echo '</form>';

}
?>

</body></html>

<?php
?>
