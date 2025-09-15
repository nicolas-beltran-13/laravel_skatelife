<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'id_cliente',
        'id_producto',
        'pago_pedido',
        'estado_pedido',
        'prioridad_envio',
        'direccion_entrega',
        'direccion_salida',
    ];

    // Relación con Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    // Relación con Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    // Relación con Envío
    public function envio()
    {
        return $this->hasOne(Envio::class, 'id_pedido');
    }

    // Relación con Pago
    public function pago()
    {
        return $this->hasOne(Pago::class, 'id_pedido');
    }
}