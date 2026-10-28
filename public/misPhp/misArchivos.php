<?php
function listarContenido(string $directorio): void {
    echo "<h3>Contenido de la carpeta <i>" . basename($directorio) . "</i>:</h3><ul>";
    foreach (scandir($directorio) as $item) {
        if ($item === '.' || $item === '..') continue;
        $ruta = $directorio . DIRECTORY_SEPARATOR . $item;
        echo "<li>" . $item . (is_dir($ruta) ? " (dir)" : "") . "</li>";
    }
    echo "</ul>";
}

$raiz = dirname(__DIR__, 2); // raíz del proyecto
listarContenido(dirname(__DIR__));          // public
listarContenido($raiz . '/practica');       // practica
listarContenido(__DIR__);                   // misPhp