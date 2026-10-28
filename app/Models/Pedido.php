<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';

    public $timestamps = false;

    protected $fillable = ['cliente_id', 'vendedor_id', 'fecha', 'total', 'estado', 'direccion_entrega'];

    // La fecha se convierte en un objeto fecha y el total en decimal
    protected $casts = [
        'fecha' => 'datetime',
        'total' => 'decimal:2',
    ];

    // Un pedido pertenece a un cliente y a un vendedor
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

    // Un pedido tiene muchas líneas de detalle
    public function detalles()
    {
        return $this->hasMany(Detalle::class);
    }
}
