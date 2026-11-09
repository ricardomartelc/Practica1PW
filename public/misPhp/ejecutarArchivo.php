<?php

$orden = $_POST['orden'] ?? null; // Obtenemos el valor de 'orden' del formulario enviado por POST, si no existe, se asigna null

if ($orden !== null && $orden !== '') { // Verificamos que la variable $orden no sea nula ni esté vacía
    // Eliminamos comillas simples de los extremos si las hubiera
    $ordenLimpia = trim($orden, "'"); // Eliminamos comillas simples de los extremos de la cadena
    $urlSegura = htmlspecialchars($ordenLimpia, ENT_QUOTES, 'UTF-8'); // Escapamos caracteres especiales para prevenir inyecciones de código y XSS
    
    // Generamos la etiqueta HTML de hipervínculo <a>
    echo "La orden a ejecutar es <a href='" . $urlSegura . "'>" . $urlSegura . "</a>"; // Mostramos la orden como un enlace seguro en la página web
} else {
    echo "No existe ninguna orden a ejecutar";
}
?>