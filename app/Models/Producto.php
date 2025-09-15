<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'id_categoria',
        'nombre_producto',
        'precio',
        'imagen',
        'cantidad',
        'informacion',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    // Relación con Categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    // Relación con Inventario
    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'id_producto');
    }

    // Relación con Pedidos (a través de detalles de pedido)
    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class, 'detalles_pedido', 'id_producto', 'id_pedido')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }
}