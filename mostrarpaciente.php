<?php
session_start();
extract($_GET);
extract($_POST);
extract($_SESSION);
	echo "<table class='basica 100 hover' width='60%'><tr>";
	echo '<td>Buen d&iacutea Dr(a) '.$_SESSION['nombredr'].'</td><td>Numero de Cita '.$_SESSION['numeroregistro'].'</td>';
 
	echo '<tr><td>Titular<input type"text" id="cedulatitular" name="cedulatitular" value="'.$_SESSION['cedulatitular'].'" readonly></td>';
	echo '<td>Nombre Titular<input type"text" id="nombretitular" name="nombretitular" size="50" value="'.$_SESSION['nombrepaciente'].'" readonly></td></tr>';
	echo '<tr><td>Beneficiario <input type"text" id="cedulabeneficiario" name="cedulabeneficiario" value="'.$_SESSION['cedulatitular'].'" readonly></td>';
	echo '<td>Nombre Beneficiario <input type"text" id="nombrebeneficiario" name="nombrebeneficiario" size="50" value="'.$_SESSION['nombrepaciente'].'" readonly></td></tr>';
	echo '<tr><td>Motivo de la Cita </td><td><input type"text" id="motivo" name="motivo" value="'.$_SESSION['motivo'].'" readonly></td></tr>';
	echo '<tr><td>Observaciones</td><td> <textarea id="observaciones" name="observaciones" row="4" cols="50" readonly>'.$_SESSION['observaciong'].' </textarea></td></tr>';
	echo '</table>';
?>
