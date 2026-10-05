<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Ruta raíz por defecto
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Bloque 3, apartado 3.2 — Rutas públicas de los miembros del grupo
| http://pweb26.test/Ricardo, /Rosalinda, /Adrian
|--------------------------------------------------------------------------
*/
Route::get('/Ricardo', function () {
    return redirect('RicardoPublico.html');
});

Route::get('/Rosalinda', function () {
    return redirect('RosalindaPublico.html');
});

Route::get('/Adrian', function () {
    return redirect('AdrianPublico.html');
});

/*
|--------------------------------------------------------------------------
| Bloque 3, apartado 3.3 — Rutas privadas
| Los archivos viven en /practica, FUERA de public, por lo que no son
| accesibles directamente por URL; esta ruta los sirve a través de Laravel.
| http://pweb26.test/RicardoPrivado, /RosalindaPrivado, /AdrianPrivado
|--------------------------------------------------------------------------
*/
Route::get('/RicardoPrivado', function () {
    $path = base_path('practica/RicardoPrivado.html');
    if (!file_exists($path)) {
        abort(404, 'Archivo no encontrado.');
    }
    return response()->file($path);
});

Route::get('/RosalindaPrivado', function () {
    $path = base_path('practica/RosalindaPrivado.html');
    if (!file_exists($path)) {
        abort(404, 'Archivo no encontrado.');
    }
    return response()->file($path);
});

Route::get('/AdrianPrivado', function () {
    $path = base_path('practica/AdrianPrivado.html');
    if (!file_exists($path)) {
        abort(404, 'Archivo no encontrado.');
    }
    return response()->file($path);
});

/*
|--------------------------------------------------------------------------
| Bloque 4, apartado 4.2 — Ruta con parámetro + plantilla paginaPersonal_Grupo1
| http://pweb26.test/paginaPersonal/Ricardo (también Rosalinda, Adrian...)
|--------------------------------------------------------------------------
*/
Route::get('/paginaPersonal/{nombre}', function ($nombre) {
    $path = base_path("practica/{$nombre}Privado.html");

    if (!file_exists($path)) {
        abort(404, 'Página privada no encontrada.');
    }

    $html = file_get_contents($path);

    if (preg_match('/<body[^>]*>(.*?)<\/body>/s', $html, $matches)) {
        $contenido = $matches[1];
        $contenido = preg_replace('/<nav\b[^>]*>(.*?)<\/nav>/is', '', $contenido);
    } else {
        $contenido = $html;
    }

    return view('paginaPersonal_Grupo1', [
        'nombre' => $nombre,
        'contenido' => $contenido,
    ]);
});

/*
|--------------------------------------------------------------------------
| Bloque 4, apartado 4.3 — Ruta y plantilla para el grupo
| http://pweb26.test/elGrupo
|--------------------------------------------------------------------------
*/
Route::get('/elGrupo', function () {
    return view('elGrupo');
});

/*
|--------------------------------------------------------------------------
| Bloque 5, apartado 5.2 — Home del portal global del curso
| http://pweb26.test/miPortal y http://pweb26.test/miPortal/home
|--------------------------------------------------------------------------
*/
Route::get('/miPortal', function () {
    return view('miPortal.home');
});

Route::get('/miPortal/home', function () {
    return view('miPortal.home');
});

/*
|--------------------------------------------------------------------------
| Bloque 5, apartado 5.3 — Resto de opciones del menú del portal: Acerca de
| http://pweb26.test/miPortal/acerca
|--------------------------------------------------------------------------
*/
Route::get('/miPortal/acerca', function () {
    return view('miPortal.acerca');
});