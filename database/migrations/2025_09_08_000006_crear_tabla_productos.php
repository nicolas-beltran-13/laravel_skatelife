<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_categoria')->constrained('categorias');
            $table->string('nombre_producto', 45);
            $table->decimal('precio', 8, 2);
            $table->string('imagen', 45);
            $table->integer('cantidad');
            $table->string('informacion', 45);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productos');
    }
};