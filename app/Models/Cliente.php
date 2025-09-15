<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'id_usuario',
        'tipo_usuario',
        'fecha_registro',
    ];

    protected $casts = [
        'fecha_registro' => 'date',
    ];

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    // Relación con Pedidos
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_cliente');
    }

    // Relación con Publicaciones
    public function publicaciones()
    {
        return $this->hasMany(Publicacion::class, 'id_cliente');
    }
}