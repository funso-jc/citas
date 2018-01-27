var xmlhttp;
// =false;

// if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
//  xmlhttp = new XMLHttpRequest();
// }

function examenes(parametro) {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
	var numeroregistro= document.getElementById('numeroregistro').value;
//	  alert("registro" + numeroregistro)
	var linea=""+parametro+"?numeroregistro=" + document.getElementById('numeroregistro').value+'&';
	// var linea='llamarpacienteconsultorio.php?codigodr=' + document.getElementById('codigomedico').value+'&';
 	alert(linea);
// pedirdiagnostico();

	xmlhttp.onreadystatechange=cambio_examenes;
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


function pedirdiagnostico()
{
//	Diagnostico <input type="diagnostico" id="diagnostico" /> var valor = document.getElementById("texto").value;
// <input type="radio" value="si" name="pregunta" id="pregunta_si"/> SI
}



function cambio_examenes() {
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
	document.getElementById('divdatos').innerHTML = "";
	xmlDoc=xmlhttp.responseXML;
/*
	document.getElementById("cedulatitular").value= xmlDoc.getElementsByTagName("cedulatitular")[0].childNodes[0].nodeValue;
	document.getElementById("cedulabeneficiario").value=xmlDoc.getElementsByTagName("cedulabeneficiario")[0].childNodes[0].nodeValue;
	document.getElementById("nombretitular").value=xmlDoc.getElementsByTagName("nombretitular")[0].childNodes[0].nodeValue;
	document.getElementById("nombrebeneficiario").value=xmlDoc.getElementsByTagName("nombrebeneficiario")[0].childNodes[0].nodeValue;
	document.getElementById("motivo").value=xmlDoc.getElementsByTagName("motivo")[0].childNodes[0].nodeValue;
	document.getElementById("observaciones").value=xmlDoc.getElementsByTagName("observacion")[0].childNodes[0].nodeValue;
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
	
*/
	}
else   document.getElementById('divdatos').innerHTML = "<img src=\"imagenes/animadas/cargando.gif\" />";
 }

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
