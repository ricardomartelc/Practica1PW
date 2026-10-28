<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $table = 'sensores';

    public $timestamps = false;

    protected $fillable = ['vendedor_id', 'codigo_dispositivo', 'tipo', 'valor_actual', 'umbral_min', 'umbral_max'];

    protected $casts = [
        'valor_actual' => 'decimal:2',
        'umbral_min' => 'decimal:2',
        'umbral_max' => 'decimal:2',
    ];

    // Un sensor pertenece al puesto (vendedor) donde está instalado
    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }
}
