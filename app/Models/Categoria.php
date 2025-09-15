<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = [
        'nombre_categoria',
        'talla',
        'colores',
        'modelo',
    ];

    // Relación con Productos
    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_categoria');
    }
}