var xmlhttp;
// =false;

// if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
//  xmlhttp = new XMLHttpRequest();
// }

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


function calccanc() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
	var selIndex = document.getElementById('registros').value;
	var linea='ajx_examenes.php?registros=' + selIndex+'&tipo='+document.getElementById('tipo').value+
	'&cupo='+document.getElementById('cupo').value+'&consumido='+document.getElementById('consumido').value+
	'&';
	var otralinea='';
//	alert(linea)
	
/*
var indice = 1;
eval("var variable" + indice + " = 'valor'");
alert('la variable 1='+variable1);
*/
	var totalregistros=0;
	for(j=0; j < selIndex; j++){
//			var valor='cancelar'+(j+1);
//			alert('valor ='+valor);
			
//			var indice = 1;
//			eval("var variable" + indice + " = 'valor'");
			
			var valor = eval("document.getElementById('cancelar"+(j+1)+"').checked");
//			alert(valor2);
			if (valor == true) {
				
				totalregistros++;
				var valor2 = eval("document.getElementById('cancelar"+(j+1)+"').value");
//				otralinea=otralinea+'cancelar'+(j+1)+'='+valor2+'&'
				otralinea=otralinea+'cancelar'+(totalregistros)+'='+valor2+'&'
			}
        }
//			alert(otralinea);

	/*
					(document.getElementById('monpre_sdp').value-document.getElementById('inicial').value) + 
						'&num_cuotas=' + selIndex + 
						'&p_interes=' + document.getElementById('interes_sd').value + 
						'&divisible=' + document.getElementById('factor_division').value +
						'&tipo_interes=' + document.getElementById('tipo_interes').value+
						'&f_ajax=' + document.getElementById('calculo').value + 
						'&cual=1';
						*/
	linea=linea+otralinea+'totalregistros='+totalregistros; //+'&micedula='+document.getElementById('micedula').value+'&montoprestamo=';
//	linea+=document.getElementById('montoprestamo').value;
// 	alert(linea);
	xmlhttp.onreadystatechange=cambio2;
	xmlhttp.open("GET", linea, true);
	xmlhttp.send(null)
}

function cambio2() {
if (xmlhttp.readyState==4) {
	xmlDoc=xmlhttp.responseXML;
	document.getElementById("aqui").value= xmlDoc.getElementsByTagName("aqui")[0].childNodes[0].nodeValue;
	document.getElementById("xconsumir").value= xmlDoc.getElementsByTagName("xconsumir")[0].childNodes[0].nodeValue;
/*
	document.getElementById("montoprestamo").value= xmlDoc.getElementsByTagName("montoprestamo")[0].childNodes[0].nodeValue-xmlDoc.getElementsByTagName("interes_diferido")[0].childNodes[0].nodeValue;
	document.getElementById("descuentosadm").value= xmlDoc.getElementsByTagName("descuentosadm")[0].childNodes[0].nodeValue;
	document.getElementById("marcados").value= xmlDoc.getElementsByTagName("marcados")[0].childNodes[0].nodeValue;
	document.getElementById("reintegros").value= xmlDoc.getElementsByTagName("reintegros")[0].childNodes[0].nodeValue;
*/
	if (document.getElementById("xconsumir").value <= 0)
		alert('Verifique los datos. El neto a recibir no puede ser menor o igual a cero');
//	document.getElementById("interes_diferido").value=xmlDoc.getElementsByTagName("interes_diferido")[0].childNodes[0].nodeValue;
//	document.getElementById("montoneto").value=xmlDoc.getElementsByTagName("montoneto")[0].childNodes[0].nodeValue;
//	document.getElementById("gastosadministrativos").value=xmlDoc.getElementsByTagName("gastosadministrativos")[0].childNodes[0].nodeValue;
	}
}

function marcar() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
	  alert("opcion en desarrollo")
	var selIndex = document.getElementById('registros').value;
	var totalregistros=0;
	for(j=0; j < selIndex; j++){
		document.getElementById(document.getElementById('cancelar"+(j+1)+"').checked) = true;
	}
	xmlhttp.send(null)
}

function desmarcar() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
	  alert("opcion en desarrollo")
	var selIndex = document.getElementById('registros').value;
	var totalregistros=0;
	for(j=0; j < selIndex; j++){
		document.getElementById(document.getElementById('cancelar"+(j+1)+"').checked) = true;
	}
	xmlhttp.send(null)
}

