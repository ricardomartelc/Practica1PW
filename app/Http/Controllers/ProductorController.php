<?php

namespace App\Http\Controllers;

use App\Models\Productor;

class ProductorController extends Controller
{
    // Lista de todos los productores
    public function index()
    {
        $productores = Productor::orderBy('nombre')->get();

        return view('productores.index', ['productores' => $productores]);
    }

    // Ficha de un productor con lo que tiene en su finca. {productor} es el id
    public function show(Productor $productor)
    {
        return view('productores.show', ['productor' => $productor]);
    }
}
