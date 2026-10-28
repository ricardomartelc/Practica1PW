<?php
function listarContenidoConEnlaces(string $directorio, string $tipo): void {
    echo "<h3>Contenido de la carpeta <i>" . basename($directorio) . "</i>:</h3><ul>";
    if (is_dir($directorio)) {
        foreach (scandir($directorio) as $item) {
            if ($item === '.' || $item === '..') continue;
            $ruta = $directorio . DIRECTORY_SEPARATOR . $item;
            $esDir = is_dir($ruta);
            
            if ($tipo === 'public') {
                if ($esDir) {
                    echo "<li>" . $item . " (dir)</li>";
                } else {
                    echo "<li><a href='http://pweb26.test/$item'>$item</a></li>";
                }
            } elseif ($tipo === 'practica') {
                $nombreSinExt = pathinfo($item, PATHINFO_FILENAME);
                echo "<li><a href='http://pweb26.test/paginaPersonal/$nombreSinExt'>$item</a></li>";
            } elseif ($tipo === 'misPhp') {
                if (pathinfo($item, PATHINFO_EXTENSION) === 'php') {
                    echo "<li><a href='/misPhp/$item'>$item</a></li>";
                } else {
                    echo "<li>$item</li>";
                }
            }
        }
    } else {
        echo "<li>El directorio no existe.</li>";
    }
    echo "</ul>";
}

$raiz = dirname(__DIR__, 2); // Raíz del proyecto Laravel
listarContenidoConEnlaces(dirname(__DIR__), 'public');          // public
listarContenidoConEnlaces($raiz . '/practica', 'practica');       // practica (privada)
listarContenidoConEnlaces(__DIR__, 'misPhp');                   // misPhp
?>

<h3>Selecciona qué archivo quieres ejecutar</h3>
<form method="post" action="ejecutarArchivo.php">
  <select name="orden">
    <option value="/misPhp/miPrimeraPagina.php">/misPhp/miPrimeraPagina.php</option>
    <option value="/misPhp/misArchivos.php">/misPhp/misArchivos.php</option>
    <option value="/misPhp/menuArchivos.php">/misPhp/menuArchivos.php</option>
  </select>
  <button type="submit">Ejecutar</button>
</form>