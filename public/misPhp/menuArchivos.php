<?php


$opcionesFormulario = [];

function listarContenidoConEnlaces(string $directorio, string $tipo, array &$opciones): void {
    echo "<h3>Contenido de la carpeta <i>" . htmlspecialchars(basename($directorio)) . "</i>:</h3><ul>"; // Mostramos el nombre de la carpeta y abrimos una lista HTML
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
                    $url = "/misPhp/$item"; // Construimos la URL relativa para el archivo PHP
                    echo "<li><a href='" . htmlspecialchars($url) . "'>" . htmlspecialchars($item) . "</a></li>"; // Mostramos el archivo PHP como un enlace seguro en la página web
                    $opciones[$url] = "'$url'"; //  Guardamos la URL relativa en el array de opciones para el formulario
                } else {
                    echo "<li>" . htmlspecialchars($item) . "</li>"; // Mostramos el archivo que no es PHP como texto plano en la página web
                }
            }
        }
    } else {
        echo "<li>El directorio no existe.</li>"; // Mostramos un mensaje de error si el directorio no existe
    }
    echo "</ul>";
}

$raiz = dirname(__DIR__, 2); // Obtenemos la ruta del directorio raíz del proyecto (dos niveles arriba del directorio actual)
listarContenidoConEnlaces(dirname(__DIR__), 'public', $opcionesFormulario);
listarContenidoConEnlaces($raiz . '/practica', 'practica', $opcionesFormulario);
listarContenidoConEnlaces(__DIR__, 'misPhp', $opcionesFormulario);
?>

<h3>Selecciona qué archivo quieres ejecutar</h3>
<form method="post" action="ejecutarArchivo.php"> // Abrimos un formulario HTML que enviará los datos mediante POST a ejecutarArchivo.php
  <select name="orden">
    <?php foreach ($opcionesFormulario as $valorPeticion => $textoDesplegable): ?> // Iteramos sobre el array de opciones para generar las opciones del desplegable
      <option value="<?= htmlspecialchars($valorPeticion) ?>"><?= htmlspecialchars($textoDesplegable) ?></option>
    <?php endforeach; ?>
  </select>
  <button type="submit">Ejecutar</button>
</form>