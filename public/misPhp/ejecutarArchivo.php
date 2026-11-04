<?php

$orden = $_POST['orden'] ?? null;

if ($orden !== null && $orden !== '') {
    // Eliminamos comillas simples de los extremos si las hubiera
    $ordenLimpia = trim($orden, "'");
    $urlSegura = htmlspecialchars($ordenLimpia, ENT_QUOTES, 'UTF-8');
    
    // Generamos la etiqueta HTML de hipervínculo <a>
    echo "La orden a ejecutar es <a href='" . $urlSegura . "'>" . $urlSegura . "</a>";
} else {
    echo "No existe ninguna orden a ejecutar";
}
?>