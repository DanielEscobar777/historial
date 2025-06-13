@extends('layouts.header')
@section('content')

<div class="col-md-12 col-12">
    <div class="panel" data-sortable-id="ui-general-1">
        <div class="panel-heading" style="background-color: #c5cacf;">
            <h4 class="panel-title text-center"><b>Formulario Historia Clínica</b></h4>
        </div>
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <?php $n = 2 ?>
        <div class="panel-body">
            <!-- <pre>{{ print_r(old(), true) }}</pre> -->

            <form action="{{ route('historial.store') }}" method="POST" autocomplete="off" novalidate>
                @csrf
                <div class="row">

                    <h5 style="color:rgba(23, 93, 126, 0.87);">1.- Filiación</h5>

                    @if ($n_ser->nombre_servicio=='NEONATOLOGIA')
                    <div class="col-md-6">
                        <label><b>Nombre del Recién Nacido</b></label>
                        <input type="text" class="form-control" name="nombre_recien_necido" value="{{ old('nombre_recien_necido') }}">
                    </div>

                    <div class="col-md-6">
                        <label><b>Sexo</b></label>
                        <input type="text" class="form-control" name="sexo" value="{{ old('sexo') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label><b>Cama</b></label>
                        <input type="text" class="form-control" name="cama" value="{{ old('cama') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label><b>Fecha de Nacimiento del RN</b></label>
                        <input type="date" class="form-control" name="fecha_recien_necido" value="{{ old('fecha_recien_necido') }}">
                    </div>

                    <div class="col-md-6">
                        <label><b>Hora de Nacimiento del RN</b></label>
                        <input type="time" class="form-control" name="hora_recien_necido" value="{{ old('hora_recien_necido') }}">
                    </div>
                    <div class="col-md-6">
                        <label><b>Servicio</b></label>
                        <input type="text" class="form-control" value="{{$n_ser->nombre_servicio}} " disabled>
                        <input type="hidden" class="form-control" name="id_servicio" value="{{$n_ser->id_servicio}}">
                        <input type="hidden" class="form-control" name="nombre_servicio" value="{{$n_ser->nombre_servicio}}">
                    </div>
                    <div class="col-md-6">
                        <label><b>Referencia (Nombre y Teléfono)</b></label>
                        <input type="text" class="form-control" name="nombrenum_referencia" value="{{ old('nombrenum_referencia') }}" required>
                    </div>
                    @else

                    <div class="col-md-6">
                        <label><b>Paciente</b></label>
                        <input type="text" class="form-control" name="id_paciente" required>
                    </div>

                    <div class="col-md-6">
                        <label><b>Servicio</b></label>
                        <input type="text" class="form-control" value="{{$n_ser->nombre_servicio}} " disabled>
                        <input type="hidden" class="form-control" name="id_servicio" value="{{$n_ser->id_servicio}}">
                        <input type="hidden" class="form-control" name="nombre_servicio" value="{{$n_ser->nombre_servicio}}">

                    </div>

                    <div class="col-md-6">
                        <label><b>Grado de Instrucción</b></label>
                        <input type="text" class="form-control" name="grado_instruccion" value="{{ old('grado_instruccion') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label><b>Religión</b></label>
                        <input type="text" class="form-control" name="religion" value="{{ old('religion') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label><b>Cama</b></label>
                        <input type="text" class="form-control" name="cama" value="{{ old('cama') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label><b>Fuente de Información</b></label>
                        <input type="text" class="form-control" name="fuente_informacion" value="{{ old('fuente_informacion') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label><b>Referencia (Nombre y Teléfono)</b></label>
                        <input type="text" class="form-control" name="nombrenum_referencia" value="{{ old('nombrenum_referencia') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label><b>Grado de Confiabilidad</b></label>
                        <input type="text" class="form-control" name="grado_confiabilidad" value="{{ old('grado_confiabilidad') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label><b>Grupo Sanguíneo y Factor</b></label>
                        <input type="text" class="form-control" name="grupo_sanguineo_facto" value="{{ old('grupo_sanguineo_facto') }}" required>
                    </div>

                    @endif
                    <div class="col-md-12">

                        @foreach ($campos_organizados as $modulo => $grupo)
                        <div class="col-12">
                            <h5 style="color:rgba(23, 93, 126, 0.87);" >{{$n}}.- {{ ucwords(str_replace('_', ' ', $grupo['nombre']))}}</h5>
                        </div>
                        @foreach ($grupo['subcampos'] as $subcampo)
                        <div class="col-md-12">
                            <label><b>{{ ucwords(str_replace('_', ' ', $subcampo['etiqueta'])) }}</b></label>
                            <input type="text" class="form-control" name="campos_dinamicos[{{ $subcampo['nombre'] }}]" value="{{ old('campos_dinamicos.' . $subcampo['nombre']) }}" placeholder="Escriba descripcion...">
                        </div>
                        @endforeach
                          <?php $n++ ?>
                        @endforeach
                    </div>

                </div>

                <br>
                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Guardar</button>
                <a href="{{ route('historial.index') }}" class="btn btn-danger"><i class="fa fa-reply"></i> Cancelar</a>
            </form>
        </div>
    </div>
</div>

@endsection