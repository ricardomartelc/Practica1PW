<?php
$permitidos = [
    '/misPhp/menuArchivos.php',
    '/misPhp/miPrimeraPagina.php',
    '/misPhp/misArchivos.php'
];

$orden = $_POST['orden'] ?? null;

if ($orden && in_array($orden, $permitidos, true)) {
    echo "La orden a ejecutar es <a href='$orden'>$orden</a>";
} else {
    echo "No existe ninguna orden a ejecutar";
}
?>