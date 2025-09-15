<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    protected $table = 'envios';

    protected $fillable = [
        'id_pedido',
        'fecha_envio',
        'estado_envio',
    ];

    protected $casts = [
        'fecha_envio' => 'date',
    ];

    // Relación con Pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }
}