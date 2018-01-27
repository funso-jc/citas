<?php

// Formateamos la salida de la variable.

$str = "It is %a on %b %d, %Y, %X - Time zone: %Z";

// Printeamos el resultado

echo (gmstrftime($str,time()));

?>