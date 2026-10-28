<?php

use App\Http\Controllers\GestionPuestoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProductorController;
use App\Http\Controllers\VendedorController;
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
| Bloque 4, apartado 4.2 — Ruta con parámetro + plantilla paginaPersonal_Grupo4
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

    return view('paginaPersonal_Grupo4', [
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

/*
|--------------------------------------------------------------------------
| Práctica 3, Bloque 4 — Catálogo de productos (ProductoController)
| http://pweb26.test/productos               → lista de productos
| http://pweb26.test/productos/{producto}    → ficha de un producto
|--------------------------------------------------------------------------
*/
Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/{producto}', [ProductoController::class, 'show'])->name('productos.show');

/*
|--------------------------------------------------------------------------
| Práctica 3, Bloque 4 — Puestos (VendedorController)
| http://pweb26.test/puestos               → lista de puestos
| http://pweb26.test/puestos/{vendedor}    → página de un puesto con sus productos
|--------------------------------------------------------------------------
*/
Route::get('/puestos', [VendedorController::class, 'index'])->name('puestos.index');
Route::get('/puestos/{vendedor}', [VendedorController::class, 'show'])->name('puestos.show');

/*
|--------------------------------------------------------------------------
| Práctica 3, Bloque 4 — Productores (ProductorController)
| http://pweb26.test/productores               → lista de productores
| http://pweb26.test/productores/{productor}   → ficha de un productor
|--------------------------------------------------------------------------
*/
Route::get('/productores', [ProductorController::class, 'index'])->name('productores.index');
Route::get('/productores/{productor}', [ProductorController::class, 'show'])->name('productores.show');

/*
|--------------------------------------------------------------------------
| Práctica 3, Bloque 4 — Caso de uso 4: gestión de los productos de un puesto
| (GestionPuestoController, tabla tienda_vendedores)
| GET    /puestos/{vendedor}/gestion                     → lista + formulario de añadir
| POST   /puestos/{vendedor}/gestion                     → añadir producto
| GET    /puestos/{vendedor}/gestion/{producto}/editar   → formulario de editar
| PUT    /puestos/{vendedor}/gestion/{producto}          → guardar cambios
| DELETE /puestos/{vendedor}/gestion/{producto}          → quitar del puesto
|--------------------------------------------------------------------------
*/
Route::get('/puestos/{vendedor}/gestion', [GestionPuestoController::class, 'index'])->name('gestion.index');
Route::post('/puestos/{vendedor}/gestion', [GestionPuestoController::class, 'store'])->name('gestion.store');
Route::get('/puestos/{vendedor}/gestion/{producto}/editar', [GestionPuestoController::class, 'edit'])->name('gestion.edit');
Route::put('/puestos/{vendedor}/gestion/{producto}', [GestionPuestoController::class, 'update'])->name('gestion.update');
Route::delete('/puestos/{vendedor}/gestion/{producto}', [GestionPuestoController::class, 'destroy'])->name('gestion.destroy');
