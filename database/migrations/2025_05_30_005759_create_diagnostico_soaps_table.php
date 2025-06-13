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
        Schema::create('diagnostico_soaps', function (Blueprint $table) {
            $table->unsignedBigInteger('id_diagnostico_soaps');
            $table->foreign('id_evolucion')->references('id_evolucion')->on('evolucions');
            $table->text('diagnostico')->nullable();
            $table->integer('id_usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostico_soaps');
    }
};
