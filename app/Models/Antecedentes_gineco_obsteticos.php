<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antecedentes_gineco_obsteticos extends Model
{
    protected $fillable = [
        'id_historial',
        'menarca',
        'ritmo_menstrual',
        'menopausia',
        'gestaciones',
        'partos',
        'cesareas',
        'abortos',
        'hijos_macrosomicos',
        'preeclampsia_eclampsia',
        'metodos_anticonceptivos',
        'pap',
        'fuab',
        'fecha_ultima_menstruacion',
        'fecha_ultima_mamografia',
        'fecha_ultima_densitometria',
        'id_usuario'
    ];
    protected $attributes = [
        'menarca' => null,       // Cadena vacía
        'ritmo_menstrual' => null,
        'menopausia' => null,
        'gestaciones' => null,
        'partos' => null,
        'cesareas' => null,
        'abortos' => null,
        'hijos_macrosomicos' => null,
        'preeclampsia_eclampsia' => null,
        'metodos_anticonceptivos' => null,
        'pap' => null,
        'fuab' => null,
        'fecha_ultima_menstruacion' => null,
        'fecha_ultima_mamografia' => null,
        'fecha_ultima_densitometria' => null,
    ];
}
