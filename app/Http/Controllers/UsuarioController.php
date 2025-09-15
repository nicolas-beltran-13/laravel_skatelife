<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Cliente;
use App\Models\Administrador;
use App\Models\Moderador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::all();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numide' => 'required|string|max:11',
            'nombre' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'edad' => 'required|integer',
            'direccion' => 'required|string|max:25',
            'telefono' => 'required|string|max:11',
            'correo' => 'required|email|unique:usuarios',
            'contrasena' => 'required|min:6',
            'tipo' => 'required|in:cliente,administrador,moderador'
        ]);

        $usuario = Usuario::create([
            'numide' => $request->numide,
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'edad' => $request->edad,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'contrasena' => Hash::make($request->contrasena)
        ]);

        // Crear el rol específico según el tipo de usuario
        switch($request->tipo) {
            case 'cliente':
                Cliente::create([
                    'id_usuario' => $usuario->id,
                    'tipo_usuario' => 'regular',
                    'fecha_registro' => now()
                ]);
                break;
            case 'administrador':
                Administrador::create([
                    'id_usuario' => $usuario->id,
                    'registro_acciones' => '',
                    'permisos_moderacion' => 'total',
                    'normas' => '',
                    'control' => 'activo'
                ]);
                break;
            case 'moderador':
                Moderador::create([
                    'id_usuario' => $usuario->id,
                    'registro_producto' => '',
                    'coordinador' => ''
                ]);
                break;
        }

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    public function show(Usuario $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    public function edit(Usuario $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'edad' => 'required|integer',
            'direccion' => 'required|string|max:25',
            'telefono' => 'required|string|max:11',
            'correo' => 'required|email|unique:usuarios,correo,' . $usuario->id,
        ]);

        $usuario->update($request->all());

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    public function destroy(Usuario $usuario)
    {
        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }
}