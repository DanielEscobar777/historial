<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Detalle_servicio_permiso;
use Illuminate\Support\Facades\Schema;

use App\Models\Historial;
use Illuminate\Http\Request;
use App\Http\Requests\StoreHistorialRequest;
use App\Http\Requests\UpdateHistorialRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\HistorialSection;
use App\Models\EspecialidadSection;
use App\Models\Servicios;
use App\Models\Permisos_historia;
use Illuminate\Support\Facades\DB;
use Doctrine\DBAL\Schema\Column;
use Illuminate\Support\Facades\Log;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;
use ReflectionClass;
use Illuminate\Support\Str;

class HistorialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = auth()->id();
        $servicios = DB::table('servicios as s')
            ->join('servicio_user as su', 's.id_servicio', '=', 'su.servicio_id')
            ->where('su.user_id', $userId)
            ->get();
        return view('historial.index', compact('servicios'));
    }

    public function show($id_servicio)
    {
        $historiales = Historial::where('id_servicio', $id_servicio)->get();
        return view('historial.index_servicio', compact('historiales', 'id_servicio'));
    }

    public function formulario($id_servicio)
    {
        $n_ser = Servicios::where('id_servicio', $id_servicio)->first();
        $permisos = DB::table('users as u')
            ->join('servicio_user as su', 'su.user_id', '=', 'u.id')
            ->join('servicios as s', 's.id_servicio', '=', 'su.servicio_id')
            ->join('detalle_servicio_permisos as dsp', 'dsp.id_servicio', '=', 's.id_servicio')
            ->join('permisos_historias as ph', 'ph.id_permisos_historia', '=', 'dsp.id_permisos_historia')
            ->where('s.id_servicio', $id_servicio)
            ->select('ph.id_permisos_historia', 'ph.nombre_permiso', 'ph.nivel', 'ph.modulo', 's.nombre_servicio')
            ->orderBy('ph.nivel')
            ->orderBy('ph.modulo')
            ->get();

        $campos_temporales = [];
        $servicio_nombre = null;
        $mapa_modulos = [

            'ap' => 'antecedentes_perinatologicos',
            'ai' => 'antecedentes_inmunizacions',
            'aai' => 'antecedentes_alimentarios',
            'afi' => 'antecedentes_familiares',
            'dp' => 'desarrollo_psicomotors',
            'ah' => 'antecedentes_heredofamiliares',
            'app' => 'antecedentes_patologicos',
            'apnp' => 'antecedentes_no_patologicos',
            'ag' => 'antecedentes_gineco_obstetricos',

            'mi' => 'motivo_de_internacion',
            'hea' => 'historia_enfermedad_actual',
            'as' => 'anamnesis_sistemas',

            'efg' => 'examen_fisico_generals',
            'efs' => 'examen_fisico_segmentado',
            'eo' => 'examen_obstetrico',
            'eg' => 'examen_ginecologico',
            'efsca' => 'examen_cardiovascular',
            'efsg' => 'examen_genito_urinario',
            'efses' => 'examen_extremidades_superiores',
            'efsei' => 'examen_extremidades_inferiores',
            'd' => 'dermatologia',
            'gl' => 'ganglios_linfaticos',
            'sn' => 'sistema_nervioso',
            'sm' => 'sistema_motor',
            'ss' => 'sistema_sensitivo',

            'ilg' => 'interpretacion_laboratorios_de_estudio_y_gabinetes',
            'ec' => 'examenes_complementarios',
            'id' => 'impresion_diagnostica',
            'ds' => 'diagnostico_sindromatico',
            'dsn' => 'diagnostico_sindromatico',

            'c' => 'comentarios'
        ];

        foreach ($permisos as $permiso) {
            $servicio_nombre = $permiso->nombre_servicio;

            if ($permiso->nivel == 1) {
                $clave = strtolower($permiso->nombre_permiso);

                logger()->info('Modulo recibido:', [
                    'original' => $permiso->nombre_permiso,
                    'clave' => $clave
                ]);

                $campos_temporales[$clave] = [
                    'slug' => $clave,
                    'nombre' => $permiso->nombre_permiso,
                    'subcampos' => []
                ];
            }
        }
        foreach ($permisos as $permiso) {
            if ($permiso->nivel == 2) {
                $grupo_clave_corto = strtolower($permiso->modulo);
                if (isset($mapa_modulos[$grupo_clave_corto])) {
                    $grupo_clave = $mapa_modulos[$grupo_clave_corto];
                } else {
                    $grupo_clave = $grupo_clave_corto; // por si acaso no está en el mapa
                }
                logger()->info('Procesando subcampo', [
                    'nombre_permiso' => $permiso->nombre_permiso,
                    'modulo' => $permiso->modulo,
                    'grupo_clave' => $grupo_clave,
                    'existe_grupo' => isset($campos_temporales[$grupo_clave])
                ]);
                if (isset($campos_temporales[$grupo_clave])) {
                    $subcampo_nombre = strtolower(str_replace(' ', '_', $permiso->nombre_permiso));

                    logger()->info('Agregando subcampo', [
                        'a_grupo' => $grupo_clave,
                        'etiqueta' => $permiso->nombre_permiso,
                        'nombre' => $subcampo_nombre
                    ]);

                    $campos_temporales[$grupo_clave]['subcampos'][] = [
                        'etiqueta' => $permiso->nombre_permiso,
                        'nombre' => $subcampo_nombre
                    ];
                } else {
                    logger()->warning('No se encontró grupo para subcampo', [
                        'grupo_clave' => $grupo_clave,
                        'nombre_permiso' => $permiso->nombre_permiso
                    ]);
                }
            }
        }
        $campos_organizados = [];
        foreach ($mapa_modulos as $clave_corta => $clave_larga) {
            if (isset($campos_temporales[$clave_larga])) {
                $campos_organizados[$clave_larga] = $campos_temporales[$clave_larga];
            }
        }

        $faltantes = array_diff(array_values($mapa_modulos), array_keys($campos_temporales));

        return view('historial.formulario', [
            'campos_organizados' => $campos_organizados,
            'mostrarCamposRN' => strtolower($servicio_nombre) === 'NEONATOLOGIA',
            'modulos_faltantes' => $faltantes,
            'n_ser' => $n_ser
        ]);
    }

    public function editSecciones($id_servicio)
    {
        $secciones = HistorialSection::all();
        $seleccionadas = EspecialidadSection::where('id_servicio', $id_servicio)->pluck('id_section')->toArray();

        $servicio = Servicios::findOrFail($id_servicio); // <-- aquí traes el nombre

        return view('historial.secciones', compact('secciones', 'seleccionadas', 'id_servicio', 'servicio'));
    }
    public function updateSecciones(Request $request, $id_servicio)
    {
        // Eliminar los accesos actuales
        EspecialidadSection::where('id_servicio', $id_servicio)->delete();

        // Insertar nuevas relaciones si hay seleccionadas
        if ($request->has('secciones')) {
            foreach ($request->secciones as $id_section) {
                EspecialidadSection::create([
                    'id_servicio' => $id_servicio,
                    'id_section' => $id_section,
                ]);
            }
        }

        return redirect()->route('servicios.index')->with('success', 'Secciones actualizadas correctamente.');
    }
    /*
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $usuario = Auth::user();
            $email = $usuario->email;

            $servicio = DB::table('users as u')
                ->join('servicio_user as su', 'su.user_id', '=', 'u.id')
                ->join('servicios as s', 's.id_servicio', '=', 'su.servicio_id')
                ->where('u.email', $email)
                ->select('s.id_servicio', 's.nombre_servicio')
                ->first();

            if (!$servicio) {
                throw new \Exception('Servicio no asignado al usuario.');
            }
            $validated = $request->validate([
                'id_paciente' => 'required|integer',
                'grado_instruccion' => 'required|string|max:255',
                'religion' => 'required|string|max:255',
                'cama' => 'required|string|max:50',
                'fuente_informacion' => 'required|string|max:255',
                'nombrenum_referencia' => 'required|string|max:255',
                'grado_confiabilidad' => 'required|string|max:255',
                'grupo_sanguineo_facto' => 'required|string|max:100',
                'nombre_recien_necido' => 'nullable|string|max:255',
                'fecha_recien_necido' => 'nullable|date',
                'hora_recien_necido' => 'nullable|date_format:H:i',
                'historia_actual' => 'required|string',
                'motivo_internacion' => 'required|string',
                'diagnostico_sindromatico' => 'required|string',
                'diagnostico' => 'required|string',
                'comentario' => 'required|string',
                'examenes_complementarios' => 'required|string',
                'interpretacion_lab_gab' => 'required|string',
                'campos_dinamicos' => 'required|array',
            ], [
                'id_paciente.required' => 'El campo paciente es obligatorio.',
                'grado_instruccion.max' => 'El grado de instrucción no puede superar los 255 caracteres.',
                'religion.required' => 'El campo religion es obligatorio.',
                'cama.required' => 'El campo cama es obligatorio.',
                'fuente_informacion.required' => 'El campo fuente_informacion es obligatorio.',
                'nombrenum_referencia.required' => 'El campo nombre y numero de referencia es obligatorio.',
                'grado_confiabilidad.required' => 'El campo grado_confiabilidad es obligatorio.',
                'grupo_sanguineo_facto.required' => 'El campo grupo_sanguineo_facto es obligatorio.',
                'historia_actual.required' => 'El campo historia_actual es obligatorio.',
                'motivo_internacion.required' => 'El campo motivo_internacion es obligatorio.',
                'diagnostico_sindromatico.required' => 'El campo diagnostico_sindromatico es obligatorio.',
                'diagnostico.required' => 'El campo diagnostico es obligatorio.',
                'campos_dinamicos.required' => 'Los campos dinámicos son obligatorios.',
            ]);

            $esObstetrico = strtolower($servicio->nombre_servicio) === 'obstetrico';

            // Insertar en historial
            $historialId = DB::table('historials')->insertGetId([
                'id_servicio' => $servicio->id_servicio,
                'id_paciente' => $request->id_paciente,
                'grado_instruccion' => $request->grado_instruccion,
                'religion' => $request->religion,
                'fecha_registro' => now()->toDateString(),
                'hora_registro' => now()->toTimeString(),
                'cama' => $request->cama,
                'fuente_informacion' => $request->fuente_informacion,
                'nombrenum_referencia' => $request->nombrenum_referencia,
                'grado_confiabilidad' => $request->grado_confiabilidad,
                'grupo_sanguineo_facto' => $request->grupo_sanguineo_facto,
                'nombre_recien_necido' => $esObstetrico ? $request->nombre_recien_necido : null,
                'fecha_recien_necido' => $esObstetrico ? $request->fecha_recien_necido : null,
                'hora_recien_necido' => $esObstetrico ? $request->hora_recien_necido : null,
                'historia_actual' => $request->historia_actual,
                'motivo_internacion' => $request->motivo_internacion,
                'diagnostico_sindromatico' => $request->diagnostico_sindromatico,
                'diagnostico' => $request->diagnostico,
                'comentario' => $request->comentario,
                'examenes_complementarios' => $request->examenes_complementarios,
                'interpretacion_lab_gab' => $request->interpretacion_lab_gab,
                'id_usuario' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Obtener permisos
            $permisos = DB::table('permisos_historias as ph')
                ->select('ph.nombre_permiso', 'ph.nivel', 'ph.modulo')
                ->get();

            $modulos = [];

            foreach ($permisos as $permiso) {
                if ($permiso->nivel == 1) {
                    $modulos[$permiso->nombre_permiso] = [];
                }
            }

            foreach ($permisos as $permiso) {
                if ($permiso->nivel == 2 && isset($modulos[$permiso->modulo])) {
                    $modulos[$permiso->modulo][] = $permiso->nombre_permiso;
                }
            }

            // Configuración DBAL
            $config = new \Doctrine\DBAL\Configuration();
            $connectionParams = [
                'dbname' => env('DB_DATABASE'),
                'user' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'host' => env('DB_HOST'),
                'driver' => 'pdo_mysql',
                'port' => env('DB_PORT', 3306),
                'charset' => 'utf8mb4',
            ];
            $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
            $schemaManager = $conn->createSchemaManager();

            // Datos del formulario

            $datosFormulario = $request->input('campos_dinamicos', []);

            $validator = Validator::make($request->all(), [
                'campos_dinamicos' => 'required|array',
            ]);

            foreach ($datosFormulario as $campo => $valor) {
                if (is_null($valor) || (is_string($valor) && trim($valor) === '')) {
                    $validator->errors()->add("campos_dinamicos.{$campo}", "El campo '{$campo}' no puede estar vacío.");
                }
            }

            if ($validator->errors()->any()) {
                return back()->withErrors($validator)->withInput();
            }


            Log::info('Campos dinámicos recibidos:', $datosFormulario);
            Log::info('Nombres de campos dinámicos recibidos:', array_keys($datosFormulario));

            foreach ($modulos as $tabla => $camposPermitidos) {
                if (!Schema::hasTable($tabla)) continue;

                Log::info("Procesando tabla: $tabla");
                Log::info("Campos permitidos para esta tabla:", $camposPermitidos);

                $data = [
                    'id_historial' => $historialId,
                    'id_usuario' => $usuario->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                try {
                    $columns = $schemaManager->listTableColumns($tabla);

                    foreach ($columns as $columna => $colInfo) {
                        if ($colInfo->getAutoincrement()) continue;
                        if (in_array($columna, ['id_historial', 'id_usuario', 'created_at', 'updated_at'])) continue;

                        // Usa directamente los datos del formulario sin usar camposPermitidos
                        if (array_key_exists($columna, $datosFormulario)) {
                            $valor = $datosFormulario[$columna];
                            $data[$columna] = $valor !== '' ? $valor : 'N/A';
                            Log::info("✔ Campo insertado: $columna => $valor");
                        } else {
                            $tipo = strtolower((new \ReflectionClass($colInfo->getType()))->getShortName());
                            $data[$columna] = in_array($tipo, ['string', 'text', 'varchar']) ? 'N/A' : 'N/A';
                            Log::warning("⚠ Campo '$columna' no fue recibido. Se asigna valor por defecto.");
                        }
                    }


                    DB::table($tabla)->insert($data);
                } catch (\Throwable $e) {
                    Log::error("❌ Error al insertar en tabla $tabla: " . $e->getMessage());
                    continue;
                }
            }

            DB::commit();
            return redirect()->route('historial.index')->with('success', 'Historia registrada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al guardar: ' . $e->getMessage()])->withInput();
        }
    }

*/
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $usuario = Auth::user();
            $email = $usuario->email;

            $servicio = DB::table('users as u')
                ->join('servicio_user as su', 'su.user_id', '=', 'u.id')
                ->join('servicios as s', 's.id_servicio', '=', 'su.servicio_id')
                ->where('u.email', $email)
                ->select('s.id_servicio', 's.nombre_servicio')
                ->first();

            if (!$servicio) {
                throw new \Exception('Servicio no asignado al usuario.');
            }

            $esObstetrico = $request->nombre_servicio;
            // Validación básica de los campos fijos (historial)
            if ($esObstetrico == 'NEONATOLOGIA') {
                $validated = $request->validate([
                    'cama' => 'nullable|string|max:50',
                    'nombrenum_referencia' => 'nullable|string|max:255',
                    'nombre_recien_necido' => 'required|string|max:255',
                    'fecha_recien_necido' => 'required|date',
                    'hora_recien_necido' => 'required|date_format:H:i',
                    'sexo' => 'required',
                    'campos_dinamicos' => 'required|array',
                    'campos_dinamicos.*' => 'required|string|max:255',
                ], [
                    'cama.required' => 'El campo cama es obligatorio.',
                    'nombrenum_referencia.required' => 'El campo nombre y numero de referencia es obligatorio.',
                    'nombre_recien_necido.required' => 'El campo nombre de recien nacido es obligatorio.',
                    'fecha_recien_necido.required' => 'El campo fecha de nacimiento es obligatorio.',
                    'hora_recien_necido.required' => 'El campo hora de nacimiento es obligatorio.',
                    'sexo.requied' => 'El campo sexo es obligatorio',
                    'campos_dinamicos.required' => 'Debe agregar al menos un campo dinámico',
                    'campos_dinamicos.*.required' => 'El valor del campo es obligatorio',
                ]);
            } else {
                $validated = $request->validate([
                    'id_paciente' => 'required|integer',
                    'grado_instruccion' => 'required|string|max:255',
                    'religion' => 'required|string|max:255',
                    'cama' => 'required|string|max:50',
                    'fuente_informacion' => 'required|string|max:255',
                    'nombrenum_referencia' => 'required|string|max:255',
                    'grado_confiabilidad' => 'required|string|max:255',
                    'grupo_sanguineo_facto' => 'required|string|max:100',
                    'nombre_recien_necido' => 'nullable|string|max:255',
                    'fecha_recien_necido' => 'nullable|date',
                    'hora_recien_necido' => 'nullable|date_format:H:i',
                    'campos_dinamicos' => 'required|array',
                    'campos_dinamicos.*' => 'required|string|max:255',
                ], [
                    'id_paciente.required' => 'El campo paciente es obligatorio.',
                    'grado_instruccion.max' => 'El grado de instrucción no puede superar los 255 caracteres.',
                    'religion.required' => 'El campo religion es obligatorio.',
                    'cama.required' => 'El campo cama es obligatorio.',
                    'fuente_informacion.required' => 'El campo fuente_informacion es obligatorio.',
                    'nombrenum_referencia.required' => 'El campo nombre y numero de referencia es obligatorio.',
                    'grado_confiabilidad.required' => 'El campo grado_confiabilidad es obligatorio.',
                    'grupo_sanguineo_facto.required' => 'El campo grupo_sanguineo_facto es obligatorio.',
                    'campos_dinamicos.required' => 'Debe agregar al menos un campo dinámico',
                    'campos_dinamicos.*.required' => 'El valor del campo es obligatorio',
                ]);
            }
            $id_servicio = $request->id_servicio;
            // Validar campos dinámicos para que no estén vacíos
            $datosFormulario = $request->input('campos_dinamicos', []);
            $erroresCamposDinamicos = [];
            foreach ($datosFormulario as $campo => $valor) {
                if (is_null($valor) || (is_string($valor) && trim($valor) === '')) {
                    $erroresCamposDinamicos["campos_dinamicos.{$campo}"] = "El campo '{$campo}' no puede estar vacío.";
                }
            }
            if (!empty($erroresCamposDinamicos)) {
                return back()->withErrors($erroresCamposDinamicos)->withInput();
            }

            // Insertar en historial
            $historialId = DB::table('historials')->insertGetId([
                'id_servicio' => $request->id_servicio,
                'id_paciente' => $request->id_paciente,
                'grado_instruccion' => $request->grado_instruccion,
                'religion' => $request->religion,
                'fecha_registro' => now()->toDateString(),
                'hora_registro' => now()->toTimeString(),
                'cama' => $request->cama,
                'fuente_informacion' => $request->fuente_informacion,
                'nombrenum_referencia' => $request->nombrenum_referencia,
                'grado_confiabilidad' => $request->grado_confiabilidad,
                'grupo_sanguineo_facto' => $request->grupo_sanguineo_facto,
                'nombre_recien_necido' => $esObstetrico ? $request->nombre_recien_necido : null,
                'fecha_recien_necido' => $esObstetrico ? $request->fecha_recien_necido : null,
                'hora_recien_necido' => $esObstetrico ? $request->hora_recien_necido : null,
                'sexo' => $esObstetrico ? $request->sexo : null,
                'id_usuario' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Obtener permisos
            $permisos = DB::table('permisos_historias as ph')
                ->select('ph.nombre_permiso', 'ph.nivel', 'ph.modulo')
                ->get();

            $modulos = [];

            foreach ($permisos as $permiso) {
                if ($permiso->nivel == 1) {
                    $modulos[$permiso->nombre_permiso] = [];
                }
            }

            foreach ($permisos as $permiso) {
                if ($permiso->nivel == 2 && isset($modulos[$permiso->modulo])) {
                    $modulos[$permiso->modulo][] = $permiso->nombre_permiso;
                }
            }

            // Configuración DBAL
            $config = new \Doctrine\DBAL\Configuration();
            $connectionParams = [
                'dbname' => env('DB_DATABASE'),
                'user' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'host' => env('DB_HOST'),
                'driver' => 'pdo_mysql',
                'port' => env('DB_PORT', 3306),
                'charset' => 'utf8mb4',
            ];
            $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
            $schemaManager = $conn->createSchemaManager();

            foreach ($modulos as $tabla => $camposPermitidos) {
                if (!\Schema::hasTable($tabla)) continue;

                $data = [
                    'id_historial' => $historialId,
                    'id_usuario' => $usuario->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                try {
                    $columns = $schemaManager->listTableColumns($tabla);

                    foreach ($columns as $columna => $colInfo) {
                        if ($colInfo->getAutoincrement()) continue;
                        if (in_array($columna, ['id_historial', 'id_usuario', 'created_at', 'updated_at'])) continue;

                        if (array_key_exists($columna, $datosFormulario)) {
                            $valor = $datosFormulario[$columna];
                            $data[$columna] = $valor !== '' ? $valor : 'N/A';
                        } else {
                            $tipo = strtolower((new \ReflectionClass($colInfo->getType()))->getShortName());
                            $data[$columna] = in_array($tipo, ['string', 'text', 'varchar']) ? 'N/A' : 'N/A';
                        }
                    }

                    DB::table($tabla)->insert($data);
                } catch (\Throwable $e) {
                    \Log::error("Error al insertar en tabla $tabla: " . $e->getMessage())->withInput();
                    continue;
                }
            }

            DB::commit();
            $historiales = Historial::where('id_servicio', $id_servicio)->get();
            return view('historial.index_servicio', compact('historiales', 'id_servicio'))->with('success', 'Historia registrada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al guardar: ' . $e->getMessage()])->withInput();
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    /* public function store(Request $request)
    {
        $reglas = [
            'id_paciente' => 'required|int|max:50',
            'religion' => 'required|string|max:100', // Reemplaza 'tu_tabla'
            'cama' => 'required|string|max:50',
            'fuente_informacion' => 'required|string|max:200',
            'grado_confiabilidad' => 'required|string|max:255',
            'telefono' => 'required|string|max:200',
            'motivos_internacion' => 'required|string|max:500',
            'historia_actual' => 'required|string',
        ];
        // Mensajes personalizados (opcional)
        $mensajes = [
            'id_paciente.required' => 'El nombre es obligatorio',
            'religion.required' => 'El campo religion es obligatorio',
            'cama.required' => 'El campo cama es obligatorio',
            'fuente_informacion.required' => 'El campo fuente_informacion es obligatorio',
            'grado_confiabilidad.required' => 'El campo grado de confiabilidad es obligatorio',
            'telefono.required' => 'El campo telefono es obligatorio',
            'motivos_internacion.required' => 'El campo motivos de internacion es obligatorio',
            'historia_actual.required' => 'El campo historia de la enfermedad actual es obligatorio',
        ];
        // Validar los datos
        $validador = Validator::make($request->all(), $reglas, $mensajes);

        // Si la validación falla
        if ($validador->fails()) {
            return redirect()->back()
                ->withErrors($validador)
                ->withInput();
        }

        $nuevoRegistro = Historial::create([
            'id_paciente' => $request->input('id_paciente'),
            'id_servicio' => $request->input('id_servicio'),
            'grado_instruccion' => $request->input('grado_instruccion'),
            'religion' => $request->input('religion'), // NULL si no viene
            'cama' => $request->input('cama'), // Cadena vacía si no viene
            'nombrenum_referencia' => $request->input('nombrenum_referencia'),
            'grado_confiabilidad' => $request->input('grado_confiabilidad'),
            'grupo_sanguineo_facto' => $request->input('grupo_sanguineo_facto'),
            'nombre_recien_nacido' => $request->input('nombre_recien_nacido'),
            'fecha_recien_nacido' => $request->input('fecha_recien_nacido'),
            'hora_recien_nacido' => $request->input('hora_recien_nacido'),
            'historia_actual' => $request->input('historia_actual'),
            'diagnostico' => $request->input('diagnostico'),
            'comentario' => $request->input('comentario'),
            'diagnostico_sindromatico' => $request->input('diagnostico_sindromatico'),
            'examenes_complementarios' => $request->input('examenes_complementarios'),
            'interpretacion_lab_gab' => $request->input('interpretacion_lab_gab')

        ]);

        $idInsertado = $nuevoRegistro->id;
       echo $idInsertado;
       die(); 
    }*/



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Historial $historial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHistorialRequest $request, Historial $historial)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Historial $historial)
    {
        //
    }
}
