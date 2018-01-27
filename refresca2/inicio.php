<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>titulo</title>
<script type="text/javascript">
function caca()
{
var algo;
if (window.XMLHttpRequest)
  {
  algo=new XMLHttpRequest();
  }
else
  {
  algo=new ActiveXObject("Microsoft.XMLHTTP");
  }
algo.onreadystatechange=function()
  {
  if (algo.readyState==4 && algo.status==200)
    {
    document.getElementById("caca").innerHTML=algo.responseText;
    }else{
//porsi quieres poner un mensaje de cargando
//document.getElementById("caca").innerHTML="cargando...";
}
  }
algo.open("GET","pagina.php",true);
algo.send();
}




</script>
</head>
<style type="text/css">
#caca{background-color:#CCC;
margin:0px auto;
width:500px;
padding:10px 10px 10px 10px;
cursor:pointer;
}
</style>
<body>
<script type="text/javascript">
setInterval("caca()",2000);
</script>
<div id='caca'></div>

</body>
</html>