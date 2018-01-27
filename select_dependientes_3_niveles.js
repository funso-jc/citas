function N3Ajax()
{ 
	/* Crea el objeto AJAX. Esta funcion es generica para cualquier utilidad de este tipo, por
	lo que se puede copiar tal como esta aqui */
	var xmlhttp=false;
//	alert('lll');
	try
	{
		// Creacion del objeto AJAX para navegadores no IE
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e)
	{
		try
		{
			// Creacion del objet AJAX para IE
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(E)
		{
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') xmlhttp=new XMLHttpRequest();
		}
	}
	return xmlhttp; 
}

// Declaro los selects que componen el documento HTML. Su atributo ID debe figurar aqui.
var listadoSelects=new Array();
// listadoSelects[0]="ciudad";
listadoSelects[0]="especialidad";
listadoSelects[1]="especialistas";
// listadoSelects[3]="tarifa";

function buscarEnArray(array, dato)
{
	// Retorna el indice de la posicion donde se encuentra el elemento en el array o null si no se encuentra
	var x=0;
	while(array[x])
	{
		if(array[x]==dato) return x;
		x++;
	}
	return null;
}

function cargaContenido(idSelectOrigen)
{
//	alert('aaa');
	// Obtengo la posicion que ocupa el select que debe ser cargado en el array declarado mas arriba
	var posicionSelectDestino=buscarEnArray(listadoSelects, idSelectOrigen)+1;
	// Obtengo el select que el usuario modifico
	var selectOrigen=document.getElementById(idSelectOrigen);
	// Obtengo la opcion que el usuario selecciono
	var opcionSeleccionada=selectOrigen.options[selectOrigen.selectedIndex].value;
	// Si el usuario eligio la opcion "Elige", no voy al servidor y pongo los selects siguientes en estado "Selecciona opcion..."
	if(opcionSeleccionada==0)
	{
		var x=posicionSelectDestino, selectActual=null;
		// Busco todos los selects siguientes al que inicio el evento onChange y les cambio el estado y deshabilito
		while(listadoSelects[x])
		{
			selectActual=document.getElementById(listadoSelects[x]);
			selectActual.length=0;
			
			var nuevaOpcion=document.createElement("option"); nuevaOpcion.value=0; nuevaOpcion.innerHTML="Selecciona Opci&oacute;n...";
			selectActual.appendChild(nuevaOpcion);	selectActual.disabled=true;
			x++;
		}
	}
	// Compruebo que el select modificado no sea el ultimo de la cadena
	else if(idSelectOrigen!=listadoSelects[listadoSelects.length-1])
	{
		// Obtengo el elemento del select que debo cargar
		var idSelectDestino=listadoSelects[posicionSelectDestino];
		var selectDestino=document.getElementById(idSelectDestino);
		// Creo el nuevo objeto AJAX y envio al servidor el ID del select a cargar y la opcion seleccionada del select origen
		var ajax=N3Ajax();
//		alert("select_dependientes_3_niveles_proceso.php?select="+idSelectDestino+"&opcion="+opcionSeleccionada);
		ajax.open("GET", "select_dependientes_3_niveles_proceso.php?select="+idSelectDestino+"&opcion="+opcionSeleccionada, true);
		ajax.onreadystatechange=function() 
		{ 
			if (ajax.readyState==1)
			{
				// Mientras carga elimino la opcion "Selecciona Opcion..." y pongo una que dice "Cargando..."
				selectDestino.length=0;
				var nuevaOpcion=document.createElement("option"); nuevaOpcion.value=0; nuevaOpcion.innerHTML="Cargando...";
				selectDestino.appendChild(nuevaOpcion); selectDestino.disabled=true;	
			}
			if (ajax.readyState==4)
			{
				selectDestino.parentNode.innerHTML=ajax.responseText;
			} 
		}
		ajax.send(null);
	}
}

/*
function mostrar_datos() {
	return confirm("¿Está seguro de que quiere borrar esta Cuenta?")
	var xmlhttp;
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
	var cedula= document.getElementById('cedula').value;
	var linea='mostrarpaciente.php';
 	// alert(linea);
	xmlhttp.onreadystatechange=cambia2;
	xmlhttp.open("GET", linea, true);
	xmlhttp.send(null)
}

function cambia2() {
 // document.getElementById('resultado').innerHTML = "Cargando imágenes...";
if (xmlhttp.readyState==4) {
	document.getElementById('resultado').innerHTML = "";
//			document.getElementById('cuota').value = xmlhttp.responseText;	// forma original para uno solo
//			document.getElementById('interes_diferido').value = xmlhttp.responseText;

	alert('1');
//	var xmlDoc=xmlhttp.responseXML.documentElement;
	alert('2');
//	alert(xmlDoc);
	// var 
	xmlDoc=xmlhttp.responseXML;
//	alert(xmlhttp.responseXML.getElementsByTagName('cuota')[0].childNodes[0].nodeValue);
//	peticion.responseXML.getElementsByTagName("codigo" )[0];
//	document.getElementById("cuota").value= xmlDoc.getElementsByTagName("cuota")[0].childNodes[0].nodeValue;
	document.getElementById("cedulatitular").value= xmlDoc.getElementsByTagName("cedulatitular")[0].childNodes[0].nodeValue;
	document.getElementById("cedulabeneficiario").value=xmlDoc.getElementsByTagName("cedulabeneficiario")[0].childNodes[0].nodeValue;
	document.getElementById("nombretitular").value=xmlDoc.getElementsByTagName("nombretitular")[0].childNodes[0].nodeValue;
	document.getElementById("nombrebeneficiario").value=xmlDoc.getElementsByTagName("nombrebeneficiario")[0].childNodes[0].nodeValue;
	document.getElementById("parentesco").value=xmlDoc.getElementsByTagName("parentesco")[0].childNodes[0].nodeValue;
	document.getElementById("status").value=xmlDoc.getElementsByTagName("estatus")[0].childNodes[0].nodeValue;
	document.getElementById("existe").value=xmlDoc.getElementsByTagName("existe")[0].childNodes[0].nodeValue;
	activar=1;
	if ((document.getElementById("existe").value == 0) && document.getElementById("cedulatitular").value == " ") 
	{
		alert("El numero de cedula que indica NO EXISTE");
		activar=0;
	}
		else if ((document.getElementById("status").value != "Activo") && (document.getElementById("status").value != "Jubilado")) 
		{
			alert("El numero de cedula que indica NO es ACTIVO o JUBILADO");
			activar=0;
		}
	if (activar == 1)
	{
//		alert(document.getElementById("ingresar")); 
		document.getElementById("ingresar").disabled=false;
//		alert("dd")
	}
	else 
	{
		document.getElementById("ingresar").disabled=true;
//		alert("ee")
	}
	}
else   document.getElementById('resultado').innerHTML = "<img src=\"imagenes/animadas/cargando.gif\" />";

}

function GetXmlHttpObject()
{
if (window.XMLHttpRequest)
  {
//	  alert('a');
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
//	  alert('b');
  // code for IE6, IE5
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
}
*/

