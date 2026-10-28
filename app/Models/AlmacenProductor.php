<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AlmacenProductor extends Pivot
{
    // Tabla asociativa productor - producto (rombo «almacén»)
    protected $table = 'almacen_productores';

    // Clave primaria compuesta (productor_id, producto_id): no hay id autoincremental
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['productor_id', 'producto_id', 'stock', 'precio_origen'];

    protected $casts = [
        'precio_origen' => 'decimal:2',
    ];

    public function productor()
    {
        return $this->belongsTo(Productor::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
