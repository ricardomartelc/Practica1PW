<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TiendaVendedor extends Pivot
{
    // Tabla asociativa vendedor - producto (rombo «tienda»)
    protected $table = 'tienda_vendedores';

    // Clave primaria compuesta (vendedor_id, producto_id): no hay id autoincremental
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['vendedor_id', 'producto_id', 'stock', 'precio_b2c', 'precio_b2b', 'minimo_b2b', 'descuento_pct'];

    protected $casts = [
        'precio_b2c' => 'decimal:2',
        'precio_b2b' => 'decimal:2',
    ];

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
