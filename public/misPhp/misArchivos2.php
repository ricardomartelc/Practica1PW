<?php
// Muestra el contenido de una carpeta en una lista
function mostrarCarpeta($titulo, $ruta) {
    echo "<h2>Contenido de la carpeta <i>$titulo</i>:</h2>";
    echo "<ul>";
    foreach (scandir($ruta) as $nombre) {
        if ($nombre == "." || $nombre == "..") continue; // saltar . y ..
        if (is_dir("$ruta/$nombre")) $nombre .= " (dir)"; // marcar directorios
        echo "<li>$nombre</li>";
    }
    echo "</ul>";
}

mostrarCarpeta("public", "..");
mostrarCarpeta("practica", "../../practica");
mostrarCarpeta("misPhp", ".");
