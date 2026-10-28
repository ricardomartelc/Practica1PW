<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    protected $table = 'detalles';

    public $timestamps = false;

    protected $fillable = ['pedido_id', 'producto_id', 'cantidad', 'precio_aplicado'];

    protected $casts = [
        'precio_aplicado' => 'decimal:2',
    ];

    // Una línea pertenece a un pedido y a un producto
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
