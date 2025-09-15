<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('publicaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cliente')->constrained('clientes');
            $table->dateTime('fecha_publicacion');
            $table->string('nombre_publicacion');
            $table->string('archivo', 100);
            $table->text('descripcion');
            $table->string('reacciones', 40);
            $table->text('comentarios');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('publicaciones');
    }
};