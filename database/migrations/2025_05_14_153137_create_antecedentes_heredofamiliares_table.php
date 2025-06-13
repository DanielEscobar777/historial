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



        Schema::create('antecedentes_heredofamiliares', function (Blueprint $table) {
            $table->id('id_antecedentes_heredofamiliares');
            $table->unsignedBigInteger('id_historial');
            $table->foreign('id_historial')->references('id_historia')->on('historials');
            $table->string('padre', 200);
            $table->string('madre', 200);
            $table->string('hermanos', 200);
            $table->string('esposo', 200)->nullable();
            $table->string('hijos', 200)->nullable();
            $table->string('abuelos', 200)->nullable();
            $table->integer('id_usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antecedentes_heredofamiliares');
    }
};
