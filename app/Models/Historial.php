<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Historial extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_servicio',
        'id_paciente',
        'grado_instruccion',
        'religion',
        'fecha_registro',
        'hora_registro',
        'cama',
        'fuente_informacion',
        'nombrenum_referencia',
        'grado_confiabilidad',
        'grupo_sanguineo_facto',
        'nombre_recien_nacido',
        'fecha_recien_nacido',
        'hora_recien_nacido',
        'motivo_internacion',
        'historia_actual',
        'diagnostico',
        'diagnostico_sindromatico',
        'comentario',
        'interpretacion_lab_gab',
        'examenes_complementarios'
    ];

    public static function traerantecedentes($id_historial) // Nota el cambio de nombre a camelCase
    {
        return self::join('anamnesis_sistemas as ass', 'historials.id_historia', '=', 'ass.id_historial')
            ->join('antecedentes_alimentarios as al', 'historials.id_historia', '=', 'al.id_historial')
            ->join('antecedentes_familiares as af', 'historials.id_historia', '=', 'af.id_historial')
            ->join('antecedentes_gineco_obstetricos as ao', 'historials.id_historia', '=', 'ao.id_historial')
            ->join('antecedentes_heredofamiliares as ah', 'historials.id_historia', '=', 'ah.id_historial')
            ->join('antecedentes_inmunizacions as ai', 'historials.id_historia', '=', 'ai.id_historial')
            ->join('antecedentes_no_patologicos as anp', 'historials.id_historia', '=', 'anp.id_historial')
            ->join('antecedentes_patologicos as ap', 'historials.id_historia', '=', 'ap.id_historial')
            ->join('antecedentes_perinatologicos as apr', 'historials.id_historia', '=', 'apr.id_historial')
            ->join('desarrollo_psicomotors as dp', 'historials.id_historia', '=', 'dp.id_historial')
            ->join('servicios as s', 'historials.id_servicio', '=', 's.id_servicio')
            ->where('historials.id_historia', $id_historial)
            ->first();
    }
    public static function traerExamenGeneral($id_historial)
    {
        return self::join('examen_fisico_generals as efg', 'historials.id_historia', '=', 'efg.id_historial')
            ->join('motivo_de_internacion as mi', 'historials.id_historia', '=', 'mi.id_historial')
            ->join('historia_enfermedad_actual as hea', 'historials.id_historia', '=', 'hea.id_historial')
            ->where('historials.id_historia', $id_historial)
            ->first();
    }

    public static function traerexamensegmentado($id_historial) // Nota el cambio de nombre a camelCase
    {
        return self::join('examen_fisico_segmentado as efs', 'historials.id_historia', '=', 'efs.id_historial')
            ->where('historials.id_historia', $id_historial)
            ->first();
    }
    public static function traerexamensegmentado_mi($id_historial) // Nota el cambio de nombre a camelCase
    {
        return self::join('examen_cardiovascular as ec', 'historials.id_historia', '=', 'ec.id_historial')
            ->join('examen_extremidades_superiores as ees', 'historials.id_historia', '=', 'ees.id_historial')
            ->join('examen_extremidades_inferiores as eei', 'historials.id_historia', '=', 'eei.id_historial')
            ->join('examen_genito_urinario as eg', 'historials.id_historia', '=', 'eg.id_historial')
            ->join('examen_ginecologico as egi', 'historials.id_historia', '=', 'egi.id_historial')
            ->join('examen_obstetrico as eob', 'historials.id_historia', '=', 'eob.id_historial')
            ->join('dermatologia as d', 'historials.id_historia', '=', 'd.id_historial')
            ->join('ganglios_linfaticos as gl', 'historials.id_historia', '=', 'gl.id_historial')
            ->where('historials.id_historia', $id_historial)
            ->first();
    }
    public static function traerexamensistemanervioso($id_historial) // Nota el cambio de nombre a camelCase
    {
        return self::join('sistema_motor as sm', 'historials.id_historia', '=', 'sm.id_historial')
            ->join('sistema_nervioso as sn', 'historials.id_historia', '=', 'sn.id_historial')
            ->join('sistema_sensitivo as ss', 'historials.id_historia', '=', 'ss.id_historial')
            ->where('historials.id_historia', $id_historial)
            ->first();
    }

    public static function traerdiagnosticos($id_historial)
    {
        return self::join('diagnostico_sindromatico as ds', 'historials.id_historia', '=', 'ds.id_historial')
            ->join('examenes_complementarios as ec', 'historials.id_historia', '=', 'ec.id_historial')
            ->join('impresion_diagnostica as id', 'historials.id_historia', '=', 'id.id_historial')
            ->join('interpretacion_laboratorios_de_estudio_y_gabinetes as ilg', 'historials.id_historia', '=', 'ilg.id_historial')
            ->join('comentarios as c', 'historials.id_historia', '=', 'c.id_historial')
            ->where('historials.id_historia', $id_historial)
            ->first();
    }

    protected static function booted() {}
}
