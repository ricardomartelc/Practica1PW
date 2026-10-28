<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    // Tabla de la base de datos a la que representa este modelo
    protected $table = 'clientes';

    // Nuestras tablas no tienen las columnas created_at y updated_at
    public $timestamps = false;

    // Columnas que se pueden rellenar de golpe (por ejemplo, desde un formulario)
    protected $fillable = ['nif', 'email', 'password', 'nombre', 'apellidos', 'tipo'];

    // Columnas que nunca se muestran al convertir el modelo a JSON
    protected $hidden = ['password'];

    // La contraseña se guarda cifrada automáticamente
    protected $casts = [
        'password' => 'hashed',
    ];

    // Un cliente tiene muchos pedidos (pedidos.cliente_id)
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}
