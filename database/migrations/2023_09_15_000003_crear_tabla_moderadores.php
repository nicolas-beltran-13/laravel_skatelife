<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('moderadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuarios')->onDelete('cascade');
            $table->string('registro_producto', 25);
            $table->string('coordinador', 100);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('moderadores');
    }
};