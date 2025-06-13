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
        Schema::create('antecedentes_familiares', function (Blueprint $table) {
            $table->id('id_antecedentes_familiares');
            $table->unsignedBigInteger('id_historial');
            $table->foreign('id_historial')->references('id_historia')->on('historials');
            $table->text('antecedentes_familiares');
            $table->integer('id_usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antecedentes_familiares');
    }
};
