<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PublicacionController;
use App\Http\Controllers\CategoriaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Rutas para Usuarios
Route::resource('usuarios', UsuarioController::class);

// Rutas para Productos
Route::resource('productos', ProductoController::class);
Route::post('productos/{producto}/inventario', [ProductoController::class, 'actualizarInventario'])
    ->name('productos.inventario.actualizar');

// Rutas para Categorías
Route::resource('categorias', CategoriaController::class);
Route::get('categorias/{categoria}/productos', [CategoriaController::class, 'getProductos'])
    ->name('categorias.productos');

// Rutas para Pedidos
Route::resource('pedidos', PedidoController::class);
Route::patch('pedidos/{pedido}/estado', [PedidoController::class, 'actualizarEstado'])
    ->name('pedidos.estado.actualizar');
Route::patch('pedidos/{pedido}/envio', [PedidoController::class, 'actualizarEnvio'])
    ->name('pedidos.envio.actualizar');
Route::patch('pedidos/{pedido}/pago', [PedidoController::class, 'actualizarPago'])
    ->name('pedidos.pago.actualizar');

// Rutas para Publicaciones
Route::resource('publicaciones', PublicacionController::class);
Route::post('publicaciones/{publicacion}/reaccion', [PublicacionController::class, 'agregarReaccion'])
    ->name('publicaciones.reaccion');
Route::post('publicaciones/{publicacion}/comentario', [PublicacionController::class, 'agregarComentario'])
    ->name('publicaciones.comentario');
