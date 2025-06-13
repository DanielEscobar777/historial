<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desarrollo_psicomotor extends Model
{
     protected $fillable = [
        'id_historial',
        'desarrollo_psicomotor',
        'id_usuario'
    ];
    protected $attributes = [
        'desarrollo_psicomotor' => null,       // Cadena vacía
    ];
}
