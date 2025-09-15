<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'inventarios';

    protected $fillable = [
        'id_administrador',
        'id_producto',
        'tipo_movimiento',
        'numero_factura',
        'proveedor',
        'fecha_movimiento',
    ];

    protected $casts = [
        'fecha_movimiento' => 'date',
    ];

    // Relación con Administrador
    public function administrador()
    {
        return $this->belongsTo(Administrador::class, 'id_administrador');
    }

    // Relación con Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
}