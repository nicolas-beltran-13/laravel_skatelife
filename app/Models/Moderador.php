<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Moderador extends Model
{
    protected $table = 'moderadores';

    protected $fillable = [
        'id_usuario',
        'registro_producto',
        'coordinador',
    ];

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}