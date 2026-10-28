<?php

namespace App\Http\Controllers;

use App\Models\Producto;

class ProductoController extends Controller
{
    // Lista de todos los productos
    public function index()
    {
        $productos = Producto::orderBy('nombre')->get();

        return view('productos.index', ['productos' => $productos]);
    }

    // Ficha de un producto. {producto} es el id; si no existe, Laravel da 404
    public function show(Producto $producto)
    {
        return view('productos.show', ['producto' => $producto]);
    }
}
