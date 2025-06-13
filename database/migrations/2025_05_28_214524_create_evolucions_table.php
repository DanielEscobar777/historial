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
        Schema::create('evolucions', function (Blueprint $table) {
            $table->id('id_evolucion');
            $table->text('descripcion');
            $table->text('s');
            $table->text('o');
            $table->text('a');
            $table->text('p');
            $table->decimal('pa');
            $table->decimal('fc');
            $table->decimal('fr');
            $table->decimal('sat');
            $table->decimal('sat_2');
            $table->decimal('peso');
            $table->decimal('diuresis');
            $table->decimal('dh');
            $table->date('fecha_registro');
            $table->time('hora_registro');
            $table->integer('id_usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evolucions');
    }
};
