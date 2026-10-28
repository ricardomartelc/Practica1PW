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

    // Un productor tiene muchos productos
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
