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
        Schema::create('dermatologia', function (Blueprint $table) {
            $table->id('id_dermatologia');
            $table->unsignedBigInteger('id_historial');
            $table->foreign('id_historial')->references('id_historia')->on('historials');
            $table->text('piel');
            $table->text('pelo')->nullable();
            $table->text('uñas')->nullable();
            $table->text('mucosas')->nullable();
            $table->text('topografia')->nullable();
            $table->text('iconografia')->nullable();
            $table->text('morfologia')->nullable();
            $table->integer('id_usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dermatologia');
    }
};
