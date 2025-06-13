<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antecedentes_patologicos extends Model
{
     protected $fillable = [
        'id_historial',
        'clinicos',
        'quirurjicos',
        'alergicos',
        'otros',
        'internaciones',
        'cirujias',
        'transfusio_sangre',
        'iras',
        'gastroenteritis',
        'habito_intestinal',
        'habito_miccional',
        'traumatismos',
        'medicamentos',
        'enfermedades',
        'id_usuario'
    ];
    protected $attributes = [
        'clinicos' => null,       // Cadena vacía
        'quirurjicos' => null,
        'alergicos' => null,
        'otros' => null,
        'internaciones' => null,
        'cirujias' => null,
        'transfusio_sangre' => null,
        'iras' => null,
        'gastroenteritis' => null,
        'habito_intestinal' => null,
        'habito_miccional' => null,
        'traumatismos' => null,
        'medicamentos' => null,
        'enfermedades' => null,
    ];
}
