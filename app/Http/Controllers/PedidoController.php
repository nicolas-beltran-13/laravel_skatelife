<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Envio;
use App\Models\Pago;
use App\Models\Producto;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with(['cliente', 'producto', 'envio', 'pago'])->get();
        return view('pedidos.index', compact('pedidos'));
    }

    public function create()
    {
        $productos = Producto::where('cantidad', '>', 0)->get();
        return view('pedidos.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_cliente' => 'required|exists:clientes,id',
            'id_producto' => 'required|exists:productos,id',
            'pago_pedido' => 'required|string|max:30',
            'estado_pedido' => 'required|string',
            'prioridad_envio' => 'required|string|max:30',
            'direccion_entrega' => 'required|string|max:50',
            'direccion_salida' => 'required|string|max:50'
        ]);

        // Verificar stock disponible
        $producto = Producto::findOrFail($request->id_producto);
        if ($producto->cantidad <= 0) {
            return back()->with('error', 'Producto sin stock disponible');
        }

        // Crear pedido
        $pedido = Pedido::create($request->all());

        // Actualizar stock
        $producto->decrement('cantidad', 1);

        // Crear envío asociado
        Envio::create([
            'id_pedido' => $pedido->id,
            'fecha_envio' => now(),
            'estado_envio' => 0 // Estado inicial
        ]);

        // Crear registro de pago
        Pago::create([
            'id_pedido' => $pedido->id,
            'fecha_pago' => now(),
            'estado_pago' => 'pendiente',
            'metodo_pago' => $request->pago_pedido
        ]);

        return redirect()->route('pedidos.index')
            ->with('success', 'Pedido creado exitosamente');
    }

    public function show(Pedido $pedido)
    {
        $pedido->load(['cliente', 'producto', 'envio', 'pago']);
        return view('pedidos.show', compact('pedido'));
    }

    public function actualizarEstado(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado_pedido' => 'required|string'
        ]);

        $pedido->update([
            'estado_pedido' => $request->estado_pedido
        ]);

        return back()->with('success', 'Estado del pedido actualizado');
    }

    public function actualizarEnvio(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado_envio' => 'required|integer'
        ]);

        $pedido->envio->update([
            'estado_envio' => $request->estado_envio
        ]);

        return back()->with('success', 'Estado del envío actualizado');
    }

    public function actualizarPago(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado_pago' => 'required|string',
            'metodo_pago' => 'required|string'
        ]);

        $pedido->pago->update([
            'estado_pago' => $request->estado_pago,
            'metodo_pago' => $request->metodo_pago,
            'fecha_pago' => $request->estado_pago === 'completado' ? now() : null
        ]);

        return back()->with('success', 'Información de pago actualizada');
    }
}