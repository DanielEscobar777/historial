<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Examen_fisico_general extends Model
{
    protected $fillable = [
        'id_historial',
        'estado_conciencia',
        'color_piel_mucosa',
        'constitucion',
        'marcha',
        'posicion',
        'estado_hidratacion',
        'biotipo',
        'facies',
        'tension_arterial',
        'tension_arterial_media',
        'frecuencia_cardiaca',
        'frecuencia_respiratoria',
        'temperatura',
        'peso',
        'talla',
        'imc',
        'spo2',
        'fio2',
        'sc',
        'apgar',
        'silverman',
        'edad_capurro',
        'somatometria',
        'saturacion',
        'perimetro_cefalico',
        'perimetro_toracico',
        'perimetro_abdominal',
        'id_usuario'
    ];
    protected $attributes = [
        'estado_conciencia' => null,       // Cadena vacía
        'color_piel_mucosa' => null,
        'constitucion' => null,
        'marcha' => null,
        'posicion' => null,
        'estado_hidratacion' => null,
        'biotipo' => null,
        'facies' => null,
        'tension_arterial' => null,
        'tension_arterial_media' => null,
        'frecuencia_cardiaca' => null,
        'frecuencia_respiratoria' => null,
        'temperatura' => null,
        'peso' => null,
        'talla' => null,
        'imc' => null,
        'spo2' => null,
        'fio2' => null,
        'sc' => null,
        'apgar' => null,
        'silverman' => null,
        'edad_capurro' => null,
        'somatometria' => null,
        'saturacion' => null,
        'perimetro_cefalico' => null,
        'perimetro_toracico' => null,
        'perimetro_abdominal' => null,

    ];


     public static function examen_general($id_historial) // Nota el cambio de nombre a camelCase
    {
        return self::where('id_historial', $id_historial)
            ->first();
    }
}
