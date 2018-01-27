<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="utf-8">

 

    <title>Ejemplo scroll de noticias</title>

 

<style type="text/css">
       .scrollWrapper   {
            width:200px;height:300px;
            overflow:hidden;
            border:2px solid #00f;
            font-family:Arial;font-size:0.8em;
        }

        .scrollTitle {
            background-color:#00f;
            color:#fff;
            padding:5px;
            font-weight:bold;
            text-align:center;
        }
        #scroll    {
            position:relative; 
            width:auto;
            margin:1px;
            z-index: -1;
            padding:5px;
        }
        #scroll .title  {font-weight:bold;margin-top:20px;}
</style>

 

<script type="text/javascript">
        // determina el numero de pixeles que se moveran las noticias para
        // cada iteracion en milisegundos de "speedjump"
        var scrollspeed=1;
        // determina la velocidad en milisgundos
        var speedjump=30;
        // segundos antes de empezar el movimiento
        var startdelay= 1;
        // posicion inicial superior en pixeles para cuando inicia
        var topspace=-10;
        // altura del marco donde se mostraran las noticias
        // Si se modifica la altura del contenedor de las noticas hay que
        // modificar tambien este valor
        var frameheight=270;

        // variable temporal que variara su valor en función de si estan las
        // noticias en movimiento o paradas
        current=scrollspeed;
        /**
         * Inicio del scroll
         * Esta función es llamada en el body de la pagina.
         * Tiene que recibir el id del scroll
         */

        function scrollStart()
        {
            dataobj = document.getElementById("scroll");
            // cogemos la altura maxima de la capa de las noticias
            alturaNoticias = dataobj.offsetHeight;
            // posicionamos la capa del scroll en su posicion inicial
            dataobj.style.top = topspace + 'px';
            setTimeout("scrolling()", (startdelay * 1000));
        }


        /**
         * Funcion que realiza el movimiento
         */

        function scrolling() {
            // Restamos a la propiedad top de la capa el valor en pixeles
            // establecido en la variable "scrollspeed", para hacer el
            // movimiento hacia arriba.
            dataobj.style.top = parseInt(dataobj.style.top) - scrollspeed + 'px';
            // Si la capa ha sobrepasado la altura del area por donde se muestran
            // las noticias ("alturaNoticias")
            if (parseInt(dataobj.style.top) < alturaNoticias * (-1))
            {
                // Posicionamos la capa en la parte inferior del recuadro, para
                // que simule que vienen las noticias de la parte inferior
                dataobj.style.top = frameheight + 'px';
                setTimeout("scrolling()", 0);
            }else{
                setTimeout("scrolling()", speedjump);
            }
        }
</script>

</head>

 

<body onLoad="scrollStart();">

 

<h1>Ejemplo scroll de noticias</H1>

 

<div class="scrollWrapper" onMouseover="scrollspeed=0" onMouseout="scrollspeed=current">

    <div class="scrollTitle">Últimas Noticias</div>

    <div id="scroll" >

 

        <div class="title">Primera Noticia</div>

        <div class="content">Contenido de ejemplo para el scroll de noticias personalizable. En el contenido puedes añadir cualquier codigo HTML, incluidos enlaces <a href="http://www.lawebdelprogramador.com" target="_top">La Web del programador</a>

        </div>

 

        <div class="title">Segunda Noticia</div>

        <div class="content">Contenido de ejemplo para el scroll de noticias personalizable. En el contenido puedes añadir cualquier codigo HTML, incluidos enlaces <a href="http://www.lawebdelprogramador.com" target="_top">La Web del programador</a>

        </div>

 

        <div class="title">Tercera Noticia</div>

        <div class="content">Contenido de ejemplo para el scroll de noticias personalizable. En el contenido puedes añadir cualquier codigo HTML, incluidos enlaces <a href="http://www.lawebdelprogramador.com" target="_top">La Web del programador</a>

        </div>

 

    </div>

</div>

</body>

</html>