<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_administrador')->constrained('administradores');
            $table->foreignId('id_producto')->constrained('productos');
            $table->string('tipo_movimiento', 50);
            $table->integer('numero_factura')->nullable();
            $table->string('proveedor', 50)->nullable();
            $table->date('fecha_movimiento');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventarios');
    }
};