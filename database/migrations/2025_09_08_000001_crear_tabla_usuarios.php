<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('numide', 11);
            $table->string('nombre', 50);
            $table->string('apellidos', 50);
            $table->integer('edad');
            $table->string('direccion', 25);
            $table->string('telefono', 11);
            $table->string('correo', 100)->unique();
            $table->string('contrasena');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};