<?php
// Muestra el contenido de una carpeta en una lista
function mostrarCarpeta($titulo, $ruta) {
    echo "<h2>Contenido de la carpeta <i>$titulo</i>:</h2>";
    echo "<ul>";
    $archivos = scandir($ruta);
    foreach ($archivos as $nombre) {
        // saltar . y ..
        if ($nombre == "." || $nombre == "..") {
            continue;
        }
        // si es directorio, añadir (dir)
        if (is_dir("$ruta/$nombre")) {
            $nombre = "$nombre (dir)";
        }
        echo "<li>$nombre</li>";
    }
    echo "</ul>";
}

mostrarCarpeta("public", "..");
mostrarCarpeta("practica", "../../practica");
mostrarCarpeta("misPhp", ".");
