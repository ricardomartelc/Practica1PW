<?php

/* 
    El código realiza dos funciones principales:

1. **Escaneo y listado dinámico de directorios:** La función `listarContenidoConEnlaces()` lee el contenido de tres carpetas del proyecto 
(`public`, `practica` y `misPhp`), omite los elementos especiales (`.` y `..`) y genera una lista HTML con enlaces adaptados al tipo de 
archivo (URLs directas a la raíz web, rutas de Laravel o archivos ejecutables PHP).

2. **Lanzador de scripts mediante formulario:** Muestra un menú desplegable HTML (`<select>`) con una lista de archivos `.php` y un botón 
que envía mediante `POST` la opción seleccionada a `ejecutarArchivo.php` para su procesamiento.
*/


function listarContenidoConEnlaces(string $directorio, string $tipo): void {  // Indica el tipo de dato que va arecibir la función.
                                                                        // el tipo de parametro indica si es public, practica, o misPhp   
    echo "<h3>Contenido de la carpeta <i>" . basename($directorio) . "</i>:</h3><ul>";  // Muestra el nombre de la carpeta que se está listando. 
    // La parte final de la ruta del directorio se obtiene con basename($directorio). Se abre una lista HTML para mostrar los elementos.
    if (is_dir($directorio)) {  // Verifica si el directorio existe y es un directorio válido.
        foreach (scandir($directorio) as $item) {    // Itera sobre los elementos del directorio usando scandir($directorio), 
                                                    // (sacandir lee el contenido del directorio) 
                                                    // que devuelve un array con los nombres de los archivos y carpetas.

            if ($item === '.' || $item === '..') continue;   // el . representa el directorio actual y .. representa el directorio padre. Se omiten estos elementos para no listarlos.
            $ruta = $directorio . DIRECTORY_SEPARATOR . $item; // Directory_separator es una constante que representa el separador de directorios del sistema operativo.
            $esDir = is_dir($ruta); // Verifica si el elemento actual es un directorio es a su vez una subcarpeta.
            


            if ($tipo === 'public') { // Si el tipo es 'public', se listan los elementos del directorio public.
                if ($esDir) {   // Si es un directorio, se muestra como un elemento de lista con la etiqueta (dir) para indicar que es una carpeta.
                    echo "<li>" . $item . " (dir)</li>"; // Muestra el nombre del directorio y la etiqueta (dir) para indicar que es una carpeta.
                } else {
                    echo "<li><a href='http://pweb26.test/$item'>$item</a></li>"; // Si es un archivo, se muestra como un enlace HTML que apunta a la URL correspondiente en el servidor local (http://pweb26.test/$item).
                }

            } elseif ($tipo === 'practica') { // Si el tipo es 'practica', se listan los elementos del directorio practica.
                $nombreSinExt = pathinfo($item, PATHINFO_FILENAME); // Se obtiene el nombre del archivo sin la extensión, transforma ricardo.blade.php ricardo

            } elseif ($tipo === 'misPhp') {
                if (pathinfo($item, PATHINFO_EXTENSION) === 'php') {  // Si el tipo es 'misPhp', se listan solo los archivos con extension .php
                    echo "<li><a href='/misPhp/$item'>$item</a></li>";  // Se muestra como un enlace HTML que apunta a la URL correspondiente en el servidor local (/misPhp/$item).
                } else {
                    echo "<li>$item</li>";  // Si no es un archivo PHP, se muestra solo el nombre del archivo sin enlace. Solo los archivos .php serán listados como enlaces
                }
            }
        }
        
    } else {
        echo "<li>El directorio no existe.</li>";    // si no, el  directorio no existe. muestra el mensaje.
    }
    echo "</ul>";
}
                                // __DIR__ sube niveles en el arbol de directorios
$raiz = dirname(__DIR__, 2); // Raíz del proyecto Laravel. dirname(__DIR__, 2) obtiene el directorio padre del directorio actual dos niveles hacia arriba, que corresponde a la raíz del proyecto Laravel.
listarContenidoConEnlaces(dirname(__DIR__), 'public');          // public     //utilizamos la funcion creada para listar el contenido de la carpeta public, indicando que es de tipo 'public'.
listarContenidoConEnlaces($raiz . '/practica', 'practica');       // practica (privada)
listarContenidoConEnlaces(__DIR__, 'misPhp');                   // misPhp
?>

<h3>Selecciona qué archivo quieres ejecutar</h3>
<form method="post" action="ejecutarArchivo.php">   // envia la orden a ejecutar al archivo ejecutarArchivo.php mediante el método POST
  <select name="orden">   // crea un menú desplegable para seleccionar la orden a ejecutar
    <option value="/misPhp/miPrimeraPagina.php">/misPhp/miPrimeraPagina.php</option>
    <option value="/misPhp/misArchivos.php">/misPhp/misArchivos.php</option>
    <option value="/misPhp/menuArchivos.php">/misPhp/menuArchivos.php</option>
  </select>
  <button type="submit">Ejecutar</button>
</form>