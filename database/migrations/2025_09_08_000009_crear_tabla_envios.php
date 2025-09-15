<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('envios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pedido')->constrained('pedidos');
            $table->date('fecha_envio');
            $table->integer('estado_envio');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('envios');
    }
};