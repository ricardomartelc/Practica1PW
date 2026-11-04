<?php
$opcionesFormulario = [];

function listarContenidoConEnlaces(string $directorio, string $tipo, array &$opciones): void {
    echo "<h3>Contenido de la carpeta <i>" . htmlspecialchars(basename($directorio)) . "</i>:</h3><ul>";
    if (is_dir($directorio)) {
        foreach (scandir($directorio) as $item) {
            if (str_starts_with($item, '.')) continue;
            $ruta = $directorio . DIRECTORY_SEPARATOR . $item;
            $esDir = is_dir($ruta);

            if ($tipo === 'public') {
                if ($esDir) {
                    echo "<li>" . htmlspecialchars($item) . " (dir)</li>";
                } else {
                    $nombreSinExt = pathinfo($item, PATHINFO_FILENAME);
                    $nombreLimpio = str_replace('Publico', '', $nombreSinExt);
                    $url = (pathinfo($item, PATHINFO_EXTENSION) === 'html') ? "/$nombreLimpio" : "/$item";
                    
                    echo "<li><a href='http://pweb26.test" . htmlspecialchars($url) . "'>" . htmlspecialchars($item) . "</a></li>";
                    $opciones[$url] = "'$url'";
                }
            } elseif ($tipo === 'practica') {
                $nombreSinExt = pathinfo($item, PATHINFO_FILENAME);
                $nombreLimpio = str_replace('Privado', '', $nombreSinExt);
                $url = "/paginaPersonal/$nombreLimpio";
                
                echo "<li><a href='http://pweb26.test" . htmlspecialchars($url) . "'>" . htmlspecialchars($item) . "</a></li>";
                $opciones[$url] = "'$url'";
            } elseif ($tipo === 'misPhp') {
                if (pathinfo($item, PATHINFO_EXTENSION) === 'php') {
                    $url = "/misPhp/$item";
                    echo "<li><a href='" . htmlspecialchars($url) . "'>" . htmlspecialchars($item) . "</a></li>";
                    $opciones[$url] = "'$url'";
                } else {
                    echo "<li>" . htmlspecialchars($item) . "</li>";
                }
            }
        }
    } else {
        echo "<li>El directorio no existe.</li>";
    }
    echo "</ul>";
}

$raiz = dirname(__DIR__, 2);
listarContenidoConEnlaces(dirname(__DIR__), 'public', $opcionesFormulario);
listarContenidoConEnlaces($raiz . '/practica', 'practica', $opcionesFormulario);
listarContenidoConEnlaces(__DIR__, 'misPhp', $opcionesFormulario);
?>

<h3>Selecciona qué archivo quieres ejecutar</h3>
<form method="post" action="ejecutarArchivo.php">
  <select name="orden">
    <?php foreach ($opcionesFormulario as $valorPeticion => $textoDesplegable): ?>
      <option value="<?= htmlspecialchars($valorPeticion) ?>"><?= htmlspecialchars($textoDesplegable) ?></option>
    <?php endforeach; ?>
  </select>
  <button type="submit">Ejecutar</button>
</form>