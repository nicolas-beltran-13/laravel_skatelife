<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('categoria')->get();
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_categoria' => 'required|exists:categorias,id',
            'nombre_producto' => 'required|string|max:45',
            'precio' => 'required|numeric',
            'imagen' => 'required|image|max:2048', // 2MB max
            'cantidad' => 'required|integer',
            'informacion' => 'required|string|max:45'
        ]);

        // Manejar la carga de la imagen
        $imagenPath = $request->file('imagen')->store('productos', 'public');

        $producto = Producto::create([
            'id_categoria' => $request->id_categoria,
            'nombre_producto' => $request->nombre_producto,
            'precio' => $request->precio,
            'imagen' => $imagenPath,
            'cantidad' => $request->cantidad,
            'informacion' => $request->informacion
        ]);

        // Registrar en el inventario
        Inventario::create([
            'id_producto' => $producto->id,
            'tipo_movimiento' => 'entrada_inicial',
            'cantidad' => $request->cantidad,
            'fecha_movimiento' => now()
        ]);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente');
    }

    public function show(Producto $producto)
    {
        $producto->load('categoria', 'inventarios');
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'id_categoria' => 'required|exists:categorias,id',
            'nombre_producto' => 'required|string|max:45',
            'precio' => 'required|numeric',
            'imagen' => 'nullable|image|max:2048',
            'cantidad' => 'required|integer',
            'informacion' => 'required|string|max:45'
        ]);

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            // Guardar nueva imagen
            $imagenPath = $request->file('imagen')->store('productos', 'public');
            $producto->imagen = $imagenPath;
        }

        $producto->update([
            'id_categoria' => $request->id_categoria,
            'nombre_producto' => $request->nombre_producto,
            'precio' => $request->precio,
            'cantidad' => $request->cantidad,
            'informacion' => $request->informacion
        ]);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy(Producto $producto)
    {
        // Eliminar imagen
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente');
    }

    public function actualizarInventario(Request $request, Producto $producto)
    {
        $request->validate([
            'tipo_movimiento' => 'required|in:entrada,salida',
            'cantidad' => 'required|integer|min:1',
            'numero_factura' => 'nullable|string',
            'proveedor' => 'nullable|string|max:50'
        ]);

        $cantidadActual = $producto->cantidad;
        $nuevaCantidad = $request->tipo_movimiento === 'entrada' 
            ? $cantidadActual + $request->cantidad 
            : $cantidadActual - $request->cantidad;

        if ($nuevaCantidad < 0) {
            return back()->with('error', 'No hay suficiente stock disponible');
        }

        // Actualizar inventario
        Inventario::create([
            'id_producto' => $producto->id,
            'tipo_movimiento' => $request->tipo_movimiento,
            'numero_factura' => $request->numero_factura,
            'proveedor' => $request->proveedor,
            'fecha_movimiento' => now()
        ]);

        $producto->update(['cantidad' => $nuevaCantidad]);

        return back()->with('success', 'Inventario actualizado exitosamente');
    }
}