var xmlhttp;
// =false;

// if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
//  xmlhttp = new XMLHttpRequest();
// }

// --------------
function pasarconsultorio() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
	var codigomedico= document.getElementById('codigodr').value;
//	alert("1 " + codigomedico)
	var nroregistro= document.getElementById('nroregistro').value;
//	alert("2 " + nroregistro)
	var linea='pasarconsultorio.php?codigodr=' + document.getElementById('codigodr').value+'&'+
	'nroregistro=' + document.getElementById('nroregistro').value;
 	// alert(linea);
	xmlhttp.onreadystatechange=cambio_pacienteingreso;
	xmlhttp.open("GET", linea, true);
//----------
//----------
	xmlhttp.send(null)
//	return false;
}


function cambio_pacienteingreso() {
if (xmlhttp.readyState==4) {
	document.getElementById('resultado').innerHTML = "";
//			document.getElementById('cuota').value = xmlhttp.responseText;	// forma original para uno solo
//			document.getElementById('interes_diferido').value = xmlhttp.responseText;

//	alert('1');
//	var xmlDoc=xmlhttp.responseXML.documentElement;
//	alert('2');
//	alert(xmlDoc);
	// var 
	xmlDoc=xmlhttp.responseXML;
//	alert(xmlhttp.responseXML.getElementsByTagName('cuota')[0].childNodes[0].nodeValue);
//	peticion.responseXML.getElementsByTagName("codigo" )[0];
//	document.getElementById("cuota").value= xmlDoc.getElementsByTagName("cuota")[0].childNodes[0].nodeValue;
//	if (xmlDoc.getElementsByTagName("sinproblema")[0].childNodes[0].nodeValue == 1)
//		alert('Tiene Cita Abierta... No puede abrir otra');
/*
	document.getElementById("cedulatitular").value= xmlDoc.getElementsByTagName("cedulatitular")[0].childNodes[0].nodeValue;
	document.getElementById("cedulabeneficiario").value=xmlDoc.getElementsByTagName("cedulabeneficiario")[0].childNodes[0].nodeValue;
	document.getElementById("nombretitular").value=xmlDoc.getElementsByTagName("nombretitular")[0].childNodes[0].nodeValue;
	document.getElementById("nombrebeneficiario").value=xmlDoc.getElementsByTagName("nombrebeneficiario")[0].childNodes[0].nodeValue;
	document.getElementById("parentesco").value=xmlDoc.getElementsByTagName("parentesco")[0].childNodes[0].nodeValue;
	document.getElementById("status").value=xmlDoc.getElementsByTagName("estatus")[0].childNodes[0].nodeValue;
	document.getElementById("existe").value=xmlDoc.getElementsByTagName("existe")[0].childNodes[0].nodeValue;
	if ((document.getElementById("existe").value == 0) && document.getElementById("cedulatitular").value == " ") alert("El numero de cedula que indica NO EXISTE")
		else if ((document.getElementById("status").value != "Activo") && (document.getElementById("status").value != "Jubilado")) alert("El numero de cedula que indica NO es ACTIVO o JUBILADO")
*/
	}	
 else   document.getElementById('resultado').innerHTML = "<img src=\"imagenes/animadas/03.gif\" />";
}

// --------------

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
