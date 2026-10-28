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

    // Un vendedor tiene muchos productos, sensores y pedidos
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function sensores()
    {
        return $this->hasMany(Sensor::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}
