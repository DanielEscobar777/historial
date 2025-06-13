<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evolucion extends Model
{
    /** @use HasFactory<\Database\Factories\EvolucionFactory> */
    use HasFactory;
    protected $fillable = [
        'diagnostico',
        'id_evolucion',
        'descripcion',
        's',
        'o',
        'a',
        'p',
        'pa',
        'fc',
        'fr',
        'sat',
        'sat_2',
        'FiO2',
        'peso',
        'diuresis',
        'dh'
    ];

    protected $primaryKey = 'id_evolucion';
    public static function traerEvolucion($id_historial)
    {
        return self::where('evolucions.id_historial', $id_historial)
            ->get();
    }
}
