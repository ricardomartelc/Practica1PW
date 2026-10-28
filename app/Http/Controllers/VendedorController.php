<?php

namespace App\Http\Controllers;

use App\Models\Vendedor;

class VendedorController extends Controller
{
    // Lista de todos los puestos
    public function index()
    {
        $vendedores = Vendedor::orderBy('nombre_puesto')->get();

        return view('puestos.index', ['vendedores' => $vendedores]);
    }

    // Página de un puesto con sus productos. {vendedor} es el id; si no existe, Laravel da 404
    public function show(Vendedor $vendedor)
    {
        return view('puestos.show', ['vendedor' => $vendedor]);
    }
}
