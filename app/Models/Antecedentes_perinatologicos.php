<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antecedentes_perinatologicos extends Model
{
     protected $fillable = [
        'id_historial',
        'ant_perinatologicos',
        'id_usuario'
    ];
    protected $attributes = [
        'ant_perinatologicos' => null,       // Cadena vacía
    ];
}
