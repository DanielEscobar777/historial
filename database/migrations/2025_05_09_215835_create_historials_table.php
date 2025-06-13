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
      
        Schema::create('historials', function (Blueprint $table) {
            $table->id('id_historia');
            //$table->foreignId('id_servicio')->constrained('servicios')->onDelete('cascade');
           // $table->foreignId('id_paciente')->constrained('pacientes')->onDelete('cascade');
           $table->string('id_servicio', 100); 
           $table->string('id_paciente', 100);
            $table->string('grado_instruccion', 100);
            $table->string('religion', 50);
            $table->date('fecha_registro');
            $table->time('hora_registro');
            $table->string('cama', 50);
            $table->string('fuente_informacion', 200);
            $table->string('nombrenum_referencia', 200);
            $table->string('grado_confiabilidad', 100)->nullable();
            $table->string('grupo_sanguineo_facto', 50);
            $table->string('nombre_recien_necido', 50)->nullable();
            $table->date('fecha_recien_necido')->nullable();
            $table->time('hora_recien_necido')->nullable();
          
            $table->integer('id_usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
   public function down()
{
    // Primero eliminar las claves foráneas que apuntan a esta tabla
    Schema::table('nombre_tabla_dependiente_1', function (Blueprint $table) {
        $table->dropForeign(['historial_id']);
    });
    
    Schema::table('nombre_tabla_dependiente_2', function (Blueprint $table) {
        $table->dropForeign(['historial_id']);
    });
    
    // Luego eliminar la tabla
    Schema::dropIfExists('historials');
}
};
