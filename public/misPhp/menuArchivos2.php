<?php
// Aquí se guardan todos los enlaces
$enlaces = [];

// Muestra el contenido de una carpeta con enlaces
function mostrarCarpeta($titulo, $ruta) {
    global $enlaces;
    echo "<h2>Contenido de la carpeta <i>$titulo</i>:</h2>";
    echo "<ul>";
    $archivos = scandir($ruta);
    foreach ($archivos as $nombre) {
        // saltar . y ..
        if ($nombre == "." || $nombre == "..") {
            continue;
        }
        // los directorios van sin enlace
        if (is_dir("$ruta/$nombre")) {
            echo "<li>$nombre (dir)</li>";
            continue;
        }
        // decidir el enlace según la carpeta
        $enlace = "";
        switch ($titulo) {
            case "public":
                if (strpos($nombre, "Publico.html") !== false) {
                    $enlace = "/" . str_replace("Publico.html", "", $nombre);
                }
                break;
            case "practica":
                if (strpos($nombre, "Privado.html") !== false) {
                    $enlace = "/paginaPersonal/" . str_replace("Privado.html", "", $nombre);
                }
                break;
            case "misPhp":
                if (strpos($nombre, ".php") !== false) {
                    $enlace = "/misPhp/$nombre";
                }
                break;
        }
        // con enlace o sin enlace
        if ($enlace == "") {
            echo "<li>$nombre</li>";
        } else {
            $enlaces[] = $enlace;
            echo "<li><a href='$enlace'>$nombre</a></li>";
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
        <?php
        foreach ($enlaces as $enlace) {
            echo "<option value='$enlace'>$enlace</option>";
        }
        ?>
    </select>
    <input type="submit" value="Ejecutar">
</form>
