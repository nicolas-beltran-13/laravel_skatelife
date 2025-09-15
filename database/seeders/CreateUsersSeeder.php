<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Administrador;
use App\Models\Moderador;
use App\Models\Cliente;
use Illuminate\Support\Facades\Hash;

class CreateUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Crear un usuario administrador
        $admin = Usuario::create([
            'numide' => '1000000001',
            'nombre' => 'Admin',
            'apellidos' => 'Principal',
            'edad' => 30,
            'direccion' => 'Calle Admin 123',
            'telefono' => '3001234567',
            'correo' => 'admin@skatelife.com',
            'contrasena' => Hash::make('admin123')
        ]);

        Administrador::create([
            'id_usuario' => $admin->id,
            'registro_acciones' => 'Creación inicial',
            'permisos_moderacion' => 'total',
            'normas' => 'Normas generales',
            'control' => 'activo'
        ]);

        // Crear un usuario moderador
        $moderador = Usuario::create([
            'numide' => '1000000002',
            'nombre' => 'Moderador',
            'apellidos' => 'Principal',
            'edad' => 25,
            'direccion' => 'Calle Mod 456',
            'telefono' => '3007654321',
            'correo' => 'moderador@skatelife.com',
            'contrasena' => Hash::make('mod123')
        ]);

        Moderador::create([
            'id_usuario' => $moderador->id,
            'registro_producto' => 'Activo',
            'coordinador' => 'Principal'
        ]);

        // Crear un cliente de prueba
        $cliente = Usuario::create([
            'numide' => '1000000003',
            'nombre' => 'Cliente',
            'apellidos' => 'Prueba',
            'edad' => 20,
            'direccion' => 'Calle Cliente 789',
            'telefono' => '3009876543',
            'correo' => 'cliente@ejemplo.com',
            'contrasena' => Hash::make('cliente123')
        ]);

        Cliente::create([
            'id_usuario' => $cliente->id,
            'tipo_usuario' => 'regular',
            'fecha_registro' => now()
        ]);
    }
}
