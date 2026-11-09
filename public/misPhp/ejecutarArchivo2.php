<?php
// Mostrar la orden recibida por POST
if (!empty($_POST["orden"])) {
    $orden = htmlspecialchars($_POST["orden"]);
    echo "La orden a ejecutar es <a href='$orden'>$orden</a>";
} else {
    echo "No existe ninguna orden a ejecutar";
}
