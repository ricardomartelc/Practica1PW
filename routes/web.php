<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/Ricardo', function () {
    return redirect('RicardoPublico.html');
});

Route::get('/Rosalinda', function () {
    return redirect('RosalindaPublico.html');
});

Route::get('/Adrian', function () {
    return redirect('AdrianPublico.html');
});

// Bloque de la página personal del Grupo 1: 
// El web.php queda limpio y delega la gestión del parámetro a la vista.
Route::get('/paginaPersonal/{nombre}', function ($nombre) {
    return view('paginaPersonal_Grupo1', compact('nombre'));
});

Route::get('/elGrupo', function () {
    return view('elGrupo');
});

Route::get('/miPortal', function () {
    return view('miportal.home');
});