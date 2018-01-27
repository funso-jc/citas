var xmlhttp;
// =false;

// if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
//  xmlhttp = new XMLHttpRequest();
// }

function LlamarPacientealConsultorio() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
	var codigomedico= document.getElementById('codigomedico').value;
	var linea='llamarpacienteconsultorio.php?codigodr=' + document.getElementById('codigomedico').value+'&';
// 	alert(linea);
	xmlhttp.onreadystatechange=cambio_paciente;
	xmlhttp.open("GET", linea, true);
//----------
/*
//setup the headers
    try {
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlhttp.setRequestHeader("Accept", "text/xml, application/xml, text/plain");
    } catch ( ex ) {
        window.alert('error' + ex.toString());
    }

*/
//----------
	xmlhttp.send(null)
//	return false;
}


function cambio_paciente() {
/*
var html = '<img src="imagenes/animadas/checklist_sm_wht.gif" />';
var tmpContainer = document.createElement('div');
tmpContainer.innerHTML = html;
*/
 // document.getElementById('resultado').innerHTML = "Cargando imágenes...";
 a = b = 1;
// while (a == b)
 {
if (xmlhttp.readyState==4) {
	document.getElementById('imagenbusqueda').innerHTML = "";
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
	document.getElementById("cedulatitular").value= xmlDoc.getElementsByTagName("cedulatitular")[0].childNodes[0].nodeValue;
	document.getElementById("cedulabeneficiario").value=xmlDoc.getElementsByTagName("cedulabeneficiario")[0].childNodes[0].nodeValue;
	document.getElementById("nombretitular").value=xmlDoc.getElementsByTagName("nombretitular")[0].childNodes[0].nodeValue;
	document.getElementById("nombrebeneficiario").value=xmlDoc.getElementsByTagName("nombrebeneficiario")[0].childNodes[0].nodeValue;
	document.getElementById("motivo").value=xmlDoc.getElementsByTagName("motivo")[0].childNodes[0].nodeValue;
	document.getElementById("observaciones").value=xmlDoc.getElementsByTagName("observacion")[0].childNodes[0].nodeValue;
	document.getElementById("numeroregistro").value=xmlDoc.getElementsByTagName("numeroregistro")[0].childNodes[0].nodeValue;
	
	// document.getElementById("existe").value=xmlDoc.getElementsByTagName("existe")[0].childNodes[0].nodeValue;
	b = xmlDoc.getElementsByTagName("existe")[0].childNodes[0].nodeValue;
	if (b > 1)
	{
		document.getElementById("diagnostico").disabled=false;
		document.getElementById("recipe").disabled=false;
		document.getElementById("signos").disabled=false;
		document.getElementById("alergias").disabled=false;
		document.getElementById("referencia").disabled=false;
		document.getElementById("constancia").disabled=false;
		document.getElementById("reposo").disabled=false;
		document.getElementById("informe").disabled=false;
		document.getElementById("orden").disabled=false;
		document.getElementById("historia").disabled=false;
		document.getElementById("ingresar").disabled=false;	
	}
//	alert ("b = "+b)
	
/*
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
*/
	}
else   document.getElementById('imagenbusqueda').innerHTML = "<img src=\"imagenes/animadas/cargando.gif\" />";
 }

}

/*
// --------------
function IngresarPaciente() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
	var cedulatitular= document.getElementById('cedulatitular').value;
	var cedulabeneficiario= document.getElementById('cedulabeneficiario').value;
	var motivo= document.getElementById('motivo').value;
	var observacion= document.getElementById('observaciones').value;
	var = document.getElementById('cedula').value;
var linea='ingresarpaciente.php?cedulatitular=' + document.getElementById('cedulatitular').value+'&'+
	'cedulabeneficiario=' + document.getElementById('cedulabeneficiario').value+'&'+
	'motivo=' + document.getElementById('motivo').value+'&'+
	'observacion=' + document.getElementById('observacion').value;
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
	document.getElementById("cedulatitular").value= xmlDoc.getElementsByTagName("cedulatitular")[0].childNodes[0].nodeValue;
	document.getElementById("cedulabeneficiario").value=xmlDoc.getElementsByTagName("cedulabeneficiario")[0].childNodes[0].nodeValue;
	document.getElementById("nombretitular").value=xmlDoc.getElementsByTagName("nombretitular")[0].childNodes[0].nodeValue;
	document.getElementById("nombrebeneficiario").value=xmlDoc.getElementsByTagName("nombrebeneficiario")[0].childNodes[0].nodeValue;
	document.getElementById("parentesco").value=xmlDoc.getElementsByTagName("parentesco")[0].childNodes[0].nodeValue;
	document.getElementById("status").value=xmlDoc.getElementsByTagName("estatus")[0].childNodes[0].nodeValue;
	document.getElementById("existe").value=xmlDoc.getElementsByTagName("existe")[0].childNodes[0].nodeValue;
	if ((document.getElementById("existe").value == 0) && document.getElementById("cedulatitular").value == " ") alert("El numero de cedula que indica NO EXISTE")
		else if ((document.getElementById("status").value != "Activo") && (document.getElementById("status").value != "Jubilado")) alert("El numero de cedula que indica NO es ACTIVO o JUBILADO")

	}
}
*/

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
