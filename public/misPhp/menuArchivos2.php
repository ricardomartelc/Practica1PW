<?php
$enlaces = []; // aquí se guardan todos los enlaces

// Muestra el contenido de una carpeta; los archivos válidos llevan enlace
function mostrarCarpeta($titulo, $ruta) {
    global $enlaces;
    echo "<h2>Contenido de la carpeta <i>$titulo</i>:</h2>";
    echo "<ul>";
    foreach (scandir($ruta) as $nombre) {
        if ($nombre == "." || $nombre == "..") continue; // saltar . y ..
        if (is_dir("$ruta/$nombre")) {
            echo "<li>$nombre (dir)</li>"; // directorios sin enlace
            continue;
        }
        $enlace = "";
        if ($titulo == "public" && substr($nombre, -12) == "Publico.html") {
            $enlace = "/" . substr($nombre, 0, -12);
        } elseif ($titulo == "practica" && substr($nombre, -12) == "Privado.html") {
            $enlace = "/paginaPersonal/" . substr($nombre, 0, -12);
        } elseif ($titulo == "misPhp" && substr($nombre, -4) == ".php") {
            $enlace = "/misPhp/$nombre";
        }
        if ($enlace != "") {
            $enlaces[] = $enlace; // guardar el enlace
            echo "<li><a href='$enlace'>$nombre</a></li>";
        } else {
            echo "<li>$nombre</li>"; // sin enlace
        }
    }
    echo "</ul>";
}

mostrarCarpeta("public", "..");
mostrarCarpeta("practica", "../../practica");
mostrarCarpeta("misPhp", ".");
?>
<h2>Selecciona que archivo quieres ejecutar</h2>
<form method="POST" action="ejecutarArchivo2.php">
    <select name="orden">
        <?php foreach ($enlaces as $e) echo "<option value='$e'>$e</option>"; ?>
    </select>
    <button type="submit">Ejecutar</button>
</form>
