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
        Schema::create('historia_enfermedad_actual', function (Blueprint $table) {
            $table->id('id_historia_enfermedad');
             $table->unsignedBigInteger('id_historial');
            $table->foreign('id_historial')->references('id_historia')->on('historials');
            $table->text('historia_de_enfermedad_actual');
            $table->integer('id_usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historia_enfermedad_actual');
    }
};
