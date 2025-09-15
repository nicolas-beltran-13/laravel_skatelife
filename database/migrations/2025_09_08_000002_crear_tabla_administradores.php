<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('administradores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuarios')->onDelete('cascade');
            $table->string('registro_acciones', 25);
            $table->string('permisos_moderacion', 25);
            $table->string('normas', 100);
            $table->string('control', 25);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('administradores');
    }
};