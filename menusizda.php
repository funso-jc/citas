<?php
session_start();
$_SESSION['bdd']='';

// <DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd" 
// <DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd" 
// <DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" 
	 
// <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
// <html xmlns="http://www.w3.org/1999/xhtml">

// body>

/*

<div id="mainContainer">
<div id="dhtmlgoodies_menu">
	<ul>
		<li><a href="">Actualizar</a>
			<ul>
				<li><a href="regtitular.php">Titulares</a></li>
				<li><a href="regbenef.php">Familiares</a></li>
				<li><a href="regexternos.php">Especialistas/Laboratorios</a></li>
				<li><a href="act_costosexamenes.php">Costos Ex&aacute;menes</a></li>
				<li><a href="regespe.php">Especialidades</a></li>
			</ul>
		</li>
		<li><a href="citas.php">Citas</a>
		<li><a href="consultas.php">Consultas</a>
	</ul>
  </div>
  <p>&nbsp;</p>
</div>


<?php
//body>
//html>
*/
{
	date_default_timezone_set('America/Caracas'); 
	menu_normal($db_con);
}

?>
<div class="body-container"> 
  <div class="container">
      <div class='alert alert-success'>
  		<button class='close' data-dismiss='alert'>&times;</button>
  			<strong>Bienvenido <?php echo $_SESSION['user_session']; ?></strong>.
      </div>
  </div>
</div>
</body>
</html>

<?php

function buscarpermiso($valor,$permisomenu) {
	for ($i=0; $i<count($permisomenu);$i++) {
		if ($permisomenu[$i] == $valor) {
			return 1;}
	}
return 0;
}

function menu_normal($db_con)
{
?>

<!-- Navbar -->
<div class="navbar navbar-default" role="navigation">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#"></a>
  </div>

  <div class="navbar-collapse collapse">
    <!-- Left nav -->
    <ul class="nav navbar-nav"> <!-- navbar-right"> -->
  		<li><a href="#">Reportes<span class="caret"></span></a>
  			<ul class="dropdown-menu">
  				<li><a href="#">Actualizar<span class="caret"></span></a>
  					<ul class="dropdown-menu">
						<li><a href="regtitular.php">Titulares</a></li>
						<li><a href="regbenef.php">Familiares</a></li>
						<li><a href="regexternos.php">Especialistas/Laboratorios</a></li>
						<li><a href="act_costosexamenes.php">Costos Ex&aacute;menes</a></li>
						<li><a href="regespe.php">Especialidades</a></li>
  					</ul>
  				</li>
  				<li class="divider"></li>
  			</ul>
			<li><a href="citas.php">Citas</a>
			<li><a href="consultas.php">Consultas</a>
  	  </li>
    </ul>

    <ul class="nav navbar-nav navbar-right">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
  			<span class="glyphicon glyphicon-user"></span>&nbsp;Hola <?php echo $_SESSION['user_session']; ?>&nbsp;<span class="caret"></span></a>
        <ul class="dropdown-menu">
                  <!-- <li><a href="#"><span class="glyphicon glyphicon-user"></span>&nbsp;View Profile</a></li> -->
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Salir</a></li>
        </ul>
      </li>
    </ul>
  </div>
</div>

<?php
}

function ddls($hoy)
{
	$ddls= date('l', strtotime($hoy));
	return $ddls;
}
