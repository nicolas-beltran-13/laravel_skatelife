<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicacionController extends Controller
{
    public function index()
    {
        $publicaciones = Publicacion::with('cliente')->orderBy('fecha_publicacion', 'desc')->get();
        return view('publicaciones.index', compact('publicaciones'));
    }

    public function create()
    {
        return view('publicaciones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_cliente' => 'required|exists:clientes,id',
            'nombre_publicacion' => 'required|string',
            'archivo' => 'required|file|max:5120', // 5MB max
            'descripcion' => 'required|string',
        ]);

        // Manejar la carga del archivo
        $archivoPath = $request->file('archivo')->store('publicaciones', 'public');

        Publicacion::create([
            'id_cliente' => $request->id_cliente,
            'fecha_publicacion' => now(),
            'nombre_publicacion' => $request->nombre_publicacion,
            'archivo' => $archivoPath,
            'descripcion' => $request->descripcion,
            'reacciones' => '0', // Inicialmente sin reacciones
            'comentarios' => '', // Inicialmente sin comentarios
        ]);

        return redirect()->route('publicaciones.index')
            ->with('success', 'Publicación creada exitosamente');
    }

    public function show(Publicacion $publicacion)
    {
        $publicacion->load('cliente');
        return view('publicaciones.show', compact('publicacion'));
    }

    public function edit(Publicacion $publicacion)
    {
        return view('publicaciones.edit', compact('publicacion'));
    }

    public function update(Request $request, Publicacion $publicacion)
    {
        $request->validate([
            'nombre_publicacion' => 'required|string',
            'archivo' => 'nullable|file|max:5120',
            'descripcion' => 'required|string',
        ]);

        if ($request->hasFile('archivo')) {
            // Eliminar archivo anterior
            if ($publicacion->archivo) {
                Storage::disk('public')->delete($publicacion->archivo);
            }
            // Guardar nuevo archivo
            $archivoPath = $request->file('archivo')->store('publicaciones', 'public');
            $publicacion->archivo = $archivoPath;
        }

        $publicacion->update([
            'nombre_publicacion' => $request->nombre_publicacion,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('publicaciones.index')
            ->with('success', 'Publicación actualizada exitosamente');
    }

    public function destroy(Publicacion $publicacion)
    {
        // Eliminar archivo
        if ($publicacion->archivo) {
            Storage::disk('public')->delete($publicacion->archivo);
        }

        $publicacion->delete();

        return redirect()->route('publicaciones.index')
            ->with('success', 'Publicación eliminada exitosamente');
    }

    public function agregarReaccion(Request $request, Publicacion $publicacion)
    {
        $request->validate([
            'tipo_reaccion' => 'required|string|max:40'
        ]);

        // Aquí podrías implementar la lógica para manejar las reacciones
        // Por ejemplo, incrementar un contador o agregar a una lista
        $reacciones = $publicacion->reacciones;
        $publicacion->update([
            'reacciones' => $reacciones + 1
        ]);

        return back()->with('success', 'Reacción agregada');
    }

    public function agregarComentario(Request $request, Publicacion $publicacion)
    {
        $request->validate([
            'comentario' => 'required|string'
        ]);

        // Aquí podrías implementar la lógica para manejar los comentarios
        // Por ejemplo, agregar a una lista de comentarios
        $comentarios = $publicacion->comentarios;
        $nuevoComentario = [
            'usuario_id' => auth()->id(),
            'contenido' => $request->comentario,
            'fecha' => now()
        ];

        $publicacion->update([
            'comentarios' => $comentarios . "\n" . json_encode($nuevoComentario)
        ]);

        return back()->with('success', 'Comentario agregado');
    }
}