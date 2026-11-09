<?php
// Comprobar si llega la orden por POST
if (!empty($_POST["orden"])) {
    $orden = $_POST["orden"];
    echo "La orden a ejecutar es <a href='$orden'>$orden</a>";
} else {
    echo "No existe ninguna orden a ejecutar";
}
