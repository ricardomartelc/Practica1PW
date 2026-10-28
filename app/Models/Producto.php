<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    // Catálogo genérico: el precio y el stock están en almacen_productores y tienda_vendedores
    protected $table = 'productos';

    public $timestamps = false;

    protected $fillable = ['nombre', 'categoria', 'unidad', 'co2_por_unidad'];

    protected $casts = [
        'co2_por_unidad' => 'decimal:2',
    ];

    // N:M con productores a través de almacen_productores (stock y precio de origen)
    public function productores()
    {
        return $this->belongsToMany(Productor::class, 'almacen_productores')
            ->using(AlmacenProductor::class)
            ->withPivot('stock', 'precio_origen');
    }

    // N:M con vendedores a través de tienda_vendedores (stock y precios de venta)
    public function vendedores()
    {
        return $this->belongsToMany(Vendedor::class, 'tienda_vendedores')
            ->using(TiendaVendedor::class)
            ->withPivot('stock', 'precio_b2c', 'precio_b2b', 'minimo_b2b', 'descuento_pct');
    }

    // Un producto aparece en muchas líneas de pedido
    public function detalles()
    {
        return $this->hasMany(Detalle::class);
    }
}
