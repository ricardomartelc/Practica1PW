<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Vendedor;
use Illuminate\Http\Request;

// Caso de uso 4: un vendedor gestiona los productos de su puesto (tabla tienda_vendedores).
// De momento se elige el puesto por la URL; cuando haya login será el del vendedor conectado.
class GestionPuestoController extends Controller
{
    // Reglas que deben cumplir los datos del formulario
    private $reglas = [
        'stock' => 'required|integer|min:0',
        'precio_b2c' => 'required|numeric|min:0',
        'precio_b2b' => 'required|numeric|min:0',
        'minimo_b2b' => 'required|integer|min:1',
        'descuento_pct' => 'required|integer|min:0|max:100',
    ];

    // Mensajes de error en español (:attribute es el nombre del campo)
    private $mensajes = [
        'required' => 'El campo :attribute es obligatorio.',
        'integer' => 'El campo :attribute debe ser un número entero.',
        'numeric' => 'El campo :attribute debe ser un número.',
        'min' => 'El campo :attribute debe ser como mínimo :min.',
        'max' => 'El campo :attribute debe ser como máximo :max.',
        'exists' => 'El producto elegido no existe.',
    ];

    // Productos del puesto y formulario para añadir uno nuevo
    public function index(Vendedor $vendedor)
    {
        // Productos del catálogo que el puesto todavía no vende
        $disponibles = Producto::whereNotIn('id', $vendedor->productos->pluck('id'))
            ->orderBy('nombre')
            ->get();

        return view('gestion.index', ['vendedor' => $vendedor, 'disponibles' => $disponibles]);
    }

    // Guarda un producto nuevo en el puesto
    public function store(Request $request, Vendedor $vendedor)
    {
        $datos = $request->validate(['producto_id' => 'required|exists:productos,id'] + $this->reglas, $this->mensajes);

        $vendedor->productos()->attach($datos['producto_id'], collect($datos)->except('producto_id')->all());

        return redirect()->route('gestion.index', $vendedor)->with('ok', 'Producto añadido al puesto.');
    }

    // Formulario para cambiar precio, stock o descuento de un producto del puesto
    public function edit(Vendedor $vendedor, Producto $producto)
    {
        $producto = $vendedor->productos()->findOrFail($producto->id);

        return view('gestion.edit', ['vendedor' => $vendedor, 'producto' => $producto]);
    }

    // Guarda los cambios
    public function update(Request $request, Vendedor $vendedor, Producto $producto)
    {
        $datos = $request->validate($this->reglas, $this->mensajes);

        $vendedor->productos()->updateExistingPivot($producto->id, $datos);

        return redirect()->route('gestion.index', $vendedor)->with('ok', 'Producto actualizado.');
    }

    // Quita un producto del puesto (el producto sigue en el catálogo)
    public function destroy(Vendedor $vendedor, Producto $producto)
    {
        $vendedor->productos()->detach($producto->id);

        return redirect()->route('gestion.index', $vendedor)->with('ok', 'Producto quitado del puesto.');
    }
}
