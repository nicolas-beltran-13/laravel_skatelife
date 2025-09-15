<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Administrador;
use App\Models\Moderador;
use App\Models\Cliente;

class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'numide',
        'nombre',
        'apellidos',
        'edad',
        'direccion',
        'telefono',
        'correo',
        'contrasena',
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    // Relaciones
    public function administrador()
    {
        return $this->hasOne(Administrador::class);
    }

    public function moderador()
    {
        return $this->hasOne(Moderador::class);
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }
}