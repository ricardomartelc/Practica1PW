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