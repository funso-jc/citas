<?php
include("head.php");
?>
<head>
</head>
<?php
$sqlp="select * from ".$_SESSION['bdd']."_consulta where pasocons = 0 order by fechconsul";
$resp=mysql_query($sqlp);
echo "<table class='basica 100 hover' width='100%'><tr>";
echo '<tr><th>Paciente</th><th>Motivo</th><th>Fecha / Hora</th><th>Observacion</th>';
while($resulp=mysql_fetch_assoc($resp))
{
	echo '<tr>';
	echo '<td>'.$resulp['nombrepaciente'].'</td>';
	// echo '<td>'.$resulp['cedbenefic'].'</td>';
	echo '<td>'.$resulp['motivocon'].'</td>';
	echo '<td>'.$resulp['fechconsul'].'</td>';
	echo '<td>'.$resulp['obsgeneral'].'</td>';
	echo '</tr>';
}
$sqlp="select now() as fecha";
$resp=mysql_query($sqlp);
$resulp=mysql_fetch_assoc($resp);
$fechap=$resulp['fecha'];
// $fechap=explode
echo '<tr><td align="center" colspan="4">'.$fechap.'</td></tr>';
//echo '<tr><td>'.date("Y-n-j H:i:s").'</td></tr>';
echo '</table>';

$sqlp="select cvalor from ".$_SESSION['bdd']."_configura where cparametro = 'NroPacientes'";
$resp=mysql_query($sqlp);
$filadr=mysql_fetch_assoc($resp);
$nropacientes=$filadr['cvalor'];
// veo quien esta libre primero
$sqlp="select * from ".$_SESSION['bdd']."_internos where disponible = 1 order by hfdisponibilidad limit 1";
$resp=mysql_query($sqlp);
echo '<div id="asignar">';
if (mysql_num_rows($resp) > 0)
{
	$filadr=mysql_fetch_assoc($resp);
	$nombredr=$filadr['nombre'];
	$codigodr=$filadr['codigomedico'];

	$sqlp="select * from ".$_SESSION['bdd']."_consulta where pasocons = 0 order by fechconsul limit $nropacientes 	";
	$resp=mysql_query($sqlp);
// 	echo $sqlp;
	echo "<table class='basica 100 hover' width='100%'><tr>";
	echo '<tr><th>Paciente</th><th>Motivo</th><th>Fecha / Hora</th><th>Observacion</th><th>Pasar con</th>';
	while($resulp=mysql_fetch_assoc($resp))
	{
		echo '<tr>';
		echo '<td>'.$resulp['nombrepaciente'].'</td>';
	// echo '<td>'.$resulp['cedbenefic'].'</td>';
		echo '<td>'.$resulp['motivocon'].'</td>';
		echo '<td>'.$resulp['fechconsul'].'</td>';
		echo '<td>'.$resulp['obsgeneral'].'</td>';
		echo '<td>'.$nombredr.'</td>';
		echo '<td>';
		echo '<form id="pasar" name="pasar" action="" onsubmit="pasarconsultorio(); return false">';
//		echo "<p /><form action='' name='form2'>";
		echo "<input type='hidden' id='codigodr' name='codigodr' value='".$codigodr."'>";
		echo "<input type='hidden' id='nroregistro' name='nroregistro' value=".$resulp['numeroconsulta'].">";
		echo "<input type='submit' value='Pasar a Consultorio' onclick='return confirm(\"¿Está seguro que desea pasar este paciente?\")'>";
		echo '</form>';
		echo '</td>';
		echo '</tr>';
	}
//	echo '<tr>';
//	echo '</tr>';
}
echo '</div>';
?>