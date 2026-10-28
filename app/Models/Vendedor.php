<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $table = 'vendedores';

    public $timestamps = false;

    protected $fillable = ['nif', 'email', 'password', 'nombre_puesto', 'ubicacion', 'entrega'];

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed',
    ];

    // N:M con productos a través de tienda_vendedores (lo que vende en su puesto)
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'tienda_vendedores')
            ->using(TiendaVendedor::class)
            ->withPivot('stock', 'precio_b2c', 'precio_b2b', 'minimo_b2b', 'descuento_pct');
    }

    // Un vendedor tiene muchos sensores y pedidos
    public function sensores()
    {
        return $this->hasMany(Sensor::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}
