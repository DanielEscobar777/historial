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
        Schema::create('examen_ginecologico', function (Blueprint $table) {
            $table->id('id_examen_ginecologico');
            $table->unsignedBigInteger('id_historial');
            $table->foreign('id_historial')->references('id_historia')->on('historials');
            $table->text('vello_pubiano')->nullable();
            $table->text('vulva')->nullable();
            $table->text('uretra')->nullable();
            $table->text('glandulas_ByS')->nullable();
            $table->text('clitoris')->nullable();
            $table->text('perineo')->nullable();
            $table->text('vagina')->nullable();
            $table->text('cuello_uterino')->nullable();
            $table->text('cuerpo_uterino')->nullable();
            $table->text('especuloscopia')->nullable();
            $table->text('anexos')->nullable();
            $table->text('tacto_rectal')->nullable();
            $table->integer('id_usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examen_ginecologico');
    }
};
