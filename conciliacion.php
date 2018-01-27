<?php
include("head.php");
include("paginar.php");
//include("popcalendario/escribe_formulario.php");
if (!$link OR !$_SESSION['empresa']) {
	include("noempresa.php");
	exit;
}
if ($accionIn == 'Anadir') 
	$onload="onload=\"foco('cta')\""; 
else
	$onload="onload=\"foco('nactivo')\"";
?>

<body <?php if (!$bloqueo) {echo $onload;}?>>

<script src="ajxconc.js" type="text/javascript"></script>
<?php
 
$readonly=" readonly='readonly'";
include("arriba.php");
$menu61=1;include("menusizda.php");
$cta = $_GET['cta'];
$nactivo=$_GET['nactivo'];
$ip = $_SERVER['HTTP_CLIENT_IP'];
if (!$ip) {$ip = $_SERVER['REMOTE_ADDR'];}
?>
<?php
if ($accionIn=="Verificar") 
{
   // echo '<div id="div1">';
   $sql= "SELECT nombre_ban,date_format(fecha_conc, '%d/%m/%Y') AS fecha, cod_banco, cue_banco FROM ".$_SESSION['bdd']."_sgcaf843 where nro_cta_ba='$codigo' and emitircheque='1'"; 
//   echo $sql.'<br>';
	$result=mysql_query($sql);
	$b= mysql_fetch_assoc($result);
	$nombre=$b['nombre_ban']; 
	$fechadep= $b['fecha']; 
	$cod_banco= $b['cod_banco'];
	$cue_banco=$b['cue_banco'];  
	$h= '35';
	$d = suma_fechas($fechadep,$h); 
	$a=explode("/", $d);
	$b = $a[0]."-".$a[1]."-".$a[2]; 
	$a[0] = '01'; 
	$j= $a[0]."-".$a[1]."-".$a[2]; 
	$hh = date($a[0]."-".$a[1]."-".$a[2]); 
	$hu= '1'; 
	$fecha = restar_fechas($hh,$hu); 
	$aa=explode("/", $fecha);
	$bb = $aa[0]."".$aa[1]."".$aa[2];
		$hoy = date("d/m/Y"); 
		$ho=explode("/", $hoy);
		$bbb = $ho[0]."-".$ho[1]."-".$ho[2]; 
$MiTimesTamp = mktime(0,0,0,$ho[1],$ho[0], $ho[2]);  
$MiTimesTamp1 = mktime(0,0,0,$aa[1],$aa[0],$aa[2]);     
	if ($MiTimesTamp<$MiTimesTamp1) 
	{
	echo '<h2> NO SE PUEDE CONCILIAR ESTE BANCO. TODAVIA NO HA TERMINADO EL MES </h2>'; 
	$accionIn='';  
	}
	else if ($MiTimesTamp>=$MiTimesTamp1)
	{
	 echo "<form action='conciliacion.php?accionIn=Verificar1' name='form1' method='post' onsubmit='return conciliacion(form1)'>"; 
	$fecha1= convertir_fecha($fecha);
	$saldo=buscar_saldo_f810($cue_banco,$fecha1);
//    echo $cod_banco; 
	pantalla_verificar($result,$accionIn,$codigo,$nombre,$fecha,$cod_banco,$saldo,$fecha1,$cue_banco);
	echo "<input type = 'submit' value = 'Enviar'>";
	}
}


if ($accionIn=="Verificar1") 
{
   //echo $fecha1; 
   //echo $cod_banco; 
   //echo $saldo_bancos;
   //echo $saldo_libros; 
   echo $diferencia; 
   echo $diferenciacon; 
   //echo $cheques; 
   //echo $depositos; 
  // echo $cue_banco;

 ///////////////////////MODIFICAR 840 PARA LOS CHEQUES COBRADOS//////////////
   for ($i=1;$i<$registros;$i++)
	{
		$variable='cancelar'.($i);
		if (!empty($$variable)) 
		{
	    $numero=$$variable; 
		$sql="UPDATE ".$_SESSION['bdd']."_sgcaf840 SET cobrados='1', fecha_cobrados='$fecha1'
		WHERE mche_orden='$numero' and mche_banco='$cod_banco'";
	echo $sql;
	echo "<p />";
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		}	
	} 
	////////////////////////MODIFICAR 840 PARA LOS CHEQUES EN TRANSITO//////////
    $sql= "SELECT *,date_format(mche_fecha, '%d/%m/%Y') AS fechax  FROM ".$_SESSION['bdd']."_sgcaf840, ".$_SESSION['bdd']."_sgcaf843 where nro_cta_ba='$codigo' and cod_banco=mche_banco and mche_fecha <= '$fecha1' and cobrados='0' and emitircheque='1' and mche_statu='I' order by mche_fecha"; 
	$result=mysql_query($sql);
	echo $sql; 
    while ($fila2 = mysql_fetch_assoc($result)) 
		{
	    $numero=$fila2['mche_orden']; 
		$cod_ban=$fila2['cod_banco']; 
		//echo $cod_banco; 
		$sql="UPDATE ".$_SESSION['bdd']."_sgcaf840 SET  fecha_cobrados='$fecha1'
		WHERE mche_orden='$numero' and mche_banco='$cod_ban' ";
		echo $sql;
		echo "<p />";
		mysql_query($sql)or die ("<p />El usuario $usuario no tiene permisos para añadir Clientes o cuenta ya existente.<br>".mysql_error()."<br>".$sql);
		    }
//////////////////////////////
//////////////////////MODIFICAR 820 PARA LOS DEPOSITOS COBRADOS//////////////
   for ($i=1;$i<$j;$i++)
	{
		$variable='cancelard'.($i);
		if (!empty($$variable)) 
		{
	    $numero=$$variable; 
		$sql="UPDATE ".$_SESSION['bdd']."_sgcaf820 SET cobrado='1', fecha_cobro='$fecha1'
		WHERE com_refere='$numero' and com_cuenta='$cue_banco'";
		echo $sql;
		echo "<p />";
		mysql_query($sql)or die ("<