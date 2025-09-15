<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cliente')->constrained('clientes');
            $table->foreignId('id_producto')->constrained('productos');
            $table->string('pago_pedido', 30);
            $table->text('estado_pedido');
            $table->string('prioridad_envio', 30);
            $table->string('direccion_entrega', 50);
            $table->string('direccion_salida', 50);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
};