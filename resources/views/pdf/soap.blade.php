<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nota de Evolución</title>
    <style>
        .indent-2 {
            padding-left: 40px;
        }

        body {
            margin: 1cm;
            font-family: 'DejaVu Sans';
        }
    </style>
</head>

<body class="body">
  @foreach ($evoluciones as $evolucion)
    <table style="border-collapse: collapse;font-size: 13px;text-align:center;width: 100%;font-family: Arial, sans-serif;">
        <tr>
            <td style="text-align: center;width: 20%;">

                <p>{{$evolucion->fecha_registro}}</p>
                <p>{{$evolucion->hora_registro}}</p>

                <p style="text-align: left;">
                    PA: {{$evolucion->pa}} mmHg <br>
                    FC: {{$evolucion->fc}}lpm <br>
                    FR: {{$evolucion->fr}} rpm <br>
                    Sat: {{$evolucion->sat}}% s/a <br>
                    Sat: {{$evolucion->sat_2}}% <br>
                    FiO2: {{$evolucion->FiO2}}<br>
                    Peso: {{$evolucion->peso}} kg <br>
                    Diuresis: {{$evolucion->diuresis}} ml <br>
                    DH: {{$evolucion->dh}} ml/kg/hr
                </p>
            </td>
            <td style="text-align: left;width: 90%;">

                <h4 style="text-align: center">NOTA DE EVOLUCION</h4>
                <p>{{$evolucion->descripcion}}:</p>
                <p class="indent-2">
                  @foreach($diagnosticos as $diagnostico)
                    @if($diagnostico->id_evolucion== $evolucion->id_evolucion)
                   
                    • {{$diagnostico->diagnostico}} <br>

                    @endif
                    @endforeach
                </p>

                <p style="text-align: justify;"> S. {{$evolucion->s}} </p>
                <p style="text-align: justify;">O. {{$evolucion->o}}  </p>
                <p style="text-align: justify;">A. {{$evolucion->a}}  </p>
                <p style="text-align: justify;">P. {{$evolucion->p}} </p>

            </td>
        </tr>

    </table>
    <div style="page-break-after: always;"></div>

 @endforeach
</body>

</html>