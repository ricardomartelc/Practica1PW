<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Productor extends Model
{
    protected $table = 'productores';

    public $timestamps = false;

    protected $fillable = ['nif', 'email', 'password', 'nombre', 'municipio'];

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed',
    ];

    // N:M con productos a través de almacen_productores (lo que tiene en su finca)
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'almacen_productores')
            ->using(AlmacenProductor::class)
            ->withPivot('stock', 'precio_origen');
    }
}
