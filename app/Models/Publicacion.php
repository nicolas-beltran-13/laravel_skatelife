<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publicacion extends Model
{
    protected $table = 'publicaciones';

    protected $fillable = [
        'id_cliente',
        'fecha_publicacion',
        'nombre_publicacion',
        'archivo',
        'descripcion',
        'reacciones',
        'comentarios',
    ];

    protected $casts = [
        'fecha_publicacion' => 'datetime',
    ];

    // Relación con Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }
}