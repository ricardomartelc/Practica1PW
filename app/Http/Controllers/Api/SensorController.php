<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function index()
    {
        return Sensor::all();
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'vendedor_id'        => 'required|exists:vendedores,id',
            'codigo_dispositivo' => 'required|string|max:100|unique:sensores,codigo_dispositivo',
            'tipo'               => 'required|in:temperatura,humedad',
            'umbral_min'         => 'required|numeric',
            'umbral_max'         => 'required|numeric|gt:umbral_min',
        ]);

        return response()->json(Sensor::create($datos), 201);
    }

    public function show(Sensor $sensor)
    {
        return $sensor;
    }

    public function update(Request $request, Sensor $sensor)
    {
        $datos = $request->validate([
            'umbral_min' => 'sometimes|numeric',
            'umbral_max' => 'sometimes|numeric',
        ]);

        $sensor->update($datos);
        return $sensor;
    }

    public function destroy(Sensor $sensor)
    {
        $sensor->delete();
        return response()->noContent(); // 204
    }

    // Endpoint extra: registrar una lectura y devolver si hay alerta (regla R6)
    public function lectura(Request $request, Sensor $sensor)
    {
        $datos = $request->validate(['valor' => 'required|numeric']);

        $sensor->update(['valor_actual' => $datos['valor']]);

        $alerta = $sensor->valor_actual < $sensor->umbral_min
               || $sensor->valor_actual > $sensor->umbral_max;

        return response()->json([
            'sensor'  => $sensor->codigo_dispositivo,
            'valor'   => $sensor->valor_actual,
            'alerta'  => $alerta,
        ]);
    }
}