<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antecedentes_inmunizacion extends Model
{
     protected $fillable = [
        'id_historial',
        'ant_inmunizacions',
        'id_usuario'
    ];
    protected $attributes = [
        'ant_inmunizacions' => null,       // Cadena vacía
    ];
}
