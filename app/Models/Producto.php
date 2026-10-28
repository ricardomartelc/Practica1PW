<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    public $timestamps = false;

    protected $fillable = [
        'vendedor_id', 'productor_id', 'nombre', 'categoria', 'unidad', 'stock',
        'precio_b2c', 'precio_b2b', 'minimo_b2b', 'descuento_pct', 'co2_por_unidad',
    ];

    // Los precios y el CO2 se leen siempre con 2 decimales
    protected $casts = [
        'precio_b2c' => 'decimal:2',
        'precio_b2b' => 'decimal:2',
        'co2_por_unidad' => 'decimal:2',
    ];

    // Un producto pertenece a un vendedor y a un productor
    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

    public function productor()
    {
        return $this->belongsTo(Productor::class);
    }

    // Un producto aparece en muchas líneas de pedido
    public function detalles()
    {
        return $this->hasMany(Detalle::class);
    }
}
