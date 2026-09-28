<?php

use Illuminate\Support\Facades\Route;

// Ruta raíz por defecto
Route::get('/', function () {
    return view('welcome');
});

// Rutas públicas de los miembros del grupo (Apartado 3.2)
Route::get('/Ricardo', function () {
    return redirect('RicardoPublico.html');
});

Route::get('/Rosalinda', function () {
    return redirect('RosalindaPublico.html');
});

Route::get('/Adrian', function () {
    return redirect('AdrianPublico.html');
});

// Ruta privada alojada en la carpeta 'practica' (Apartado 3.3)
Route::get('/RicardoPrivado', function () {
    return include('../practica/RicardoPrivado.html');
});

Route::get('/RosalindaPrivado', function () {
    return include('../practica/RosalindaPrivado.html');
});



Route::get('/paginaPersonal/{nombre}', function ($nombre) {
    $path = base_path("practica/{$nombre}Privado.html");

    if (!file_exists($path)) {
        abort(404, 'Página privada no encontrada.');
    }

    $html = file_get_contents($path);

    // Extraemos solo lo que está dentro del body del archivo HTML privado
    if (preg_match('/<body[^>]*>(.*?)<\/body>/s', $html, $matches)) {
        $contenido = $matches[1];
        
        // Opcional: eliminamos el <nav> antiguo que tuviera dentro para que no choque con el de Blade
        $contenido = preg_replace('/<nav\b[^>]*>(.*?)<\/nav>/is', '', $contenido);
    } else {
        $contenido = $html;
    }

    return view('paginaPersonal_Grupo1', [
        'nombre' => $nombre,
        'contenido' => $contenido
    ]);
});




Route::get('/elGrupo', function () {
    return view('elGrupo');
});




// para el bloque 5:

// Rutas para la página Home del portal
Route::get('/miPortal', function () {
    return view('miportal.home');
});

Route::get('/miPortal/home', function () {
    return view('miportal.home');
});

Route::get('/miPortal/acerca', function () {
    return view('miportal.home'); // Temporalmente usando home para evitar errores si no existe otra vista
});