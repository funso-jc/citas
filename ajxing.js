var xmlhttp;


function activar() {
/*
xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
*/
	voluntario=parseFloat(document.getElementById("cancelarahvol1").value);
	var selIndex = document.getElementById('registros').value;
	var totalregistros=0;
    //alert('total registros'+selIndex);
	for(j=0; j < selIndex; j++){
			var valor = eval("document.getElementById('cancelar"+(j+1)+"').checked");
			if (valor == true) {
				document.getElementById("cancelart"+(j+1)).value= document.getElementById("cancelarho"+(j+1)).value;
				document.getElementById("cancelart"+(j+1)).disabled=false;
			}
			else {
				document.getElementById("cancelart"+(j+1)).disabled=true;
				document.getElementById("cancelart"+(j+1)).value= 0;
			}
        }
	var neto=0;
	for (var i=1;i<=selIndex;i++) 
		neto+=parseFloat(document.getElementById("cancelart"+i).value);
 	document.getElementById("totalprestamos").value=parseFloat(neto)+voluntario;
//	document.getElementById("montoprestamo").value=parseFloat(document.getElementById("totalprestamos").value)+parseFloat(document.getElementById("montoahorros").value);
//alert(document.getElementById("totalprestamos").value);
//alert(document.getElementById("montoahorros").value);
	neto=parseFloat(document.getElementById("totalprestamos").value)+parseFloat(document.getElementById("montoahorros").value);
 	document.getElementById("montoprestamo").value=parseFloat(neto);
 	neto2=parseFloat(document.getElementById("montoprestamo").value);
	if (neto2<1)
		document.getElementById("continuar").disabled=true;
 	else document.getElementById("continuar").disabled=false;


/*
xmlhttp.send(null)
*/
}


function revisarmonto(registro) {
//alert(registro);
	voluntario=parseFloat(document.getElementById("cancelarahvol1").value);
	var selIndex = document.getElementById('registros').value;
	var visible=document.getElementById("cancelart"+registro).value;
	var oculto=document.getElementById("cancelarh"+(registro)).value;
    //alert('valor visible '+visible + ' vlor oculto '+oculto);

var neto=0;
	for (var i=1;i<=selIndex;i++) 
	{
		neto+=parseFloat(document.getElementById("cancelart"+i).value);
		if (parseFloat(document.getElementById("cancelart"+i).value) > parseFloat(document.getElementById("cancelarho"+i).value))
		{
			document.getElementById("continuar").disabled=true;
			alert("El monto a pagar por cuota no debe exceder la misma"); return false; 
		}
			
	}
	document.getElementById("totalprestamos").value=parseFloat(neto)+voluntario;
//	document.getElementById("montoprestamo").value=parseFloat(neto);

	neto=parseFloat(document.getElementById("totalprestamos").value)+parseFloat(document.getElementById("montoahorros").value);
 	document.getElementById("montoprestamo").value=parseFloat(neto);
	if (parseFloat(visible) <= 0) {
		document.getElementById("continuar").disabled=true;
		alert("El monto a cancelar no puede ser menor o igual a 0"); return false; }
	else 
/*
		if (parseFloat(visible) > parseFloat(oculto)){
			document.getElementById("continuar").disabled=true;
			alert("El monto a cancelar no puede ser mayor al saldo actual"); return false; }
		else {
*/
		{
			document.getElementById("continuar").disabled=false;
			return true;
		}
}

function calcular(){
	var neto=0;
	voluntario=parseFloat(document.getElementById("cancelarahvol1").value);
	for (var i=1;i<=selIndex;i++) 
		neto+=parseFloat(document.getElementById("cancelart"+i).value);
	document.getElementById("montoprestamo").value=parseFloat(neto)+voluntario;
}

function activarah() {
/*
xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }
*/
	voluntario=parseFloat(document.getElementById("cancelarahvol1").value);
	var selIndex = document.getElementById('registroah').value;
	var totalregistros=0;
//   alert('total registros'+selIndex);
	var neto=0;
	for(j=0; j < selIndex; j++){
			var valor = eval("document.getElementById('cancelarah"+(j+1)+"').checked");
			if (valor == true) {
				document.getElementById("cancelaraht"+(j+1)).value= document.getElementById("cancelaraho"+(j+1)).value;
				document.getElementById("cancelaraht"+(j+1)).disabled=false;
			}
			else {
				document.getElementById("cancelaraht"+(j+1)).disabled=true;
				document.getElementById("cancelaraht"+(j+1)).value= 0;
			}
        }
	var neto=0;
	for (var i=1;i<=selIndex;i++) 
	{
		neto+=parseFloat(document.getElementById("cancelaraht"+i).value);
//		alert('total registros'+neto);
	}
	document.getElementById("montoahorros").value=parseFloat(neto);
	document.getElementById("montoprestamo").value=parseFloat(document.getElementById("totalprestamos").value)+parseFloat(document.getElementById("montoahorros").value)+voluntario;
	neto=parseFloat(document.getElementById("totalprestamos").value)+parseFloat(document.getElementById("montoahorros").value);
 	neto2=parseFloat(document.getElementById("montoprestamo").value)+voluntario;
	if (neto2<1)
		document.getElementById("continuar").disabled=true;
	else document.getElementById("continuar").disabled=false;


/*
xmlhttp.send(null)
*/
}

function revisarmontoahorro(registro) {
// alert(registro);
var neto=0;
	voluntario=parseFloat(document.getElementById("cancelarahvol1").value);
	document.getElementById("montoahorros").value=parseFloat(neto);
	document.getElementById("montoprestamo").value=parseFloat(document.getElementById("totalprestamos").value)+parseFloat(document.getElementById("montoahorros").value)+voluntario;
	
	if (parseFloat(document.getElementById("registroah").value) > 1)
		var selIndex = document.getElementById('registroah').value;
	else 
		var selIndex = 0;
	if (selIndex > 0)
	{
		var visible=document.getElementById("cancelaraht"+registro).value;
		var oculto=document.getElementById("cancelarah"+(registro)).value;
	    //alert('valor visible '+visible + ' vlor oculto '+oculto);

		for (var i=1;i<=selIndex;i++) 
		{
			neto+=parseFloat(document.getElementById("cancelaraht"+i).value);
//		alert(document.getElementById("cancelaraht"+i).value);
		}
//	alert('salgo');
//	document.getElementById("totalprestamos").value=parseFloat(neto);
		document.getElementById("montoahorros").value=parseFloat(neto);
		document.getElementById("montoprestamo").value=parseFloat(document.getElementById("totalprestamos").value)+parseFloat(document.getElementById("montoahorros").value)+voluntario;
// 		alert(voluntario);

		if (parseFloat(visible) <= 0) {
			document.getElementById("continuar").disabled=true;
			alert("El monto a cancelar no puede ser menor o igual a 0"); return false; }
		else 
/*
		if (parseFloat(visible) > parseFloat(oculto)){
			document.getElementById("continuar").disabled=true;
			alert("El monto a cancelar no puede ser mayor al saldo actual"); return false; }
		else {
*/
		{
			document.getElementById("continuar").disabled=false;
			return true;
		}
	}
		document.getElementById("montoahorros").value=parseFloat(neto);
		document.getElementById("montoprestamo").value=parseFloat(document.getElementById("totalprestamos").value)+parseFloat(document.getElementById("montoahorros").value);
}

function activar_pago_pr() {
	voluntario=parseFloat(document.getElementById("cancelarahvol1").value);
	var selIndex = document.getElementById('registrosp').value;
	var totalregistros=0;
    //alert('total registros'+selIndex);
	for(j=0; j < selIndex; j++){
			var valor = eval("document.getElementById('pago_pr"+(j+1)+"').checked");
			if (valor == true) {
//				document.getElementById("pago_prt"+(j+1)).value= document.getElementById("cancelarho"+(j+1)).value;
				document.getElementById("pago_prt"+(j+1)).disabled=false;
			}
			else {
				document.getElementById("pago_pr"+(j+1)).disabled=true;
				document.getElementById("pago_prt"+(j+1)).value= 0;
			}
        }
	var neto=0;
	for (var i=1;i<=selIndex;i++) 
		neto+=parseFloat(document.getElementById("cancelart"+i).value);
 	document.getElementById("totalpago").value=parseFloat(neto);
 	document.getElementById("totalprestamos").value=document.getElementById("totalprestamos").value+document.getElementById("totalpago").valuevoluntario;
	neto=parseFloat(document.getElementById("totalprestamos").value)+parseFloat(document.getElementById("montoahorros").value);
 	document.getElementById("montoprestamo").value=parseFloat(neto);
 	neto2=parseFloat(document.getElementById("montoprestamo").value);
	if (neto2<1)
		document.getElementById("continuar").disabled=true;
 	else document.getElementById("continuar").disabled=false;

}

function activar_inscripcion() {
	var selIndex = document.getElementById('registros').value;
	var totalregistros=0;
/*
	for(j=0; j < selIndex; j++){
			var valor = eval("document.getElementById('inscribir"+(j+1)+"').checked");
			if (valor == true) {
				document.getElementById("inscribiri"+(j+1)).value= document.getElementById("inscribiri"+(j+1)).value;
				document.getElementById("inscribiri"+(j+1)).disabled=false;
			}
			else {
				document.getElementById("cancelart"+(j+1)).disabled=true;
				document.getElementById("cancelart"+(j+1)).value= 0;
			}
        }
*/
//	alert(linea);


	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }

	var neto=0;
 	document.getElementById("totalprestamos").value=parseFloat(neto);
 	document.getElementById("montoprestamo").value=parseFloat(neto);
	document.getElementById("concepto").value="";
	document.getElementById("cod_prog").value="";
	for (var i=1;i<=selIndex;i++) 
	{
		var valor = eval("document.getElementById('inscribir"+(i)+"').checked");
		if (valor == true) {
			var linea='newcod_ahorropro.php?serie='+document.getElementById("inscribir"+(i)).value+"";
//			alert(linea);
			xmlhttp.onreadystatechange=cambio_ahorropro;
			xmlhttp.open("GET", linea, true);
			xmlhttp.send(null)

			neto+=parseFloat(document.getElementById("inscribiri"+i).value);
		 	document.getElementById("montoprestamo").value=parseFloat(neto);
		 	document.getElementById("totalprestamos").value=parseFloat(neto);
//			alert('codigo '+document.getElementById("cod_prog").value)
		 	document.getElementById("concepto").value="Inscripcion Ahorro Programado PLAN ("+document.getElementById("inscribir"+i).value+") del "+document.getElementById("inscribirf"+i).value;
			//+ ' -->Codigo'+document.getElementById("cod_prog").value+'<--';
		}
	}
	if (neto<1)
		document.getElementById("continuar").disabled=true;
 	else document.getElementById("continuar").disabled=false;
/*
//	document.getElementById("montoprestamo").value=parseFloat(document.getElementById("totalprestamos").value)+parseFloat(document.getElementById("montoahorros").value);
//alert(document.getElementById("totalprestamos").value);
//alert(document.getElementById("montoahorros").value);
	neto=parseFloat(document.getElementById("totalprestamos").value)+parseFloat(document.getElementById("montoahorros").value);
 	document.getElementById("montoprestamo").value=parseFloat(neto);
 	neto2=parseFloat(document.getElementById("montoprestamo").value);
	if (neto2<1)
		document.getElementById("continuar").disabled=true;
 	else document.getElementById("continuar").disabled=false;
*/

/*
xmlhttp.send(null)
*/
}

function cambio_ahorropro() {
if (xmlhttp.readyState==4) {
	xmlDoc=xmlhttp.responseXML;
	document.getElementById("cod_prog").value= xmlDoc.getElementsByTagName("cod_prog")[0].childNodes[0].nodeValue;
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

function ah_activar() {
	xmlhttp=GetXmlHttpObject();
	if (xmlhttp==null)
	  {
		  alert ("Browser does not support HTTP Request");
	  return;
	  }

	var selIndex = document.getElementById('registros').value;
	var totalregistros=0;
	for(j=0; j < selIndex; j++)
	{
		var valor = eval("document.getElementById('cancelar_ah"+(j+1)+"').checked");
		if (valor == true) 
		{
			document.getElementById("cancelart_ah"+(j+1)).value= document.getElementById("cancelarho_ah"+(j+1)).value;
			document.getElementById("cancelart_ah"+(j+1)).disabled=false;
		}
		else 
		{
				document.getElementById("cancelart_ah"+(j+1)).disabled=true;
				document.getElementById("cancelart_ah"+(j+1)).value= 0;
		}
	}
	var neto=0;
	for (var i=1;i<=selIndex;i++) 
		neto+=parseFloat(document.getElementById("cancelart_ah"+i).value);
 	document.getElementById("montoprestamo").value=document.getElementById("totalprestamos").value=parseFloat(neto);

	neto=parseFloat(document.getElementById("totalprestamos").value);
	if (neto<1)
		document.getElementById("continuar").disabled=true;
 	else document.getElementById("continuar").disabled=false;
//    alert('total registros'+neto);

/*
xmlhttp.send(null)
*/
}

function revisarmonto_ah(registro) {
// alert(registro);
	var selIndex = document.getElementById('registros').value;
	var visible=document.getElementById("cancelart_ah"+registro).value;
	var oculto=document.getElementById("cancelarh_ah"+(registro)).value;

	var neto=0;
	for (var i=1;i<=selIndex;i++) 
	{
		neto+=parseFloat(document.getElementById("cancelart_ah"+i).value);
		if (parseFloat(document.getElementById("cancelart_ah"+i).value) > parseFloat(document.getElementById("cancelarho_ah"+i).value))
		{
			document.getElementById("continuar").disabled=true;
			alert("El monto a pagar por cuota no debe exceder la misma"); return false; 
		}
	}
	document.getElementById("totalprestamos").value=parseFloat(neto);
//	document.getElementById("montoprestamo").value=parseFloat(neto);

	neto=parseFloat(document.getElementById("totalprestamos").value);
 	document.getElementById("montoprestamo").value=parseFloat(neto);
	if (parseFloat(visible) <= 0) {
		document.getElementById("continuar").disabled=true;
		alert("El monto a cancelar no puede ser menor o igual a 0"); return false; }
	else 
		{
			document.getElementById("continuar").disabled=false;
			return true;
		}
}
