<?php
include("head.php");
$sqlp="select * from ".$_SESSION['bdd']."consulta, ".$_SESSION['bdd']."internos where (pasocons = 1 or pasocons = 2) and (".$_SESSION['bdd']."internos.codigomedico = ".$_SESSION['bdd']."consulta.codigomedico) order by fechconsul desc";
$resp=$db_con->prepare($sqlp);
$resp->execute();
echo "<table class='table' width='100%'><tr>";
echo '<tr><th>Paciente</th><th>En Consulta con </th><th>Tipo</th><th></th>';
if ($medicodisponible == 1)
	echo '<th>Medico</th><th>Pasa a Consulta?</th>';
echo '</tr>';
$cuantos = 0;
while($resulp=$resp->fetch(PDO::FETCH_ASSOC))
{
	echo '<tr>';
	echo '<td>'.$resulp['nombrepaciente'].'</td>';
	// echo '<td>'.$resulp['cedbenefic'].'</td>';
	echo '<td>'.$resulp['nombre'].'</td>';
	echo '<td>'.($resulp['pasocons']==1?'En Consulta':($resulp['pasocons']==2?'Finalizada':'StandBy')).'</td>';
	echo '<td>'.''.'</td>';
	if ($medicodisponible == 1)
	{
		$cuantos++;
//		if ($cuantos < $nropacientes)
		{
			echo '<td>'.$nombredr.'</td>';
			echo '<td>';
			echo '<form id="pasar" name="pasar" action="" onsubmit="pasarconsultorio(); return false">';
//		echo "<p /><form action='' name='form2'>";
			echo "<input type='hidden' id='codigodr' name='codigodr' value='".$codigodr."'>";
			echo "<input type='hidden' id='nroregistro' name='nroregistro' value=".$resulp['numeroconsulta'].">";
			echo "<input class='btn btn-success' type='submit' value='Pasar a Consultorio' onclick='return confirm(\"¿Está seguro que desea pasar este paciente?\")'>";
			echo '</form>';
			echo '</td>';
		}
	}
	echo '</tr>';
}
$sqlp="select now() as fecha";
$resp=$db_con->prepare($sqlp);
$resp->execute();
$resulp=$resp->fetch(PDO::FETCH_ASSOC);
$fechap=$resulp['fecha'];
// $fechap=explode
echo '<tr><td align="center" colspan="4">'.$fechap.'</td></tr>';
//echo '<tr><td>'.date("Y-n-j H:i:s").'</td></tr>';
echo '</table>';

/*
echo '<div id="asignar">';
if ($resp->rowCount() > 0)
{
	$filadr=mysql_fetch_assoc($resp);
	$nombredr=$filadr['nombre'];
	$codigodr=$filadr['codigomedico'];

	$sqlp="select * from ".$_SESSION['bdd']."_consulta where pasocons = 0 order by fechconsul limit $nropacientes 	";
	$resp=mysql_query($sqlp);
// 	echo $sqlp;
	echo "<table class='table' width='100%'><tr>";
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
*/
?>