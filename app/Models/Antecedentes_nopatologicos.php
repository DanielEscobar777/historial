<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antecedentes_nopatologicos extends Model
{
     protected $fillable = [
        'id_historial',
        'vacunas',
        'vacunas_hpv',
        'habitos_toxicos',
        'alimentacion',
        'vivienda_servicio_basico',
        'habito_alcoholico',
        'habito_tabaquico',
        'exposicion_biomasa',
        'contacto_tuberculosis',
        'contacto_triatoma_infestans',
        'toxicomanias_drogas',
        'inmunizaciones',
        'antecedentes_sexuales',
        'id_usuario'
    ];
    protected $attributes = [
        'vacunas' => null,       // Cadena vacía
        'vacunas_hpv' => null,
        'habitos_toxicos' => null,
        'alimentacion' => null,
        'vivienda_servicio_basico' => null,
        'habito_alcoholico' => null,
        'habito_tabaquico' => null,
        'exposicion_biomasa' => null,
        'contacto_tuberculosis' => null,
        'contacto_triatoma_infestans' => null,
        'toxicomanias_drogas' => null,
        'inmunizaciones' => null,
        'antecedentes_sexuales' => null,
    ];
}
