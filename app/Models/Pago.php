<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'id_pedido',
        'fecha_pago',
        'estado_pago',
        'metodo_pago',
    ];

    protected $casts = [
        'fecha_pago' => 'date',
    ];

    // Relación con Pedido
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido');
    }
}