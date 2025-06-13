<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sistema_sensitivo', function (Blueprint $table) {
            $table->id('id_sistema_sensitivo');
            $table->unsignedBigInteger('id_historial');
            $table->foreign('id_historial')->references('id_historia')->on('historials');
            $table->text('sensibilidad_superficial');
            $table->text('sensibilidad_profunda_consciente')->nullable();
            $table->text('sensibilidad_profunda_inconsciente')->nullable();
            $table->text('sistema_vestibulo_cerebeloso')->nullable();
            $table->text('signos_irritacion_meningea')->nullable();
            $table->text('marcha')->nullable();
            $table->integer('id_usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sistema_sensitivo');
    }
};
