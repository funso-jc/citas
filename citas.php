<?php
include("head.php");
?>
<head>
<script type="text/javascript">
function caca()
{
var algo;
if (window.XMLHttpRequest)
  {
  algo=new XMLHttpRequest();
  }
else
  {
  algo=new ActiveXObject("Microsoft.XMLHTTP");
  }
algo.onreadystatechange=function()
  {
  if (algo.readyState==4 && algo.status==200)
    {
    document.getElementById("caca").innerHTML=algo.responseText;
    }else{
//porsi quieres poner un mensaje de cargando
document.getElementById("caca").innerHTML="Cargando datos...";
}
  }
algo.open("GET","pendientes.php",true);
algo.send();
}

function enproceso()
{
var algo2;
if (window.XMLHttpRequest)
  {
  algo2=new XMLHttpRequest();
  }
else
  {
  algo2=new ActiveXObject("Microsoft.XMLHTTP");
  }
algo2.onreadystatechange=function()
  {
  if (algo2.readyState==4 && algo2.status==200)
    {
    document.getElementById("enproceso").innerHTML=algo2.responseText;
    }else{
//porsi quieres poner un mensaje de cargando
document.getElementById("enproceso").innerHTML="Procesando datos...";
}
  }
algo2.open("GET","enproceso.php",true);
algo2.send();
}

</script>

<script language="JavaScript" type="text/javascript" src="consultarpcte.js"></script>
<script language="JavaScript" type="text/javascript" src="ingresarpcte.js"></script>
<script language="JavaScript" type="text/javascript" src="pasarconsultorio.js"></script>
</head>
<script type="text/javascript">
setInterval("caca()",5000);
setInterval("enproceso()",5000);
</script>
<div style="text-align: center; position: absolute; top: 400px; left:0px; float: left" id='caca'></div>
<div style="text-align: center; position: absolute; top: 400px; left:500px; float: left;" id='enproceso'></div>
<?php
// include("paginar.php");
/*
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
/*
if ($_GET['emp'] == 1) {$_GET['n'] = 1;}

*/
$onload="onload=\"foco('cedula')\""; 
?>
<style type="text/css">
#caca{background-color:#CCC;
width:500px;
margin:20px auto;
padding:10px 10px 10px 10px;
cursor:pointer;
}

#enproceso{background-color:#CDD;
width:500px;
margin:20px auto;
padding:10px 10px 10px 10px;
cursor:pointer;
}
</style>

<body ><?php // echo $onload;?>


<?php

$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;
include("menusizda.php");
$cedula = $_GET['cedula'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
// echo '<div = "datos">';
echo '<form id="consulta" name="consulta" action="" onsubmit="ConsultarPaciente(\'consultapaciente.php\'); return false">';
echo "<table class='basica 100 hover' width='100%'><tr>";
echo '<td align="center" colspan="3">C&eacute;dula ';
$prueba=0;
echo '<input name="cedula" type="text" id="cedula" value="'.($prueba==1?'09377388':'').'" size="14" maxlength="14" required/>';
echo '<input class="btn btn-info" type="submit" value="Consultar" /></td></tr>';
echo '</form>';
echo '<form id="ingresar" name="ingresar" action="" onsubmit="IngresarPaciente(\'ingresarpaciente.php\'); return false">';
// echo "<form action='citas.php?accion=Buscar' id='form1' name='form1' enctype='multipart/form-data' method='post'>";
echo '<div id="resultado">';
echo '<tr><td>Titular<input type"text" id="cedulatitular" name="cedulatitular" value="" readonly></td>';
echo '<td>Nombre Titular<input type"text" id="nombretitular" name="nombretitular" size="50" value="" readonly></td></tr>';
echo '<tr><td>Beneficiario <input type"text" id="cedulabeneficiario" name="cedulabeneficiario" value="" readonly>';
echo '<td>Nombre Beneficiario <input type"text" id="nombrebeneficiario" name="nombrebeneficiario" size="50" value="" readonly></td></tr>';
echo '<tr><td>Parentesco <input type"text" id="parentesco" name="parentesco" value="" readonly></td>';
echo '<td>Status<input type"text" id="status" name="status" value="" readonly></td></tr>';
echo '<input type="hidden" name="existe" id="existe" value="0">';
echo '<td>Motivo de la Cita </td><td><select id="motivo" name="motivo" size="1">';
$sql="select cvalor from ".$_SESSION['bdd']."configura where cparametro='Motivo' order by cvalor	";
$resultado=$db_con->prepare($sql);
$resultado->execute();
while ($fila2 = $resultado->fetch(PDO::FETCH_ASSOC)) {
	echo '<option value="'.$fila2['cvalor'].'" '.(($elstatus==$fila2['cvalor'])?'selected':'').'>'.$fila2['cvalor'].'</option>';}
echo '</select></td>'; 
echo '<tr><td>Observaciones</td><td> <textarea id="observaciones" name="observaciones" row="4" cols="50" value="." ></textarea></td></tr>';

echo '<tr><td colspan="2" align="center">';
// echo "<input type = 'submit' value = 'Buscar'>";
echo "<input class='btn btn-success' type = 'submit' id='ingresar' name='ingresar' value = 'Colocar en Lista' >"; // disabled
echo '</td></tr>';
echo '</div>';
echo "</form>";
// echo '</div>';
 
?>

<?php include("pie.php");?></body></html>

