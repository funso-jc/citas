<html>
<head>
<script language="JavaScript" type="text/JavaScript">
/*****************************************************************************
Scroll de noticias con foto. Script creado por Tunait! (27/3/2004)
Si quieres usar este script en tu sitio eres libre de hacerlo con la condición de que permanezcan intactas estas líneas, osea, los créditos.
No autorizo a publicar y ofrecer el código en sitios de script sin previa autorización
Si quieres publicarlo, por favor, contacta conmigo.
javascript tunait com/
tunait@yahoo.com 
******************************************************************************/
var ancho = 250 //anchura del cuadro
var alto = 240 //altura del cuadro
var marco = 1 //0 para que notenga marco (borde)
var fondo = '#FFFFFF' //color de fondo del cuadro
var pausilla = 2000 //tiempo de la pausa en milisegundos (2000 = 2 segundos)
var destino = "_blank" //target en donde se quiera que se carguen los enlaces, en caso de usarlos.
var cursor = "default;"  //cursor que se quiera sobre el cuadro
var colTitular = '#006699' //color del texto del titular
var colTexto = '#999999' // color del texto de la noticia
var colFecha = '#3399FF' //color del texto de la fecha
var colEnlace = '#660000' //color del texto del enlace
var fuente = "arial" //fuente para los textos 
var tamTitular = '14' //tamaño de la fuente del titular
var tamTexto = '12' //tamaño de la fuente del texto de la noticia
var tamFecha = '10' // tamaño de la fuente de la fecha
var tamEnlace = '11' // tamaño de la fuente del enlace 
var alinImagen = 'center'//alineaci&oacute;n de la imagen
var margImagen = '3'//margen alrededor de la imagen
var masInfo = true //Determina si se usa o no el enlace. true para usarlo. false para omitirlo
var poneFecha = true //true para poner fecha. false para omitirla. Si no se quiere fecha, dejar las comillas vacías ""

function noticia(titular,texto,fecha,enlace,destino,imagen)
	{
	this.titular = titular
	this.texto = texto
	this.imagen = imagen
	this.fecha= fecha
	this.enlace = enlace
	this.destino = destino
	}
var noticias = new Array()

noticias[0]= new noticia("Titulo","Descrip","fecha","#","1.gif")
noticias[1]= new noticia("Titulo","Descrip","fecha","#","_blank","2.gif")

var det = false
var hayIma, claseIma
function escribe(){
document.write ('<div id="mami" style="width:' + ancho + 'px; height:' + alto + 'px; position:relative;  overflow:hidden ">')
document.write('<table bgcolor="' + fondo + '" border = "' + marco + '" width="' + ancho + '" height="100%"><tr><td valign="top">')
document.write ('<div id="uno" style="top:' + alto +'px; width:' + ancho + 'px; height:' + alto + 'px;  ">')
document.write ('<div class="titular">')
document.write (noticias[0].titular)
document.write ('</div>')
if(noticias[0].imagen != null){
	hayIma = true
	claseIma = 'imagen'
	}
else{
	hayIma = false
	claseIma = 'noImagen'
	}
document.write ('<div class="' + claseIma + '">')
document.write ('<img src="' + noticias[0].imagen + '">')
document.write ('</div>')
document.write ('<div class="fecha">')
document.write (noticias[0].fecha)
document.write ('</div>')
document.write ('<div class="texto">')
document.write (noticias[0].texto)
document.write ('</div>')
if(masInfo == true){
	document.write ('<a class="enlace" href="')
	document.write (noticias[0].enlace)
	document.write ('" target="' + destino + '">más información...</a>')
	}
document.write ('</div>')
document.write ('<div id="dos" style="top:' + (alto*2) +'px; width:' + ancho + 'px; height:' + alto + 'px; ">')
document.write ('<div class="titular">')
document.write (noticias[1].titular)
document.write ('</div>')
if(noticias[1].imagen != null){
	hayIma = true
	claseIma = 'imagen'
	}
else{
	hayIma = false
	claseIma = 'noImagen'
	}

document.write ('<div class="' + claseIma + '">')
document.write ('<img src="' + noticias[1].imagen + '">')
document.write ('</div>')
document.write ('<div class="fecha">')
document.write (noticias[1].fecha)
document.write ('</div>')
document.write ('<div class="texto">')
document.write (noticias[1].texto)
document.write ('</div>')
if(masInfo == true){
	document.write ('<a class="enlace" href="')
	document.write (noticias[1].enlace)
	document.write ('" target = "' + destino + '">más información...</a>')
	}
document.write ('</div>')
document.write('</td></tr></table>')
document.write ('</div>')
if(navigator.appName == "Netscape")
{altoUno = document.getElementById('uno').offsetHeight}
else
{altoUno = document.getElementById('uno').clientHeight}
document.getElementById('uno').onmouseover =function(){
	det = true
	clearTimeout(tiempo)
	}
document.getElementById('uno').onmouseout =function(){
	det = false;
	clearTimeout(tiempo)
	escrolea()
	}

document.getElementById('dos').onmouseover =function(){
	det = true
	clearTimeout(tiempo)
	}
document.getElementById('dos').onmouseout =function(){
	det = false;
	clearTimeout(tiempo)
	 escrolea()
	 
	}
}
desp = 1
var cont = 1
var pos,pos2
function escrolea(){
pos = document.getElementById('uno').style.top
pos = pos.replace(/px/,"");
pos = pos.replace(/pt/,"");
pos = new Number(pos);
pos2 = document.getElementById('dos').style.top
pos2 = pos2.replace(/px/,"");
pos2 = pos2.replace(/pt/,"");
pos2 = new Number(pos2);
pos -= desp
pos2 -= desp

if (pos == desp){
	var contenidos = ""
	document.getElementById('dos').style.top = alto + "px"
	document.getElementById('dos').childNodes[0].firstChild.nodeValue  = noticias[cont].titular
	if(noticias[cont].imagen != null){
		document.getElementById('dos').childNodes[1].firstChild.src = noticias[cont].imagen
		document.getElementById('dos').childNodes[1].className = 'imagen'
		}
	else{
		document.getElementById('dos').childNodes[1].className = 'noImagen'
		}
	if(poneFecha == true){
	document.getElementById('dos').childNodes[2].firstChild.nodeValue  = noticias[cont].fecha
	}
	document.getElementById('dos').childNodes[3].firstChild.nodeValue  = noticias[cont].texto
		if(masInfo == true){
		document.getElementById('dos').childNodes[4].href = noticias[cont].enlace 
		}
	document.getElementById('uno').style.top = 0
	if(cont == noticias.length-1)
		{cont=0}
	else{
		cont++
		}
	pausa()
	return false
	}
else{
	if (pos2 == desp){
		var contenidos = ""
		document.getElementById('uno').style.top = alto + "px"
		document.getElementById('uno').childNodes[0].firstChild.nodeValue  = noticias[cont].titular
		if(noticias[cont].imagen != null){
		document.getElementById('uno').childNodes[1].firstChild.src = noticias[cont].imagen
		document.getElementById('uno').childNodes[1].className = 'imagen'
		}
	else{
		document.getElementById('uno').childNodes[1].className = 'noImagen'
		}
		if(poneFecha == true){
		document.getElementById('uno').childNodes[2].firstChild.nodeValue  = noticias[cont].fecha
		}
		document.getElementById('uno').childNodes[3].firstChild.nodeValue  = noticias[cont].texto
		if(masInfo == true){
		document.getElementById('uno').childNodes[4].href  = noticias[cont].enlace
		}
		document.getElementById('dos').style.top = 0
		if(cont == noticias.length-1)
		{cont=0}
	else{
		cont++
		}
		pausa()
		return false
		}
	else{
		document.getElementById('uno').style.top = pos + "px"
		document.getElementById('dos').style.top = pos2 + "px"
		}
	}
tiempo = window.setTimeout('escrolea()',50)
}
var tiempo
function pausa()
{
clearTimeout(tiempo)
if (det == false){
	tiempo = setTimeout ('continuar()',2000)
	}
}
function continuar()
{
if(det == false)
	{escrolea()}
}

document.write('<style type="text/css">')
document.write ('#uno {')
document.write ('color: #006699;')
if(cursor == "pointer" || cursor == "hand"){
cursor = (navigator.appName == "Netscape")?'pointer;':'hand;';
}
document.write ('cursor:' + cursor + ";")
document.write ('position:absolute;}')
document.write ('#dos {')
document.write ('color: #006699;')
document.write ('cursor:' + cursor + ";")
document.write ('position:absolute;}')
document.write ('.titular{')
document.write ('color:' + colTitular +';')
document.write ('font-family:' + fuente + ';')
document.write ('font-size :' + tamTitular + 'px;font-weight:bold}')
document.write ('.texto{')
document.write ('color:' + colTexto + ';')
document.write ('font-family:' + fuente + ';')
document.write ('font-size:' + tamTexto + 'px;}')
if(poneFecha == true){
document.write ('.fecha{')
document.write ('color:' + colFecha +';')
document.write ('font-family:' + fuente + ';')
document.write ('font-size :' + tamFecha + 'px;font-weight:bold}')
}
else{
document.write ('.fecha{display: none;}')
}
document.write ('.enlace{')
document.write ('color:' + colEnlace + ';')
document.write ('font-family:' + fuente + ';')
document.write ('font-size:' + tamEnlace + 'px;}')
document.write ('.imagen{')
document.write ('text-align:' + alinImagen + ';')
document.write ('margin: ' + margImagen + 'px;}')
document.write ('.noImagen{display:none;}')
document.write ('</style>')
</script>
</head>

<body topmargin="30" marginheight="0" onload="escrolea()">
<div align="center">
  <p> 
    <script>escribe()</script>
  </p>
</div>
</body>
</html>