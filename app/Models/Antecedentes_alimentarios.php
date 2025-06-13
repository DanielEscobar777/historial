<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antecedentes_alimentarios extends Model
{
      protected $fillable = [
        'id_historial',
        'ant_alimentarios'
    ];
      protected $attributes = [
        'ant_alimentarios' => null,       // Cadena vacía

    ];
}
