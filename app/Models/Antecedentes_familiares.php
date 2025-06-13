<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antecedentes_familiares extends Model
{
     protected $fillable = [
        'id_historial',
        'ant_familiares',
    ];
    protected $attributes = [
        'ant_familiares' => null,
    ];
}
